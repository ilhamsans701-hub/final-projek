<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul'] ?? 'Dashboard - MyMoney'; ?></title>

    <!-- Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
    :root {
        --primary: #6366f1;
        --primary-light: #818cf8;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --success-light: #34d399;
        --danger: #ef4444;
        --danger-light: #f87171;
        --warning: #f59e0b;
        --warning-light: #fbbf24;

        /* Tema terang */
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-tertiary: #94a3b8;
        --border-light: #e2e8f0;
        --border-medium: #cbd5e1;
        --border-dark: #94a3b8;

        --gradient-primary: linear-gradient(135deg, #6366f1, #8b5cf6);
        --gradient-success: linear-gradient(135deg, #10b981, #34d399);
        --gradient-warning: linear-gradient(135deg, #f59e0b, #fbbf24);
        --gradient-danger: linear-gradient(135deg, #ef4444, #f87171);

        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --shadow-primary: 0 10px 30px rgba(99, 102, 241, 0.15);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Sidebar Navigation - Mobile Optimized */
    .sidebar {
        background: var(--bg-primary);
        backdrop-filter: blur(10px);
        border-right: 1px solid var(--border-light);
        min-height: 100vh;
        position: fixed;
        width: 260px;
        z-index: 1000;
        padding-top: 20px;
        box-shadow: var(--shadow-md);
        transition: transform 0.3s ease;
    }

    .main-content {
        margin-left: 260px;
        padding: 20px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    /* Mobile Sidebar Toggle */
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1001;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
            width: 280px;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding: 20px 15px;
        }

        .sidebar-toggle {
            display: flex;
        }

        .sidebar-logo {
            padding: 1rem;
        }

        .sidebar-text {
            display: inline !important;
        }

        /* Adjust top bar for mobile */
        .top-bar {
            margin-top: 20px;
        }

        /* Make stats cards stack on mobile */
        .stats-row>div {
            margin-bottom: 1rem;
        }

        /* Adjust table for mobile */
        .table-responsive {
            font-size: 0.875rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 576px) {
        .main-content {
            padding: 15px 10px;
        }

        .top-bar {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
            padding: 1rem;
        }

        .stats-card {
            padding: 1rem;
        }

        .chart-container {
            padding: 1rem;
        }

        h4.fw-bold {
            font-size: 1.25rem;
        }

        /* Better touch targets for mobile */
        .nav-link {
            padding: 1rem;
            min-height: 56px;
        }

        .btn,
        .dropdown-toggle {
            min-height: 44px;
            display: flex;
            align-items: center;
        }
    }

    .sidebar-logo {
        padding: 1.5rem 1rem;
        text-align: center;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 1rem;
    }

    .nav-item {
        margin-bottom: 0.5rem;
    }

    .nav-link {
        color: var(--text-secondary);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--primary);
        background: rgba(99, 102, 241, 0.1);
        border-left: 3px solid var(--primary);
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    /* Stats Cards */
    .stats-card {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s;
        height: 100%;
        box-shadow: var(--shadow-sm);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-light);
        box-shadow: var(--shadow-lg);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stats-income .stats-icon {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .stats-expense .stats-icon {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .stats-balance .stats-icon {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
    }

    /* Chart Container */
    .chart-container {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    /* AI Advice Panel */
    .advice-panel {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.03));
        border: 1px solid var(--primary-light);
        border-radius: 16px;
        padding: 1.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.1);
        }

        50% {
            box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
        }
    }

    /* Table Styling */
    .table-container {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .table {
        color: var(--text-primary);
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--bg-tertiary);
        border-bottom: 2px solid var(--border-light);
        font-weight: 600;
        padding: 1rem;
        color: var(--text-secondary);
    }

    .table tbody td {
        padding: 1rem;
        border-color: var(--border-light);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    /* Badges */
    .badge-income {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-expense {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Floating Action Button */
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: var(--shadow-primary);
        z-index: 1000;
        transition: all 0.3s;
    }

    @media (max-width: 576px) {
        .fab {
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            font-size: 1.3rem;
        }
    }

    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
    }

    /* Top Bar */
    .top-bar {
        background: var(--bg-primary);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-light);
        padding: 1rem 1.5rem;
        position: sticky;
        top: 0;
        z-index: 999;
        box-shadow: var(--shadow-sm);
    }

    .user-dropdown img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid var(--primary);
    }

    /* Avatar size fix */
    .avatar-sm {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50%;
        border: 2px solid var(--primary);
    }

    /* User dropdown button styling */
    .btn-outline-light.btn-sm {
        border-color: var(--border-light) !important;
        background: var(--bg-primary);
        font-size: 0.875rem;
        color: var(--text-primary) !important;
    }

    .btn-outline-light.btn-sm:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary) !important;
        color: var(--primary) !important;
    }

    /* User name text */
    .user-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    /* Dropdown menu styling */
    .dropdown-menu {
        background: var(--bg-primary);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
    }

    .dropdown-item {
        color: var(--text-primary);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        margin: 0.1rem 0.3rem;
        font-size: 0.875rem;
    }

    .dropdown-item:hover {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
    }

    .dropdown-divider {
        border-color: var(--border-light);
        margin: 0.5rem 0;
    }

    /* Scrollbar styling for light theme */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--bg-tertiary);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--border-dark);
    }

    /* Mobile overlay when sidebar is open */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    @media (max-width: 992px) {
        .sidebar-overlay.show {
            display: block;
        }
    }

    /* Prevent text size adjustment on mobile */
    input,
    select,
    textarea,
    button {
        font-size: 16px !important;
    }

    /* Better focus states for accessibility */
    .nav-link:focus,
    .btn:focus,
    .dropdown-toggle:focus {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    /* Loading skeleton for better mobile experience */
    .skeleton {
        background: linear-gradient(90deg,
                var(--bg-tertiary) 25%,
                var(--bg-secondary) 50%,
                var(--bg-tertiary) 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* CSS baru untuk mobile dropdown profile */
    @media (max-width: 992px) {

        /* Sembunyikan dropdown profile dari top-bar di mobile */
        .top-bar .dropdown {
            display: none;
        }

        /* Tambah dropdown profile di sidebar */
        .sidebar-profile {
            padding: 1.5rem 1rem;
            border-top: 1px solid var(--border-light);
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-profile-info {
            flex: 1;
        }

        .sidebar-profile .dropdown-toggle {
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            color: var(--text-primary);
        }

        .sidebar-profile .dropdown-toggle::after {
            margin-left: auto;
        }

        .sidebar-profile .dropdown-menu {
            position: absolute !important;
            transform: translateX(-100px) !important;
            margin-top: 0.5rem;
        }
    }

    /* Desktop: sembunyikan sidebar profile */
    @media (min-width: 993px) {
        .sidebar-profile {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .top-bar {
            padding-left: 60px;
            /* Beri ruang untuk hamburger button */
        }

        .top-bar>div:first-child {
            margin-left: 0;
        }

        .sidebar-toggle {
            top: 20px;
            left: 20px;
            z-index: 1051;
        }

        /* Pastikan judul tidak keluar dari layar */
        .top-bar h4 {
            font-size: 1.25rem;
            max-width: calc(100% - 60px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }

    /* Profile section di sidebar */
    .sidebar-profile {
        padding: 1.5rem 1rem;
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
        margin: 1rem 0;
        display: none;
        /* Default sembunyi */
    }

    .sidebar-profile-info {
        flex: 1;
        text-align: left;
    }

    .sidebar-profile .dropdown-toggle {
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        color: var(--text-primary);
        transition: all 0.3s;
    }

    .sidebar-profile .dropdown-toggle:hover {
        background: rgba(99, 102, 241, 0.1);
    }

    .sidebar-profile .dropdown-toggle::after {
        margin-left: auto;
        border: none;
        content: "\f078";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }

    .sidebar-profile .dropdown-menu {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        margin-top: 0.5rem;
        min-width: 200px;
    }

    /* Tampilkan di mobile, sembunyikan di desktop */
    @media (max-width: 992px) {
        .sidebar-profile {
            display: block;
        }

        /* Sembunyikan dropdown profile dari top-bar di mobile */
        .top-bar .dropdown {
            display: none;
        }
    }

    @media (min-width: 993px) {
        .sidebar-profile {
            display: none;
        }
    }

    /* Perbaikan untuk top-bar di mobile */
    @media (max-width: 992px) {
        .top-bar {
            padding-left: 60px !important;
        }

        .top-bar>div:first-child {
            margin-left: 0;
        }

        .sidebar-toggle {
            top: 20px;
            left: 20px;
            z-index: 1051;
        }

        .top-bar h4 {
            font-size: 1.25rem;
            max-width: calc(100% - 70px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .top-bar small {
            font-size: 0.85rem;
            display: block;
            max-width: calc(100% - 70px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }

    /* Solusi sederhana - dropdown selalu di tengah layar */
    @media (max-width: 992px) {
        .sidebar-profile .dropdown-menu {
            position: fixed !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            width: 280px;
            max-width: calc(100vw - 40px);
            box-shadow: var(--shadow-xl);
            animation: dropdownSlideIn 0.3s ease;
        }

        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -40%) !important;
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) !important;
            }
        }
    }

    /* Atau jika mau dropdown muncul dari kanan sidebar */
    @media (max-width: 992px) {
        .sidebar-profile {
            position: relative;
        }

        .sidebar-profile .dropdown-menu {
            position: absolute !important;
            right: 0 !important;
            /* Muncul dari kanan */
            left: auto !important;
            top: 100% !important;
            margin-top: 5px;
            min-width: 220px;
            transform: none !important;
        }
    }
    </style>
</head>

<body>

    <!-- Mobile Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <a href="<?= BASEURL; ?>/student" class="navbar-brand fw-bold" style="color: var(--text-primary);">
                <i class="fas fa-wallet me-2"></i><span class="sidebar-text">MyMoney</span>
            </a>
        </div>

        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link active" href="<?= BASEURL; ?>/student">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASEURL; ?>/student/create">
                    <i class="fas fa-plus-circle"></i>
                    <span class="sidebar-text">Tambah Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASEURL; ?>/subscription">
                    <i class="fas fa-bell"></i>
                    <span class="sidebar-text">Tagihan & Notifikasi</span>
                    <span class="badge bg-danger ms-auto sidebar-text">3</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASEURL; ?>/report">
                    <i class="fas fa-chart-bar"></i>
                    <span class="sidebar-text">Laporan & Grafik</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-text">Pengaturan</span>
                </a>
            </li>
        </ul>

        <div class="mt-auto">
            <!-- Profile Section untuk Mobile -->
            <div class="sidebar-profile">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($data['user'] ?? 'User'); ?>&background=6366f1&color=fff"
                            alt="Avatar" class="avatar-sm">
                        <div class="sidebar-profile-info">
                            <div class="user-name"><?= $data['user'] ?? 'User'; ?></div>
                            <small class="text-muted">Mahasiswa</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar
                            </a></li>
                    </ul>
                </div>
            </div>

            <!-- Hapus Logout dari menu utama -->
            <!-- Footer -->
            <div class="p-3">
                <div class="text-center">
                    <small class="text-muted sidebar-text">v1.0 • Proyek Akhir</small>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0"><?= $data['judul'] ?? 'Dashboard'; ?></h4>
                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i> <?= date('d F Y'); ?>
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2 p-2"
                        type="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($data['user'] ?? 'User'); ?>&background=6366f1&color=fff"
                            alt="Avatar" class="avatar-sm">
                        <span class="d-none d-md-inline user-name"><?= $data['user'] ?? 'User'; ?></span>
                        <i class="fas fa-chevron-down ms-1 small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid">