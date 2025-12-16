// State management
let monthlyBudget = 0;
let expenses = [];
let exchangeRates = {};
let lastUpdateTime = null;
let autoRefreshInterval = null;
let currentUserRole = null; // 'anak' atau 'orangtua'
const ROLE_STORAGE_KEY = 'currentUserRole';

// Load data from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const isLoginPage = !!loginForm;

    if (isLoginPage) {
        // Halaman login: hanya inisialisasi form login
        initLoginForms();
        return;
    }

    // Halaman utama (index): cek role dari localStorage
    const savedRole = localStorage.getItem(ROLE_STORAGE_KEY);
    if (savedRole === 'anak' || savedRole === 'orangtua') {
        currentUserRole = savedRole;
    } else {
        // Jika belum login, arahkan ke halaman login
        window.location.href = 'login.html';
        return;
    }

    loadBudget();
    loadExpenses();
    updateDashboard();
    
    // Load kurs online saat pertama kali (force refresh untuk kurs terbaru)
    loadExchangeRates(false, true);
    
    // Auto-refresh exchange rates untuk selalu mendapatkan kurs online
    startAutoRefresh();

    applyRolePermissions();
});

// Budget Management
function setBudget() {
    if (currentUserRole !== 'orangtua') {
        alert('Hanya Orang Tua yang boleh mengatur anggaran.');
        return;
    }

    const budgetInput = document.getElementById('budget-amount');
    const amount = parseFloat(budgetInput.value);

    if (isNaN(amount) || amount < 0) {
        alert('Masukkan jumlah anggaran yang valid!');
        return;
    }

    monthlyBudget = amount;
    saveBudget();
    updateDashboard();
    budgetInput.value = '';
    alert('Anggaran berhasil diset!');
}

function saveBudget() {
    localStorage.setItem('monthlyBudget', monthlyBudget.toString());
}

function loadBudget() {
    const saved = localStorage.getItem('monthlyBudget');
    if (saved) {
        monthlyBudget = parseFloat(saved);
    }
}

// Expense Management
function addExpense() {
    const description = document.getElementById('expense-description').value.trim();
    const amountInput = document.getElementById('expense-amount');
    const amount = parseFloat(amountInput.value);

    if (!description) {
        alert('Masukkan deskripsi pengeluaran!');
        return;
    }

    if (isNaN(amount) || amount < 0) {
        alert('Masukkan jumlah yang valid!');
        return;
    }

    const now = new Date();
    const expense = {
        id: Date.now(),
        description: description,
        amount: amount,
        date: now.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }),
        timestamp: now.getTime() // Simpan timestamp untuk filtering
    };

    expenses.push(expense);
    saveExpenses();
    updateDashboard();
    renderExpenses();

    // Clear form
    document.getElementById('expense-description').value = '';
    amountInput.value = '';
}

function deleteExpense(id) {
    if (currentUserRole !== 'orangtua') {
        alert('Hanya Orang Tua yang boleh menghapus pengeluaran.');
        return;
    }

    expenses = expenses.filter(expense => expense.id !== id);
    saveExpenses();
    updateDashboard();
    renderExpenses();
}

function saveExpenses() {
    localStorage.setItem('expenses', JSON.stringify(expenses));
}

function loadExpenses() {
    const saved = localStorage.getItem('expenses');
    if (saved) {
        expenses = JSON.parse(saved);
        
        // Migrate old expenses yang belum punya timestamp
        expenses = expenses.map(expense => {
            if (!expense.timestamp && expense.date) {
                // Coba parse dari string date atau gunakan id sebagai fallback
                const parsedDate = new Date(expense.date);
                if (isNaN(parsedDate.getTime())) {
                    // Jika parsing gagal, gunakan id (timestamp creation) sebagai timestamp
                    expense.timestamp = expense.id;
                } else {
                    expense.timestamp = parsedDate.getTime();
                }
            }
            return expense;
        });
        
        // Simpan kembali jika ada perubahan
        if (expenses.length > 0) {
            saveExpenses();
        }
    }
    renderExpenses();
}

function renderExpenses() {
    const container = document.getElementById('expenses-container');
    
    if (expenses.length === 0) {
        container.innerHTML = '<p class="empty-message">Belum ada pengeluaran</p>';
        return;
    }

    container.innerHTML = expenses.map(expense => `
        <div class="expense-item">
            <div class="expense-item-info">
                <div class="expense-item-description">${expense.description}</div>
                <div class="expense-item-date">${expense.date}</div>
            </div>
            <div class="expense-item-amount">Rp ${formatNumber(expense.amount)}</div>
            <button class="delete-btn" onclick="deleteExpense(${expense.id})">Hapus</button>
        </div>
    `).join('');

    // Terapkan ulang hak akses untuk tombol hapus setelah render
    applyRolePermissions();
}

function getCurrentMonthExpenses() {
    const now = new Date();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();

    return expenses.filter(expense => {
        // Gunakan timestamp jika tersedia, jika tidak gunakan parsing date
        let expenseDate;
        if (expense.timestamp) {
            expenseDate = new Date(expense.timestamp);
        } else {
            // Fallback untuk data lama yang belum punya timestamp
            expenseDate = new Date(expense.date);
        }
        
        // Cek jika date valid dan sesuai bulan/tahun saat ini
        if (isNaN(expenseDate.getTime())) {
            return false; // Skip jika date tidak valid
        }
        
        return expenseDate.getMonth() === currentMonth && 
               expenseDate.getFullYear() === currentYear;
    });
}

function getTotalExpenses() {
    const currentMonthExpenses = getCurrentMonthExpenses();
    return currentMonthExpenses.reduce((total, expense) => total + expense.amount, 0);
}

// Dashboard Updates
function updateDashboard() {
    const totalExpenses = getTotalExpenses();
    const availableBalance = monthlyBudget - totalExpenses;

    document.getElementById('available-balance').textContent = 
        `Rp ${formatNumber(availableBalance)}`;
    document.getElementById('monthly-expense').textContent = 
        `Rp ${formatNumber(totalExpenses)}`;
    document.getElementById('monthly-budget').textContent = 
        `Rp ${formatNumber(monthlyBudget)}`;

    // Update card color based on balance
    const balanceCard = document.querySelector('.card:first-child');
    if (availableBalance < 0) {
        balanceCard.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    } else if (availableBalance < monthlyBudget * 0.2) {
        balanceCard.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
    } else {
        balanceCard.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
    }
}

// Currency Converter
async function loadExchangeRates(showLoading = false, forceRefresh = false) {
    const refreshBtn = document.getElementById('refresh-rates-btn');
    const lastUpdateInfo = document.getElementById('last-update-info');
    
    if (showLoading && refreshBtn) {
        const originalText = refreshBtn.textContent;
        refreshBtn.textContent = 'Memperbarui...';
        refreshBtn.disabled = true;
    }
    
    try {
        // Tambahkan query parameter untuk force refresh jika diperlukan
        const url = forceRefresh ? '/api/exchange-rates?refresh=true' : '/api/exchange-rates';
        const response = await fetch(url);
        if (response.ok) {
            const data = await response.json();
            exchangeRates = data;
            lastUpdateTime = new Date();
            
            // Update UI dengan timestamp
            if (lastUpdateInfo) {
                const timeStr = lastUpdateTime.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                lastUpdateInfo.textContent = `Kurs online terakhir diperbarui: ${timeStr}`;
                lastUpdateInfo.style.color = '#10b981';
            }
            
            console.log('Exchange rates loaded:', exchangeRates);
            
            // Simpan timestamp ke localStorage
            localStorage.setItem('exchangeRatesLastUpdate', lastUpdateTime.getTime().toString());
            
            // Jika ada nilai di form, auto-convert ulang
            const fromAmount = parseFloat(document.getElementById('from-amount').value);
            if (fromAmount && fromAmount > 0) {
                convertCurrency();
            }
        } else {
            // Fallback rates jika server error
            setFallbackRates();
            if (lastUpdateInfo) {
                lastUpdateInfo.textContent = 'Menggunakan kurs offline';
                lastUpdateInfo.style.color = '#f59e0b';
            }
        }
    } catch (error) {
        console.error('Error loading exchange rates:', error);
        // Gunakan fallback rates jika tidak bisa connect ke server
        setFallbackRates();
        if (lastUpdateInfo) {
            lastUpdateInfo.textContent = 'Menggunakan kurs offline';
            lastUpdateInfo.style.color = '#f59e0b';
        }
    } finally {
        if (showLoading && refreshBtn) {
            refreshBtn.textContent = '🔄 Update Kurs Online';
            refreshBtn.disabled = false;
        }
    }
}

async function refreshExchangeRates() {
    await loadExchangeRates(true, true); // Force refresh dari server
}

function startAutoRefresh() {
    // Hapus interval sebelumnya jika ada
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    
    // Auto-refresh setiap 10 menit untuk selalu mendapatkan kurs online terbaru
    autoRefreshInterval = setInterval(async () => {
        console.log('Auto-refreshing exchange rates from online API...');
        await loadExchangeRates(false, true); // Force refresh untuk kurs online
    }, 10 * 60 * 1000); // 10 menit
    
    // Cek last update time dari localStorage
    const savedUpdateTime = localStorage.getItem('exchangeRatesLastUpdate');
    if (savedUpdateTime) {
        const savedTime = new Date(parseInt(savedUpdateTime));
        const now = new Date();
        const diffMinutes = (now - savedTime) / (1000 * 60);
        
        // Jika sudah lebih dari 10 menit, refresh sekarang untuk kurs online terbaru
        if (diffMinutes > 10) {
            console.log('Exchange rates outdated, refreshing from online API now...');
            loadExchangeRates(false, true); // Force refresh untuk kurs online
        } else {
            // Update UI dengan saved timestamp
            const lastUpdateInfo = document.getElementById('last-update-info');
            if (lastUpdateInfo) {
                const timeStr = savedTime.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                lastUpdateInfo.textContent = `Kurs online terakhir diperbarui: ${timeStr}`;
                lastUpdateInfo.style.color = '#10b981';
            }
            lastUpdateTime = savedTime;
        }
    }
}

function setFallbackRates() {
    // Fallback rates (diperbarui Desember 2024)
    exchangeRates = {
        USD: 1,
        EUR: 0.92,
        GBP: 0.79,
        JPY: 150.50,
        IDR: 16710,
        SGD: 1.34,
        MYR: 4.70
    };
    console.log('Using fallback exchange rates');
}

async function convertCurrency() {
    const fromAmountInput = document.getElementById('from-amount');
    const toAmountInput = document.getElementById('to-amount');
    const rateInfo = document.getElementById('exchange-rate-info');
    const convertBtn = document.getElementById('convert-btn') || document.querySelector('button.btn-primary');
    
    const fromAmount = parseFloat(fromAmountInput.value);
    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;

    // Validasi input
    if (isNaN(fromAmount) || fromAmount < 0) {
        alert('Masukkan jumlah yang valid!');
        fromAmountInput.focus();
        return;
    }

    if (!fromAmount) {
        alert('Masukkan jumlah yang ingin dikonversi!');
        fromAmountInput.focus();
        return;
    }

    // Jika mata uang sama
    if (fromCurrency === toCurrency) {
        toAmountInput.value = fromAmount.toFixed(2);
        rateInfo.textContent = 'Mata uang sama, tidak perlu konversi';
        return;
    }

    // Tampilkan loading
    const originalBtnText = convertBtn.textContent;
    convertBtn.textContent = 'Mengkonversi...';
    convertBtn.disabled = true;
    rateInfo.textContent = 'Memproses konversi...';
    toAmountInput.value = '';

    try {
        // Coba konversi via server dulu
        const response = await fetch('/api/convert', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                amount: fromAmount,
                from: fromCurrency,
                to: toCurrency
            })
        });

        if (response.ok) {
            const data = await response.json();
            toAmountInput.value = data.convertedAmount.toFixed(2);
            
            if (data.rate) {
                rateInfo.textContent = 
                    `Kurs: 1 ${fromCurrency} = ${formatNumber(data.rate.toFixed(4))} ${toCurrency}`;
            } else {
                rateInfo.textContent = 'Konversi berhasil';
            }
        } else {
            // Jika server error, gunakan client-side conversion
            throw new Error('Server error, using client-side conversion');
        }
    } catch (error) {
        console.warn('Server conversion failed, using client-side:', error);
        
        // Client-side conversion sebagai fallback
        if (Object.keys(exchangeRates).length === 0) {
            setFallbackRates();
        }

        try {
            const convertedAmount = convertCurrencyClientSide(fromAmount, fromCurrency, toCurrency);
            const rate = calculateRate(fromCurrency, toCurrency);
            
            toAmountInput.value = convertedAmount.toFixed(2);
            rateInfo.textContent = 
                `Kurs (offline): 1 ${fromCurrency} = ${formatNumber(rate.toFixed(4))} ${toCurrency}`;
        } catch (clientError) {
            console.error('Client-side conversion error:', clientError);
            alert('Error melakukan konversi. Pastikan koneksi internet dan server berjalan!');
            rateInfo.textContent = 'Konversi gagal';
        }
    } finally {
        // Restore button
        convertBtn.textContent = originalBtnText;
        convertBtn.disabled = false;
    }
}

function convertCurrencyClientSide(amount, from, to) {
    // Pastikan rates sudah ada
    if (Object.keys(exchangeRates).length === 0) {
        setFallbackRates();
    }

    // Konversi: dari currency -> USD -> target currency
    let amountInUSD;
    if (from === 'USD') {
        amountInUSD = amount;
    } else {
        if (!exchangeRates[from]) {
            throw new Error(`Rate untuk ${from} tidak ditemukan`);
        }
        amountInUSD = amount / exchangeRates[from];
    }

    let convertedAmount;
    if (to === 'USD') {
        convertedAmount = amountInUSD;
    } else {
        if (!exchangeRates[to]) {
            throw new Error(`Rate untuk ${to} tidak ditemukan`);
        }
        convertedAmount = amountInUSD * exchangeRates[to];
    }

    return convertedAmount;
}

function calculateRate(from, to) {
    if (Object.keys(exchangeRates).length === 0) {
        setFallbackRates();
    }

    if (from === to) return 1;
    
    if (from === 'USD') {
        return exchangeRates[to] || 1;
    }
    
    if (to === 'USD') {
        return 1 / (exchangeRates[from] || 1);
    }
    
    // from -> USD -> to
    const fromToUSD = exchangeRates[from] || 1;
    const usdToTo = exchangeRates[to] || 1;
    return usdToTo / fromToUSD;
}

function swapCurrencies() {
    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;
    const fromAmount = document.getElementById('from-amount').value;
    const toAmount = document.getElementById('to-amount').value;

    // Swap currencies
    document.getElementById('from-currency').value = toCurrency;
    document.getElementById('to-currency').value = fromCurrency;
    
    // Swap amounts (hasil konversi jadi input baru)
    document.getElementById('from-amount').value = toAmount;
    document.getElementById('to-amount').value = '';

    // Auto convert jika ada nilai di input baru
    if (toAmount && parseFloat(toAmount) > 0) {
        convertCurrency();
    }
}

// Utility Functions
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Login & Role Handling (UI only, belum terhubung ke backend)
function applyRolePermissions() {
    const budgetInput = document.getElementById('budget-amount');
    const setBudgetBtn = document.querySelector('button[onclick="setBudget()"]');
    const roleInfo = document.getElementById('user-role-info');
    const deleteButtons = document.querySelectorAll('.delete-btn');

    if (currentUserRole === 'orangtua') {
        if (budgetInput) budgetInput.disabled = false;
        if (setBudgetBtn) setBudgetBtn.disabled = false;
        if (roleInfo) roleInfo.textContent = 'Masuk sebagai: Orang Tua';
        deleteButtons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('disabled');
        });
    } else if (currentUserRole === 'anak') {
        if (budgetInput) budgetInput.disabled = true;
        if (setBudgetBtn) setBudgetBtn.disabled = true;
        if (roleInfo) roleInfo.textContent = 'Masuk sebagai: Anak';
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('disabled');
        });
    } else {
        if (budgetInput) budgetInput.disabled = true;
        if (setBudgetBtn) setBudgetBtn.disabled = true;
        if (roleInfo) roleInfo.textContent = '';
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('disabled');
        });
    }
}

// Login Forms (UI only)
function initLoginForms() {
    const loginForm = document.getElementById('login-form');
    const usernameInput = document.getElementById('login-username');
    const passwordInput = document.getElementById('login-password');
    const anakButton = document.getElementById('login-as-anak');
    const ortuButton = document.getElementById('login-as-orangtua');

    if (!loginForm || !usernameInput || !passwordInput || !anakButton || !ortuButton) {
        return;
    }

    // Mencegah submit default form (enter) agar tidak reload halaman
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
    });

    function doLogin(role) {
        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();

        if (!username || !password) {
            alert('Masukkan username dan password terlebih dahulu.');
            return;
        }

        // Validasi sederhana username & password per role (dummy, tanpa backend)
        if (role === 'anak') {
            // Contoh kredensial anak
            if (username !== 'anak' || password !== 'anak123') {
                alert('Username atau password Anak salah. Coba username: anak, password: anak123');
                return;
            }
        } else if (role === 'orangtua') {
            // Contoh kredensial orang tua
            if (username !== 'orangtua' || password !== 'ortu123') {
                alert('Username atau password Orang Tua salah. Coba username: orangtua, password: ortu123');
                return;
            }
        }

        currentUserRole = role;
        localStorage.setItem(ROLE_STORAGE_KEY, role);

        if (role === 'anak') {
            alert('Login sebagai Anak berhasil. Anda sekarang bisa menambahkan pengeluaran.');
        } else {
            alert('Login sebagai Orang Tua berhasil. Anda dapat mengatur anggaran dan memantau pengeluaran.');
        }

        // Arahkan ke halaman utama setelah login
        window.location.href = 'index.html';
    }

    anakButton.addEventListener('click', function () {
        doLogin('anak');
    });

    ortuButton.addEventListener('click', function () {
        doLogin('orangtua');
    });
}

// Allow Enter key to trigger conversion
document.addEventListener('DOMContentLoaded', function() {
    const fromAmountInput = document.getElementById('from-amount');
    if (fromAmountInput) {
        fromAmountInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                convertCurrency();
            }
        });

        // Auto-convert saat amount berubah (optional, bisa diaktifkan jika perlu)
        // fromAmountInput.addEventListener('input', function() {
        //     if (this.value && parseFloat(this.value) > 0) {
        //         convertCurrency();
        //     }
        // });
    }

    // Auto-convert saat currency berubah
    const fromCurrencySelect = document.getElementById('from-currency');
    const toCurrencySelect = document.getElementById('to-currency');
    
    if (fromCurrencySelect) {
        fromCurrencySelect.addEventListener('change', function() {
            const fromAmount = parseFloat(document.getElementById('from-amount').value);
            if (fromAmount && fromAmount > 0) {
                convertCurrency();
            }
        });
    }
    
    if (toCurrencySelect) {
        toCurrencySelect.addEventListener('change', function() {
            const fromAmount = parseFloat(document.getElementById('from-amount').value);
            if (fromAmount && fromAmount > 0) {
                convertCurrency();
            }
        });
    }
});

