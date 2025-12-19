<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Edit Profil</h5>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <form action="<?= BASEURL; ?>/profile/update" method="POST" enctype="multipart/form-data" id="profileForm"
            novalidate>
            <input type="hidden" name="id" value="<?= $data['profile_user']['id'] ?? ''; ?>">
            <input type="hidden" name="fotoLama" value="<?= $data['profile_user']['photo'] ?? 'default.png'; ?>">

            <div class="row">
                <!-- Foto Profil -->
                <div class="col-md-4 text-center mb-4">
                    <div class="mb-3">
                        <?php
                            $username = $data['profile_user']['username'] ?? 'User';
                            $photoName = $data['profile_user']['photo'] ?? '';
                            $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&background=6366f1&color=fff&size=150';
                            if (!empty($photoName)) {
                                $photoUrl = BASEURL . '/img/profile/' . $photoName;
                            } else {
                                $photoUrl = $defaultAvatar;
                            }
                        ?>

                        <img src="<?= $photoUrl; ?>" class="img-fluid rounded-circle border shadow-sm"
                            style="width: 150px; height: 150px; object-fit: cover;" id="profilePreview" /* Fallback:
                            Jika gambar error/404, ganti source ke avatar inisial */
                            onerror="this.onerror=null; this.src='<?= $defaultAvatar; ?>';">
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-camera me-1"></i> Ganti Foto
                            <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                        </label>
                        <small class="text-muted d-block mt-1">Maks. 2MB (JPG, PNG)</small>
                    </div>
                </div>

                <!-- Form Data -->
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-user me-1"></i> Username *
                            </label>
                            <input type="text" class="form-control" name="username"
                                value="<?= htmlspecialchars($data['profile_user']['username'] ?? ''); ?>" required
                                minlength="3" maxlength="50">
                            <div class="invalid-feedback">
                                Username harus diisi (3-50 karakter).
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-envelope me-1"></i> Email *
                            </label>
                            <input type="email" class="form-control" name="email"
                                value="<?= htmlspecialchars($data['profile_user']['email'] ?? ''); ?>" required>
                            <div class="invalid-feedback">
                                Email harus valid.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-user-tag me-1"></i> Role
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <?php 
                                        $role = $data['profile_user']['role'] ?? 'user';
                                        $icon = 'user';
                                        if($role == 'admin') $icon = 'crown';
                                        elseif($role == 'orangtua') $icon = 'user-tie';
                                        elseif($role == 'mahasiswa') $icon = 'user-graduate';
                                    ?>
                                    <i class="fas fa-<?= $icon; ?>"></i>
                                </span>
                                <input type="text" class="form-control" value="<?= ucfirst($role); ?>" readonly
                                    style="background-color: var(--bg-tertiary); cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-users me-1"></i> Kode Keluarga
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" class="form-control"
                                    value="<?= $data['profile_user']['family_code'] ?? 'Tidak ada'; ?>" readonly
                                    style="background-color: var(--bg-tertiary); cursor: not-allowed;">
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="changePassword">
                                <label class="form-check-label fw-medium" for="changePassword">
                                    <i class="fas fa-key me-1"></i> Ganti Password
                                </label>
                            </div>

                            <div id="passwordFields" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">
                                            <i class="fas fa-lock me-1"></i> Password Baru
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password"
                                                id="newPassword" minlength="6" maxlength="50">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('newPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Password minimal 6 karakter.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">
                                            <i class="fas fa-lock me-1"></i> Konfirmasi Password
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="confirm_password"
                                                id="confirmPassword" minlength="6" maxlength="50">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('confirmPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Password tidak cocok.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-danger" onclick="showDeleteModal()">
                                    <i class="fas fa-trash me-1"></i> Hapus Akun
                                </button>
                                <button type="submit" class="btn btn-primary px-4" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Hapus Akun Permanent?</h5>
                    <p class="text-muted mb-0">
                        Semua data transaksi dan informasi akun<br>
                        akan dihapus secara permanen.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Inline validation styles */
.is-valid {
    border-color: var(--success) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2310b981' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid {
    border-color: var(--danger) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: none;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid~.invalid-feedback {
    display: block;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Profile specific styles */
#profilePreview {
    border: 3px solid var(--primary);
    transition: transform 0.3s ease;
    cursor: pointer;
}

#profilePreview:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

/* Password field styling */
#passwordFields {
    transition: all 0.3s ease;
    background: rgba(99, 102, 241, 0.05);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid var(--border-light);
}

/* Delete Modal Styling */
#deleteModal .modal-content {
    border-radius: 16px;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
}

#deleteModal .modal-header {
    background: rgba(239, 68, 68, 0.05);
    border-bottom: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 16px 16px 0 0;
}

#deleteModal .modal-footer {
    background: var(--bg-tertiary);
    border-radius: 0 0 16px 16px;
}

/* Untuk input readonly */
input[readonly] {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary);
    cursor: not-allowed;
}

.input-group-text.bg-light {
    background-color: var(--bg-tertiary) !important;
    border-color: var(--border-light);
}

@media (max-width: 768px) {
    #profilePreview {
        width: 120px !important;
        height: 120px !important;
    }

    .table-container {
        padding: 1rem !important;
    }
}

@media (max-width: 576px) {
    #profilePreview {
        width: 100px !important;
        height: 100px !important;
    }

    .btn {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
</style>

<script>
// Profile Form Validation & Interaction
document.addEventListener('DOMContentLoaded', function() {
    // Preview foto profil
    const photoInput = document.getElementById('photo');
    const profilePreview = document.getElementById('profilePreview');

    if (photoInput && profilePreview) {
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showCustomAlert('Ukuran file maksimal 2MB', 'warning');
                    this.value = '';
                    return;
                }

                // Check file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    showCustomAlert('Format file harus JPG, JPEG, atau PNG', 'warning');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle password fields
    const changePasswordCheckbox = document.getElementById('changePassword');
    const passwordFields = document.getElementById('passwordFields');

    if (changePasswordCheckbox && passwordFields) {
        changePasswordCheckbox.addEventListener('change', function() {
            passwordFields.style.display = this.checked ? 'block' : 'none';

            // Toggle required attribute
            const passwordInputs = passwordFields.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(input => {
                input.required = this.checked;
            });
        });
    }

    // Real-time validation
    const form = document.getElementById('profileForm');
    if (form) {
        // Validate on input
        form.querySelectorAll('input').forEach(field => {
            field.addEventListener('input', function() {
                validateField(this);
            });

            field.addEventListener('blur', function() {
                validateField(this);
            });
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showCustomAlert('Harap periksa kembali form Anda', 'warning');

                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                // Show loading
                const saveBtn = document.getElementById('saveBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
                saveBtn.disabled = true;
            }
        });
    }
});

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const minLength = field.getAttribute('minlength');
    const maxLength = field.getAttribute('maxlength');
    const type = field.type;

    // Reset validation classes
    field.classList.remove('is-valid', 'is-invalid');

    // Check if field is required and empty
    if (isRequired && !value) {
        field.classList.add('is-invalid');
        return false;
    }

    if (value) {
        // Check minlength
        if (minLength && value.length < minLength) {
            field.classList.add('is-invalid');
            return false;
        }

        // Check maxlength
        if (maxLength && value.length > maxLength) {
            field.classList.add('is-invalid');
            return false;
        }

        // Check email format
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
    }

    // If all checks pass
    if (value) {
        field.classList.add('is-valid');
    }

    return true;
}

// Validate entire form
function validateForm() {
    const form = document.getElementById('profileForm');
    let isValid = true;

    // Validate required fields
    form.querySelectorAll('[required]').forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    // Validate password match if changing password
    const changePasswordCheckbox = document.getElementById('changePassword');
    if (changePasswordCheckbox && changePasswordCheckbox.checked) {
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            confirmPassword.nextElementSibling.innerHTML = 'Password tidak cocok';
            isValid = false;
        }

        if (newPassword.value && newPassword.value.length < 6) {
            newPassword.classList.add('is-invalid');
            isValid = false;
        }
    }

    return isValid;
}

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Show delete modal
function showDeleteModal() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();

    // Handle confirm button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = '<?= BASEURL; ?>/profile/delete';
    };
}

// Custom alert function (konsisten dengan halaman lain)
function showCustomAlert(message, type = 'info') {
    // Remove existing alerts
    document.querySelectorAll('.custom-alert').forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        box-shadow: var(--shadow-lg);
        animation: slideInRight 0.3s ease;
    `;

    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getIconByType(type)} me-3"></i>
            <div class="flex-grow-1">
                <strong class="me-auto">${getTitleByType(type)}</strong>
                <div class="small mt-1">${message}</div>
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        </div>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}

function getIconByType(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function getTitleByType(type) {
    const titles = {
        'success': 'Berhasil!',
        'danger': 'Error!',
        'warning': 'Peringatan!',
        'info': 'Info'
    };
    return titles[type] || 'Info';
}

// Add animation for custom alert
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
</script>