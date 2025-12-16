<?php

class Currency {
    
    // Durasi Cache: 12 Jam (43200 detik)
    // Jika data di database lebih baru dari 12 jam, kita tidak akan hit API.
    private static $cacheDuration = 43200; 

    public static function getRate($fromCurrency, $toCurrency = 'IDR')
    {
        // 1. Jika mata uang sama, return 1
        if ($fromCurrency == $toCurrency) {
            return 1;
        }

        // 2. CEK DATABASE (CACHE)
        $db = new Database;
        $query = "SELECT * FROM exchange_rates WHERE currency_code = :code";
        $db->query($query);
        $db->bind('code', $fromCurrency);
        $cachedData = $db->single();

        if ($cachedData) {
            $lastUpdate = strtotime($cachedData['updated_at']);
            $now = time();
            
            // Logic: Jika data ada DAN umurnya belum lewat 12 jam
            if (($now - $lastUpdate) < self::$cacheDuration) {
                // Gunakan data database (Hemat Kuota)
                return $cachedData['rate_to_idr'];
            }
        }

        // 3. JIKA CACHE BASI/TIDAK ADA -> HIT API
        $rateFromApi = self::fetchFromApi($fromCurrency, $toCurrency);

        // 4. SIMPAN HASIL KE DATABASE (Update Cache)
        if ($rateFromApi > 1) { 
            if ($cachedData) {
                // Update data lama
                $updateQuery = "UPDATE exchange_rates SET rate_to_idr = :rate, updated_at = NOW() WHERE currency_code = :code";
                $db->query($updateQuery);
            } else {
                // Insert data baru
                $insertQuery = "INSERT INTO exchange_rates (currency_code, rate_to_idr) VALUES (:code, :rate)";
                $db->query($insertQuery);
            }
            // Bind parameter
            $db->bind('rate', $rateFromApi);
            $db->bind('code', $fromCurrency);
            $db->execute();
        }

        return $rateFromApi;
    }

    private static function fetchFromApi($fromCurrency, $toCurrency)
    {
        // Ambil Key dari Config (yang sudah terhubung ke .env)
        if (!defined('EXCHANGE_RATE_KEY') || empty(EXCHANGE_RATE_KEY)) {
            return self::getFallbackRate($fromCurrency);
        }

        try {
            // URL v6 Endpoint
            $apiKey = EXCHANGE_RATE_KEY;
            $url = "https://v6.exchangerate-api.com/v6/" . $apiKey . "/latest/" . $fromCurrency;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout 5 detik
            
            $output = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $data = json_decode($output, true);
                
                // Cek status sukses
                if (isset($data['result']) && $data['result'] == 'success') {
                    if (isset($data['conversion_rates'][$toCurrency])) {
                        return $data['conversion_rates'][$toCurrency];
                    }
                }
            }
            // Jika gagal, pakai fallback
            return self::getFallbackRate($fromCurrency);

        } catch (Exception $e) {
            return self::getFallbackRate($fromCurrency);
        }
    }

    private static function getFallbackRate($currency)
    {
        // Nilai estimasi jika offline
        $rates = [
            'USD' => 15850,
            'EUR' => 17200,
            'SGD' => 11900,
            'JPY' => 109,
            'IDR' => 1
        ];
        return isset($rates[$currency]) ? $rates[$currency] : 1;
    }
}