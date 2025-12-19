<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Back to Home Button (Mobile Optimized) -->
            <div class="d-md-none mb-3">
                <a href="<?= BASEURL; ?>/home" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <div class="auth-card animate__animated animate__fadeIn">
                <div class="auth-header">
                    <h2 class="fw-bold mb-2 fs-4 fs-md-3">Daftar Akun MyMoney</h2>
                    <p class="opacity-75 mb-0 fs-6 fs-md-base">Bergabung dengan komunitas mahasiswa pengguna MyMoney</p>
                </div>

                <div class="p-3 p-md-4 p-lg-5">
                    <!-- Flash Messages -->
                    <div class="mb-3 mb-md-4 flash-message">
                        <?php Flasher::flash(); ?>
                    </div>

                    <form action="<?= BASEURL; ?>/auth/processRegister" method="POST" id="registerForm">
                        <!-- Username & Email (Stack on mobile) -->
                        <div class="row g-3 mb-3 mb-md-4">
                            <div class="col-12 col-md-6">
                                <label for="username" class="form-label fw-medium">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                                <input type="text" class="form-control form-control-lg py-2 py-md-2" id="username"
                                    name="username" placeholder="username_unik" required aria-label="Username">
                                <div class="form-text mt-1">
                                    <small>Minimal 3 karakter, tanpa spasi</small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-medium">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control form-control-lg py-2 py-md-2" id="email"
                                    name="email" placeholder="nama@email.com" required aria-label="Email">
                            </div>
                        </div>

                        <!-- Password Fields (Stack on mobile) -->
                        <div class="row g-3 mb-3 mb-md-4">
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label fw-medium">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg py-2 py-md-2" id="password"
                                    name="password" placeholder="Minimal 6 karakter" required aria-label="Password">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="confirm_password" class="form-label fw-medium">
                                    <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                </label>
                                <input type="password" class="form-control form-control-lg py-2 py-md-2"
                                    id="confirm_password" placeholder="Ulangi password" required
                                    aria-label="Confirm password">
                            </div>
                        </div>

                        <!-- Role Selection (Stack on mobile) -->
                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-medium d-block mb-3">
                                <i class="fas fa-user-tag me-2"></i>Daftar Sebagai:
                            </label>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <div class="radio-card active mobile-card">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="role" id="role_ortu"
                                                value="orangtua" checked>
                                            <label class="form-check-label fw-bold" for="role_ortu">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie fs-4 me-3 text-primary"></i>
                                                    <div>
                                                        <div class="fs-6">Orang Tua</div>
                                                        <small class="text-muted">Monitor keuangan anak</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="radio-card mobile-card">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="role" id="role_anak"
                                                value="anak">
                                            <label class="form-check-label fw-bold" for="role_anak">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-graduation-cap fs-4 me-3 text-success"></i>
                                                    <div>
                                                        <div class="fs-6">Mahasiswa</div>
                                                        <small class="text-muted">Kelola keuangan kuliah</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Code Input -->
                        <div class="mb-3 mb-md-4 d-none animate__animated" id="family_code_input">
                            <label for="input_family_code" class="form-label fw-medium">
                                <i class="fas fa-users me-2"></i>Kode Keluarga
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control form-control-lg py-2 py-md-2"
                                    id="input_family_code" name="input_family_code" placeholder="FAM-XXXXX"
                                    aria-label="Family code">
                                <button type="button" class="btn btn-outline-info px-3" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Dapatkan kode dari orang tua Anda">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                            <div class="form-text mt-1">
                                <small>Masukkan kode keluarga dari orang tua Anda</small>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-3 mb-md-4">
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3 mb-md-4">
                            <button type="submit" class="btn-auth py-2 py-md-2">
                                <i class="fas fa-user-plus me-2"></i>
                                <span class="fw-semibold">Buat Akun Sekarang</span>
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="position-relative my-3 my-md-4">
                            <hr class="opacity-25">
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                            </div>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <a href="<?= BASEURL; ?>/auth"
                                class="btn btn-outline-primary rounded-pill px-4 py-2 w-100 w-md-auto">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* Mobile Optimized Styles for Register */
.mobile-card {
    min-height: 70px;
    display: flex;
    align-items: center;
}

.security-info {
    background: rgba(16, 185, 129, 0.05);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 12px;
}

/* Touch-friendly form controls */
.form-check-input {
    width: 1.3em;
    height: 1.3em;
    margin-top: 0.15em;
}

/* Better spacing for mobile */
@media (max-width: 768px) {
    .auth-header {
        padding: 1.5rem 1rem !important;
    }

    .p-3.p-md-4.p-lg-5 {
        padding: 1.5rem !important;
    }

    .row.g-3 {
        margin-bottom: 1rem !important;
    }

    .radio-card {
        padding: 0.75rem !important;
    }

    .radio-card i.fs-4 {
        font-size: 1.5rem !important;
    }
}

/* Extra small devices */
@media (max-width: 576px) {
    .auth-card {
        margin: 0.5rem !important;
        border-radius: 16px !important;
    }

    .btn {
        padding: 0.625rem 1.5rem !important;
    }
}

/* Fix for iOS Safari */
input[type="text"],
input[type="email"],
input[type="password"] {
    -webkit-appearance: none;
    border-radius: 12px !important;
}

/* Prevent zoom on focus for mobile */
@media (max-width: 768px) {

    input,
    select,
    textarea {
        font-size: 16px !important;
    }
}

/* Better button feedback */
.btn:active {
    transform: scale(0.98);
    transition: transform 0.1s;
}

/* Responsive font sizes */
.fs-6 {
    font-size: 0.875rem !important;
}

.fs-md-base {
    font-size: 1rem !important;
}
</style>

<script>
// Mobile optimized form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');

    // Initialize role selection and family code
    initializeRoleSelection();

    // Real-time password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    function validatePasswords() {
        if (password.value && confirmPassword.value) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Password tidak cocok');
                return false;
            } else {
                confirmPassword.setCustomValidity('');
                return true;
            }
        }
        return true;
    }

    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);

    // Form submission with mobile-friendly validation
    form.addEventListener('submit', function(e) {
        if (!validatePasswords()) {
            e.preventDefault();
            showMobileAlert('Password dan konfirmasi password tidak cocok!');
            return false;
        }

        if (password.value.length < 6) {
            e.preventDefault();
            showMobileAlert('Password minimal 6 karakter!');
            return false;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftarkan...';
            submitBtn.disabled = true;
        }

        return true;
    });

    // Initialize tooltips with mobile consideration
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            boundary: 'viewport',
            trigger: 'hover focus'
        });
    });

    // Better mobile alerts
    function showMobileAlert(message) {
        // Check if we're on mobile
        if (window.innerWidth <= 768) {
            // Use Bootstrap toast for mobile
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 start-0 w-100 bg-danger text-white p-3 text-center';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        } else {
            alert(message);
        }
    }

    // Initialize role selection
    function initializeRoleSelection() {
        // Set initial state based on default selection
        toggleFamilyCodeInput();

        // Add event listeners to radio inputs
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                toggleFamilyCodeInput();
            });
        });
    }
});

// Toggle family code input with smooth animation
function toggleFamilyCodeInput() {
    const roleAnak = document.getElementById('role_anak');
    const codeInput = document.getElementById('family_code_input');
    const inputField = document.getElementById('input_family_code');

    if (!roleAnak || !codeInput) return;

    // Check current state
    const isAnakSelected = roleAnak.checked;

    if (isAnakSelected) {
        // Show with animation
        codeInput.classList.remove('d-none');

        // Force reflow
        void codeInput.offsetWidth;

        // Add animation classes
        codeInput.classList.add('animate__animated', 'animate__fadeIn');

        // Remove animation class after animation completes
        setTimeout(() => {
            codeInput.classList.remove('animate__animated', 'animate__fadeIn');
        }, 300);

        if (inputField) {
            inputField.required = true;
            setTimeout(() => {
                inputField.focus();
            }, 350);
        }
    } else {
        // Hide with animation
        codeInput.classList.add('animate__animated', 'animate__fadeOut');

        setTimeout(() => {
            codeInput.classList.remove('animate__animated', 'animate__fadeOut');
            codeInput.classList.add('d-none');
        }, 300);

        if (inputField) {
            inputField.required = false;
            inputField.value = '';
        }
    }
}
</script>