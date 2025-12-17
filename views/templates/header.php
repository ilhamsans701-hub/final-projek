<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul'] ?? 'MyMoney - Keuangan Mahasiswa'; ?></title>

    <!-- Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #0f172a;
        --light: #f8fafc;
        --gradient-primary: linear-gradient(135deg, #6366f1, #8b5cf6);
        --gradient-dark: linear-gradient(135deg, #f8fafc, #e2e8f0);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--gradient-dark);
        min-height: 100vh;
        color: var(--dark);
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
        z-index: -1;
    }

    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .auth-header {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        padding: 2.5rem 2rem;
        text-align: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .auth-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--dark);
        padding: 0.875rem 1rem;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: var(--primary);
        color: var(--dark);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .btn-auth {
        background: var(--gradient-primary);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        width: 100%;
        color: white !important;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.2);
    }

    .auth-footer {
        padding: 1.5rem 2rem;
        text-align: center;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(248, 250, 252, 0.3);
    }

    .floating-element {
        position: absolute;
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

    .radio-card {
        background: rgba(248, 250, 252, 0.6);
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .radio-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
    }

    .radio-card.active {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.1);
    }

    .radio-card .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .flash-message {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body>

    <!-- Floating Background Elements -->
    <div class="floating-element"
        style="top: 10%; left: 5%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%); animation-delay: 0s;">
    </div>
    <div class="floating-element"
        style="bottom: 10%; right: 5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(139, 92, 246, 0.04) 0%, transparent 70%); animation-delay: 1s;">
    </div>

    <div class="auth-container">