<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<!-- Ubah data-bs-theme menjadi light -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMoney - Web Keuangan Mahasiswa dengan Kurs Otomatis</title>

    <!-- Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --secondary: #8b5cf6;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #0f172a;
        --light: #f8fafc;
        --gradient-primary: linear-gradient(135deg, #6366f1, #8b5cf6);
        /* Ubah variabel dark dan light untuk tema terang */
        --gradient-dark: linear-gradient(135deg, #e2e8f0, #f1f5f9);
        --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.1);
        --bg-color: #ffffff;
        --text-color: #1e293b;
        --card-bg: rgba(255, 255, 255, 0.9);
        --border-color: rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--gradient-dark);
        color: var(--text-color);
        min-height: 100vh;
        overflow-x: hidden;
    }

    .hero-section {
        min-height: 100vh;
        padding-top: 80px;
        /* Ubah background untuk tema terang */
        background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
            linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        position: relative;
    }

    .display-1 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 15px rgba(99, 102, 241, 0.1);
    }

    .feature-card {
        /* Ubah background dan border untuk tema terang */
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: var(--shadow-lg);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: var(--gradient-primary);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
    }

    .btn-primary {
        background: var(--gradient-primary);
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
    }

    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .glass-effect {
        /* Ubah glass effect untuk tema terang */
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    }

    .nav-link {
        font-weight: 500;
        transition: all 0.3s;
        color: var(--text-color) !important;
    }

    .nav-link:hover {
        color: var(--primary) !important;
    }

    .nav-link.active {
        color: var(--primary) !important;
        font-weight: 600;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .api-badge {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
        font-weight: 600;
    }

    .notification-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-weight: 600;
    }

    .text-light {
        color: var(--text-color) !important;
    }

    .border-dark {
        border-color: var(--border-color) !important;
    }

    .navbar {
        /* Navbar untuk tema terang */
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-color);
    }

    .navbar-brand {
        color: var(--text-color) !important;
    }

    .btn-outline-light {
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .btn-outline-light:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-color: var(--primary);
        color: var(--primary);
    }

    .alert-primary {
        background-color: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.2);
        color: var(--text-color);
    }

    footer {
        background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid var(--border-color) !important;
    }

    .badge.bg-dark {
        background-color: #e2e8f0 !important;
        color: var(--text-color) !important;
    }
    </style>
</head>

<body>

    <!-- Animated Background Elements -->
    <div class="position-fixed w-100 h-100" style="z-index: -2;">
        <div class="floating"
            style="position: absolute; top: 10%; left: 5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);">
        </div>
        <div class="floating"
            style="animation-delay: 1s; position: absolute; bottom: 10%; right: 5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(139, 92, 246, 0.05) 0%, transparent 70%);">
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top glass-effect py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#home">
                <i class="fas fa-wallet me-2"></i>MyMoney<span class="text-primary">.</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item ms-2"><a href="<?= BASEURL; ?>/auth"
                            class="btn btn-outline-light rounded-pill px-4">Masuk</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 animate__animated animate__fadeInLeft">
                    <h6 class="text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>SOLUSI KEUANGAN MAHASISWA
                    </h6>
                    <h1 class="display-1 fw-bold mb-4">Kelola Keuangan <span class="text-warning">Kuliah</span> dengan
                        Lebih Mudah</h1>
                    <p class="lead mb-4">Web aplikasi keuangan mahasiswa dengan <strong>kurs
                            otomatis</strong>, <strong>notifikasi jatuh tempo</strong> pembayaran, dan <strong>analitik
                            pengeluaran</strong> pintar.</p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="<?= BASEURL; ?>/auth/register" class="btn btn-primary text-white fw-bold">
                            <i class="fas fa-rocket me-2"></i>Mulai Sekarang Gratis
                        </a>
                        <a href="#fitur" class="btn btn-outline-light rounded-pill px-4">
                            <i class="fas fa-play-circle me-2"></i>Lihat Demo Fitur
                        </a>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-number">500+</div>
                            <small>Mahasiswa</small>
                        </div>
                        <div class="col-4">
                            <div class="stat-number">50+</div>
                            <small>Universitas</small>
                        </div>
                        <div class="col-4">
                            <div class="stat-number">5+</div>
                            <small>Mata Uang</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="position-relative">
                        <div class="card glass-effect border-0 rounded-4 p-4 shadow-lg">
                            <div class="card-body">
                                <!-- Dashboard Preview -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h5 class="fw-bold mb-1">Dashboard Mahasiswa</h5>
                                        <small>Monitor keuangan & tagihan kuliah</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge api-badge">Kurs Otomatis</span>
                                        <span class="badge notification-badge">Notifikasi</span>
                                    </div>
                                </div>

                                <!-- Stats REAL -->
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.1);">
                                            <small class="text-success">Pemasukan Bulan Ini</small>
                                            <div class="fw-bold fs-5">Rp 5.000.000</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background: rgba(239, 68, 68, 0.1);">
                                            <small class="text-danger">Pengeluaran Bulan Ini</small>
                                            <div class="fw-bold fs-5">Rp 2.100.000</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tagihan & Transaksi Preview -->
                                <div class="mb-4">
                                    <small class="d-block mb-2">Tagihan Mendatang:</small>
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                        style="background: rgba(239, 68, 68, 0.05);">
                                        <div>
                                            <i class="fas fa-university text-danger me-2"></i>
                                            <span>SPP Semester</span>
                                        </div>
                                        <div>
                                            <span class="text-danger fw-bold">Rp 2.500.000</span>
                                            <small class="ms-2">(H-3)</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <i class="fas fa-home text-warning me-2"></i>
                                            <span>Bayar Kos</span>
                                        </div>
                                        <span class="text-warning">Rp 1.200.000</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-wallet text-success me-2"></i>
                                            <span>Uang Saku</span>
                                        </div>
                                        <span class="text-success">+ Rp 1.000.000</span>
                                    </div>
                                </div>

                                <div class="text-center pt-2">
                                    <a href="<?= BASEURL; ?>/auth" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-external-link-alt me-2"></i>Coba Dashboard Lengkap
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="position-absolute top-0 start-0 translate-middle floating"
                            style="animation-delay: 0.5s;">
                            <div class="p-3 rounded-3 glass-effect" style="width: 120px;">
                                <i class="fas fa-chart-line text-primary fs-4 mb-2"></i>
                                <small>Analitik</small>
                            </div>
                        </div>

                        <div class="position-absolute bottom-0 end-0 translate-middle floating"
                            style="animation-delay: 1s;">
                            <div class="p-3 rounded-3 glass-effect" style="width: 120px;">
                                <i class="fas fa-bell text-danger fs-4 mb-2"></i>
                                <small>Notifikasi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-5" style="background: #f8fafc;">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Fitur Unggulan <span class="text-primary">MyMoney</span></h2>
                <p class="lead">Semua yang mahasiswa butuhkan untuk mengelola keuangan kuliah</p>
            </div>

            <div class="row g-4">
                <!-- Fitur 1: API Kurs Otomatis -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">API Kurs Otomatis</h4>
                        <p>Integrasi dengan Exchange Rate API untuk konversi mata uang asing ke
                            Rupiah secara real-time.</p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Support USD, EUR, SGD, JPY</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Cache 12 jam untuk efisiensi</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Fallback rates jika offline</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Auto update kurs harian</li>
                        </ul>
                    </div>
                </div>

                <!-- Fitur 2: Notifikasi Jatuh Tempo -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Notifikasi Jatuh Tempo</h4>
                        <p>Sistem pengingat otomatis untuk pembayaran SPP, kos, dan tagihan rutin
                            lainnya.</p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Pengingat H-7, H-3, H-1</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Status "Telat Bayar" otomatis</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Custom siklus pembayaran</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Alert warna berdasarkan urgensi
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Fitur 3: Grafik Bulanan -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Grafik Pemasukan & Pengeluaran</h4>
                        <p>Visualisasi data keuangan bulanan dengan Chart.js untuk analisis trend
                            pengeluaran.</p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Line chart perkembangan bulanan
                            </li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Perbandingan pemasukan/pengeluaran
                            </li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Filter berdasarkan periode</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Export data ke CSV</li>
                        </ul>
                    </div>
                </div>

                <!-- Fitur 4: Analitik Kategori Boros -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-search-dollar text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Analitik Kategori Boros</h4>
                        <p>Identifikasi kategori pengeluaran terbesar dan dapatkan saran penghematan
                            otomatis.</p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Deteksi kategori terboros</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Saran hemat berdasarkan pola</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Rasio pengeluaran vs pemasukan</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Peringatan pengeluaran berlebihan
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Fitur 5: Parental Monitoring -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Monitoring Orang Tua</h4>
                        <p>Dashboard khusus orang tua untuk memantau pengeluaran anak selama kuliah.
                        </p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Pantau saldo anak real-time</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Lihat transaksi terbaru</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Ringkasan keuangan per anak</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Sistem kode keluarga</li>
                        </ul>
                    </div>
                </div>

                <!-- Fitur 6: Keamanan Data -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Keamanan Data</h4>
                        <p>Proteksi data keuangan dengan sistem keamanan berlapis.</p>
                        <ul class="small mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Password hashing bcrypt</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Prepared statements PDO</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Session management aman</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Proteksi SQL injection</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-5 border-top">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-4">Tentang <span class="text-primary">MyMoney</span></h2>
                    <p class="lead mb-4">MyMoney adalah <strong>web aplikasi keuangan khusus
                            mahasiswa</strong> yang dikembangkan sebagai proyek akhir pengembangan web. Fokus utama
                        aplikasi ini adalah membantu mahasiswa mengelola keuangan kuliah dengan fitur-fitur modern.</p>

                    <div class="row mt-4">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-graduation-cap text-white"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Untuk Mahasiswa</h5>
                                    <small>Kelola uang saku, bayar SPP/kos, tracking
                                        pengeluaran</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Untuk Orang Tua</h5>
                                    <small>Pantau keuangan anak selama kuliah</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Teknologi yang Digunakan:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-dark p-2">PHP Native</span>
                            <span class="badge bg-primary p-2">MVC Pattern</span>
                            <span class="badge bg-success p-2">MySQL</span>
                            <span class="badge bg-warning p-2">Exchange Rate API</span>
                            <span class="badge bg-info p-2">Chart.js</span>
                            <span class="badge bg-danger p-2">Bootstrap 5</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card glass-effect border-0 rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Tujuan Proyek</h5>

                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-bullseye text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Mempermudah Pengelolaan Keuangan</h6>
                                    <small>Membantu mahasiswa mengatur uang saku dan tagihan
                                        kuliah</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Memberikan Insight Keuangan</h6>
                                    <small>Analisis pengeluaran dan saran penghematan
                                        otomatis</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Mencegah Telat Bayar</h6>
                                    <small>Notifikasi otomatis untuk pembayaran SPP dan kos</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Untuk Demonstrasi:</h6>
                            <div class="alert alert-primary border-0">
                                <small><i class="fas fa-info-circle me-2"></i>Gunakan akun demo:
                                    <strong>Username:</strong> anak_budi | <strong>Password:</strong> password</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="p-5 rounded-4 glass-effect">
                        <h2 class="display-6 fw-bold mb-4">Siap Mengelola Keuangan Kuliah dengan Lebih Baik?</h2>
                        <p class="lead mb-4">Bergabunglah dengan ratusan mahasiswa yang sudah menggunakan MyMoney untuk
                            mengatur keuangan mereka.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <a href="<?= BASEURL; ?>/auth/register"
                                class="btn btn-primary btn-lg text-white fw-bold px-5">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang Gratis
                            </a>
                            <a href="<?= BASEURL; ?>/auth" class="btn btn-outline-light btn-lg px-5">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun
                            </a>
                        </div>
                        <small class="mt-3 d-block">
                            <i class="fas fa-lock me-1"></i> Data aman |
                            <i class="fas fa-bolt me-1 ms-2"></i> Real-time |
                            <i class="fas fa-smile me-1 ms-2"></i> User-friendly
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 border-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a class="navbar-brand fw-bold fs-3" href="#home">
                        <i class="fas fa-wallet me-2"></i>MyMoney<span class="text-primary">.</span>
                    </a>
                    <p class="mt-2">Web aplikasi keuangan mahasiswa dengan kurs otomatis dan notifikasi
                        tagihan.</p>
                    <small>Proyek Akhir Pengembangan Web - 2023</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-3 mb-2">
                        <a href="#" class="text-dark"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-dark"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-dark"><i class="fas fa-envelope"></i></a>
                    </div>
                    <small class="d-block">Dibuat dengan ❤️ untuk mahasiswa Indonesia</small>
                    <small>© 2023 MyMoney - Semua hak dilindungi</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Smooth scroll untuk semua anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });

                // Update active nav link
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            }
        });
    });

    // Update active nav link on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section[id]');
        const scrollPos = window.scrollY + 100;

        sections.forEach(section => {
            if (scrollPos > section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
                const id = section.getAttribute('id');
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });

    // Floating animation untuk stats
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        stat.style.animationDelay = `${Math.random() * 2}s`;
    });

    // Add hover effect to feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    </script>
</body>

</html>