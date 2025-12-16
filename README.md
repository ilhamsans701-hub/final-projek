# MyMoney - Aplikasi Manajemen Keuangan Mahasiswa

Aplikasi web sederhana untuk membantu mahasiswa pemula dalam mengelola keuangan pribadi mereka. Aplikasi ini mencakup fitur tracking anggaran, pengeluaran, dan konverter mata uang.

## Fitur

- 📊 **Dashboard Keuangan**: Lihat saldo tersedia, pengeluaran bulanan, dan anggaran
- 💰 **Pengatur Anggaran**: Set anggaran bulanan dan tracking pengeluaran
- 📝 **Pencatatan Pengeluaran**: Tambah dan hapus pengeluaran dengan deskripsi
- 💱 **Konverter Mata Uang**: Konversi antar mata uang secara real-time
- 💾 **Penyimpanan Lokal**: Data disimpan di browser menggunakan localStorage

## Teknologi yang Digunakan

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: Node.js, Express.js
- **API**: ExchangeRate-API (gratis, tanpa API key)
- **Styling**: CSS Custom dengan gradient dan responsive design

## Instalasi

1. Clone atau download repository ini
2. Install dependencies:
   ```bash
   npm install
   ```

3. (Opsional) Konfigurasi environment variables di file `.env`:
   ```
   PORT=3000
   NODE_ENV=development
   ```

4. Jalankan server:
   ```bash
   npm start
   ```
   atau untuk development dengan auto-reload:
   ```bash
   npm run dev
   ```

5. Buka browser dan akses: `http://localhost:3000`

## Struktur Project

```
mymoney-student-pemula/
├── index.html         # Halaman utama (dashboard sederhana)
├── style.css          # Styling global
├── script.js          # Logika JavaScript (kalkulator kurs, anggaran)
├── server.js          # Backend sederhana (Node.js untuk API kurs)
├── package.json       # Dependencies (express, axios)
├── .env               # Kunci API kurs (jangan commit)
├── README.md          # Dokumentasi singkat
└── assets/            # Folder untuk gambar atau file statis
    └── logo.png
```

## Cara Menggunakan

### 1. Set Anggaran Bulanan
- Masukkan jumlah anggaran bulanan Anda
- Klik tombol "Set Anggaran"

### 2. Tambah Pengeluaran
- Isi deskripsi pengeluaran (contoh: "Makan siang")
- Masukkan jumlah pengeluaran
- Klik "Tambah Pengeluaran"

### 3. Konversi Mata Uang
- Pilih mata uang asal dan tujuan
- Masukkan jumlah yang ingin dikonversi
- Klik "Konversi"
- Gunakan tombol "Tukar" untuk menukar mata uang

## Catatan Penting

- File `.env` tidak perlu di-commit ke repository (sudah ada di .gitignore)
- Data pengeluaran dan anggaran disimpan di localStorage browser
- Aplikasi menggunakan ExchangeRate-API yang gratis (tidak memerlukan API key)
- Pastikan server berjalan untuk fitur konverter mata uang

## Dependencies

- `express`: Web framework untuk Node.js
- `axios`: HTTP client untuk API requests
- `dotenv`: Load environment variables
- `cors`: Enable CORS untuk API

## License

ISC

## Kontribusi

Silakan buat issue atau pull request jika ingin berkontribusi pada project ini.

