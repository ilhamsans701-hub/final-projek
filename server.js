const express = require('express');
const axios = require('axios');
const dotenv = require('dotenv');
const cors = require('cors');
const path = require('path');

// Load environment variables
dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.static('.'));

// In-memory cache for exchange rates (update every hour)
let exchangeRatesCache = {
    data: null,
    timestamp: null
};

const CACHE_DURATION = 10 * 60 * 1000; // 10 minutes in milliseconds (update lebih sering untuk kurs online)

// API endpoint to get exchange rates
app.get('/api/exchange-rates', async (req, res) => {
    try {
        const now = Date.now();
        const forceRefresh = req.query.refresh === 'true'; // Force refresh jika diperlukan
        
        // Check cache (kecuali jika force refresh)
        if (!forceRefresh && exchangeRatesCache.data && exchangeRatesCache.timestamp && 
            (now - exchangeRatesCache.timestamp) < CACHE_DURATION) {
            console.log('Returning cached exchange rates');
            return res.json(exchangeRatesCache.data);
        }

        // Use ExchangeRate-API (free, no API key required) - Kurs Online
        console.log('Fetching fresh exchange rates from online API...');
        
        // Coba beberapa API untuk reliability
        let response;
        try {
            // Primary API: exchangerate-api.com
            response = await axios.get('https://api.exchangerate-api.com/v4/latest/USD', {
                timeout: 8000
            });
            console.log('Successfully fetched from primary API');
        } catch (primaryError) {
            console.warn('Primary API failed, trying alternative...', primaryError.message);
            // Alternative API: exchangerate.host (backup)
            try {
                response = await axios.get('https://api.exchangerate.host/latest?base=USD', {
                    timeout: 8000
                });
                // Transform response format to match
                response.data = { rates: response.data.rates };
                console.log('Successfully fetched from alternative API');
            } catch (altError) {
                throw new Error('All online APIs failed');
            }
        }
        
        // Cache the response
        exchangeRatesCache = {
            data: response.data.rates,
            timestamp: now
        };

        console.log('Online exchange rates updated successfully');
        res.json(response.data.rates);
    } catch (error) {
        console.error('Error fetching exchange rates:', error.message);
        
        // Jika cache masih ada, gunakan cache meski sudah expired
        if (exchangeRatesCache.data) {
            console.log('Using expired cache as fallback');
            return res.json(exchangeRatesCache.data);
        }
        
        // Return fallback rates if API fails and no cache
        res.json({
            USD: 1,
            EUR: 0.92,
            GBP: 0.79,
            JPY: 149.50,
            IDR: 15650,
            SGD: 1.34,
            MYR: 4.68
        });
    }
});

// API endpoint to convert currency
app.post('/api/convert', async (req, res) => {
    try {
        const { amount, from, to } = req.body;

        // Validasi input
        if (amount === undefined || amount === null || isNaN(amount) || amount < 0) {
            return res.status(400).json({ error: 'Invalid amount parameter' });
        }

        if (!from || !to) {
            return res.status(400).json({ error: 'Missing currency parameters (from/to)' });
        }

        if (from === to) {
            return res.json({
                convertedAmount: amount,
                rate: 1
            });
        }

        // Get exchange rates
        let rates;
        const now = Date.now();
        
        try {
            if (exchangeRatesCache.data && exchangeRatesCache.timestamp && 
                (now - exchangeRatesCache.timestamp) < CACHE_DURATION) {
                rates = exchangeRatesCache.data;
            } else {
                const response = await axios.get('https://api.exchangerate-api.com/v4/latest/USD', {
                    timeout: 5000 // 5 second timeout
                });
                rates = response.data.rates;
                exchangeRatesCache = {
                    data: rates,
                    timestamp: now
                };
            }

            // Validasi rates
            if (!rates || Object.keys(rates).length === 0) {
                throw new Error('No rates available');
            }

            // Convert: first to USD, then to target currency
            let amountInUSD;
            if (from === 'USD') {
                amountInUSD = amount;
            } else {
                if (!rates[from]) {
                    return res.status(400).json({ error: `Currency ${from} not found in rates` });
                }
                amountInUSD = amount / rates[from];
            }

            let convertedAmount;
            if (to === 'USD') {
                convertedAmount = amountInUSD;
            } else {
                if (!rates[to]) {
                    return res.status(400).json({ error: `Currency ${to} not found in rates` });
                }
                convertedAmount = amountInUSD * rates[to];
            }

            // Calculate rate
            let rate;
            if (from === 'USD') {
                rate = rates[to];
            } else if (to === 'USD') {
                rate = 1 / rates[from];
            } else {
                rate = rates[to] / rates[from];
            }

            res.json({
                convertedAmount: convertedAmount,
                rate: rate
            });
        } catch (apiError) {
            console.error('Error fetching rates from online API:', apiError.message);
            
            // Coba gunakan cache yang expired sebagai fallback terakhir
            if (exchangeRatesCache.data) {
                console.log('Using expired cache as last resort');
                rates = exchangeRatesCache.data;
                
                // Convert using cached rates
                let amountInUSD = from === 'USD' ? amount : (amount / (rates[from] || 1));
                let convertedAmount = to === 'USD' ? amountInUSD : (amountInUSD * (rates[to] || 1));
                let rate = from === 'USD' ? rates[to] : (to === 'USD' ? 1 / rates[from] : rates[to] / rates[from]);
                
                return res.json({
                    convertedAmount: convertedAmount,
                    rate: rate,
                    warning: 'Using cached rates - Online API unavailable'
                });
            }
            
            // Last resort: fallback rates
            const fallbackRates = {
                USD: 1,
                EUR: 0.92,
                GBP: 0.79,
                JPY: 149.50,
                IDR: 15650,
                SGD: 1.34,
                MYR: 4.68
            };

            let amountInUSD = from === 'USD' ? amount : (amount / (fallbackRates[from] || 1));
            let convertedAmount = to === 'USD' ? amountInUSD : (amountInUSD * (fallbackRates[to] || 1));
            let rate = from === 'USD' ? fallbackRates[to] : (to === 'USD' ? 1 / fallbackRates[from] : fallbackRates[to] / fallbackRates[from]);

            res.json({
                convertedAmount: convertedAmount,
                rate: rate,
                warning: 'Using offline rates - Online API unavailable'
            });
        }
    } catch (error) {
        console.error('Error converting currency:', error.message);
        res.status(500).json({ error: 'Failed to convert currency: ' + error.message });
    }
});

// Serve index.html for root route
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'index.html'));
});

// Start server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
    console.log(`Environment: ${process.env.NODE_ENV || 'development'}`);
});

