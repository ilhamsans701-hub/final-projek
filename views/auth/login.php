<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <!-- Back to Home Button (Mobile Optimized) -->
            <div class="d-md-none mb-3">
                <a href="<?= BASEURL; ?>/home" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <div class="auth-card animate__animated animate__fadeIn">
                <div class="auth-header">
                    <h2 class="fw-bold mb-2 fs-4 fs-md-3">Masuk ke MyMoney</h2>
                    <p class="opacity-75 mb-0 fs-6 fs-md-base">Kelola keuangan kuliah dengan lebih mudah</p>
                </div>

                <div class="p-3 p-md-4 p-lg-5">
                    <!-- Flash Messages -->
                    <div class="mb-3 mb-md-4 flash-message">
                        <?php Flasher::flash(); ?>
                    </div>

                    <form action="<?= BASEURL; ?>/auth/processLogin" method="POST" id="loginForm">
                        <div class="mb-3 mb-md-4">
                            <label for="username" class="form-label fw-medium">
                                <i class="fas fa-user me-2"></i>Username
                            </label>
                            <input type="text" class="form-control form-control-lg py-2 py-md-2" id="username"
                                name="username" placeholder="Masukkan username" required autofocus
                                aria-label="Username">
                        </div>

                        <div class="mb-3 mb-md-4">
                            <label for="password" class="form-label fw-medium">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control form-control-lg py-2 py-md-2" id="password"
                                name="password" placeholder="Masukkan password" required aria-label="Password">
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-3 mb-md-4">
                        </div>

                        <div class="d-grid mb-3 mb-md-4">
                            <button type="submit" class="btn-auth py-2 py-md-2">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <span class="fw-semibold">Masuk ke Dashboard</span>
                            </button>
                        </div>
                    </form>

                    <!-- Divider with responsive sizing -->
                    <div class="position-relative my-3 my-md-4">
                        <hr class="opacity-25">
                        <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                            <small class="text-muted">Atau</small>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="mb-2 fs-6">Belum punya akun?</p>
                        <a href="<?= BASEURL; ?>/auth/register"
                            class="btn btn-outline-primary rounded-pill px-4 py-2 w-100 w-md-auto">
                            <i class="fas fa-user-plus me-2"></i>Daftar Akun Baru
                        </a>
                    </div>
                </div>

                <!-- Demo Info with responsive design -->
                <div class="auth-footer">
                    <!-- Back to Home (Desktop) -->
                    <div class="d-none d-md-block mt-3">
                        <a href="<?= BASEURL; ?>/home" class="text-decoration-none fs-6">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile Optimized Styles for Login */
.form-control-lg {
    font-size: 1rem !important;
}

.demo-info {
    background: rgba(99, 102, 241, 0.05);
    border: 1px solid rgba(99, 102, 241, 0.1);
    border-radius: 12px;
}

.demo-credential {
    background: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.demo-credential code {
    font-size: 0.85rem;
    background: rgba(0, 0, 0, 0.05);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Better touch targets for mobile */
.btn-auth {
    min-height: 48px;
}

.form-check-input {
    width: 1.2em;
    height: 1.2em;
}

/* Mobile specific adjustments */
@media (max-width: 576px) {
    .auth-header {
        padding: 1.5rem 1rem !important;
    }

    .auth-card {
        border-radius: 20px !important;
    }

    .btn {
        padding: 0.5rem 1.25rem !important;
    }
}

/* Tablet adjustments */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .auth-card {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }
}

/* Better focus states for mobile */
.form-control:focus,
.btn:focus {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}
</style>