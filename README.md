# MyMoney - Aplikasi Manajemen Keuangan Keluarga

## Fitur Utama

### **Untuk Orangtua**
- **Dashboard Monitoring**: Pantau saldo, pemasukan, dan pengeluaran semua anak
- **Detail Keuangan Anak**: Lihat transaksi, statistik, dan pola pengeluaran anak
- **Laporan Visual**: Chart bulanan untuk analisis tren keuangan
- **Export Data**: Download laporan transaksi dalam format CSV

### **Untuk Anak (Mahasiswa)**
- **Dashboard Pribadi**: Ringkasan keuangan pribadi dengan saldo dan anggaran
- **Manajemen Transaksi**: Catat pemasukan dan pengeluaran dengan multi-mata uang
- **Target Tabungan**: Buat dan track progress goals/target tabungan
- **Tagihan Rutin**: Kelola subscription/langganan dengan reminder jatuh tempo
- **Laporan**: Generate laporan keuangan bulanan (PDF/Excel)

## Arsitektur Sistem

### **Tech Stack**
- **Backend**: PHP Native (MVC Pattern)
- **Frontend**: HTML5, CSS3, JavaScript (jQuery, DataTables, Chart.js)
- **Database**: MySQL
- **Styling**: Bootstrap 5, Custom CSS dengan tema modern
- **Third-party**: DataTables, Chart.js, Font Awesome

### **Struktur Database**
```
mymoney_db/
├── users (id, username, email, password, role, family_code, parent_id)
├── transactions (id, user_id, category_id, type, amount, currency_code, exchange_rate)
├── categories (id, name, type, icon)
├── goals (id, user_id, title, target_amount, current_amount, deadline, status)
├── subscriptions (id, user_id, service_name, amount, billing_cycle, due_date)
└── exchange_rates (id, currency_code, rate_to_idr)
```

## Instalasi & Setup

### **1. Prerequisites**
- PHP 7.4+ dengan PDO MySQL extension
- MySQL 5.7+
- Web server (Apache/Nginx)
- Composer (opsional)

### **2. Setup Database**
```sql
mysql -u username -p mymoney_db < database/mymoney_db.sql
```

### **3. Konfigurasi**
```php
// app/config/config.php
define('BASEURL', 'http://localhost/mymoney');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mymoney_db');
```

### **4. Struktur Folder**
```
mymoney/
├── app/
│   ├── controllers/    # Controller (Auth, Student, Budget, Dashboard)
│   ├── models/         # Model (User_model, Transaction_model, Budget_model)
│   ├── core/           # Core system (App, Controller, Database)
│   └── views/          # Views (HTML templates)
├── public/
│   ├── index.php       # Entry point
│   └── assets/         # CSS, JS, images
└── README.md           # Dokumentasi
```

## Cara Menggunakan

### **Registrasi & Login**
1. **Orangtua** register terlebih dahulu → dapatkan `family_code`
2. **Anak** register dengan memasukkan `family_code` orangtua
3. Login sesuai role masing-masing

### **Fitur Orangtua**
```
1. Dashboard → Overview semua anak
3. Detail Anak → Klik anak untuk lihat detail transaksi
4. Export → Download laporan CSV
```

### **Fitur Anak**
```
1. Dashboard → Ringkasan keuangan pribadi
2. Transaksi → Tambah/edit/hapus transaksi
3. Goals → Buat target tabungan
4. Subscription → Kelola tagihan rutin
5. Report → Generate laporan bulanan
```

## Fitur Teknis

### **Multi-Mata Uang**
- Support IDR, USD, EUR, SGD, dll
- Auto-convert ke IDR untuk perhitungan
- Rate dari API external (implementasi via Helper)

### **Security Features**
- Password hashing (bcrypt)
- Session-based authentication
- Input validation & sanitization
- CSRF protection (basic)
- SQL injection prevention (PDO prepared statements)

### **Responsive Design**
- Mobile-first approach
- Responsive tables dengan DataTables
- Adaptive charts dengan Chart.js
- Touch-friendly interface

## Fitur Laporan & Analisis

### **Visualisasi Data**
- **Chart Bulanan**: Income vs Expense trend
- **Top Categories**: Kategori pengeluaran terbesar
- **Budget Progress**: Progress anggaran vs realisasi
- **Goal Tracking**: Progress target tabungan

### **Export Options**
- **CSV Export**: Data mentah untuk analisis lanjutan
- **Print View**: Format rapi untuk dicetak
- **PDF Ready**: Browser print to PDF