<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
        color: white;
        padding: 100px 0;
    }

    .feature-icon {
        font-size: 3rem;
        color: #0d6efd;
        margin-bottom: 1rem;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASEURL; ?>"><i class="fas fa-wallet me-2"></i>MyMoney</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASEURL; ?>/auth">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-warning text-dark rounded-pill px-4"
                            href="<?= BASEURL; ?>/auth/register">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>