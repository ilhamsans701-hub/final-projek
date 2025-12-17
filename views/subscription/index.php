<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Kelola Tagihan Rutin</h5>
            <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Form Tambah -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Tagihan Baru
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASEURL; ?>/subscription/store" method="POST" id="addSubscriptionForm"
                            novalidate>
                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-tag me-1"></i> Nama Layanan *
                                </label>
                                <input type="text" name="service_name" class="form-control"
                                    placeholder="Contoh: Kosan, Netflix, SPP" required minlength="3" maxlength="50">
                                <div class="invalid-feedback">
                                    Nama layanan harus diisi (3-50 karakter).
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-money-bill-wave me-1"></i> Biaya (IDR) *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="amount" class="form-control" placeholder="0" required
                                        pattern="[0-9.,]+" data-type="currency">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatAmount(this)">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Biaya harus diisi dengan angka yang valid.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-sync-alt me-1"></i> Siklus Pembayaran *
                                </label>
                                <select name="billing_cycle" class="form-select" required>
                                    <option value="monthly" selected>Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih siklus pembayaran.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-calendar-day me-1"></i> Jatuh Tempo *
                                </label>
                                <input type="date" name="due_date" class="form-control" required
                                    min="<?= date('Y-m-d'); ?>">
                                <div class="invalid-feedback">
                                    Tanggal jatuh tempo harus diisi dan tidak boleh kurang dari hari ini.
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Sistem akan mengingatkan H-3 sebelum jatuh tempo.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Simpan Tagihan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Tagihan -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-list-alt me-2 text-primary"></i>Daftar Tagihan Aktif
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <?php Flasher::flash(); ?>

                        <?php if(empty($data['subscriptions'])) : ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-smile fa-3x text-warning mb-3"></i>
                                <h5 class="fw-bold">Tidak ada tagihan aktif</h5>
                                <p class="text-muted mb-0">Hidup tenang! 😎</p>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-medium">Layanan</th>
                                        <th class="fw-medium">Biaya</th>
                                        <th class="fw-medium">Jatuh Tempo</th>
                                        <th class="fw-medium">Status</th>
                                        <th class="fw-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['subscriptions'] as $sub) : ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($sub['service_name']); ?></div>
                                            <small class="text-muted">
                                                <i class="fas fa-sync-alt me-1"></i>
                                                <?= $sub['billing_cycle'] == 'monthly' ? 'Bulanan' : 'Tahunan'; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">Rp <?= number_format($sub['amount'], 0, ',', '.'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?= date('d M Y', strtotime($sub['due_date'])); ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php
                                                $today = new DateTime();
                                                $dueDate = new DateTime($sub['due_date']);
                                                $interval = $today->diff($dueDate);
                                                echo $interval->days . ' hari lagi';
                                                ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if($sub['status'] == 'overdue') : ?>
                                            <span class="badge bg-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Telat <?= $sub['days_left']; ?> hari
                                            </span>
                                            <?php elseif($sub['status'] == 'danger') : ?>
                                            <span class="badge bg-danger rounded-pill px-3 py-2 animate-blink">
                                                <i class="fas fa-bell me-1"></i>
                                                H-<?= $sub['days_left']; ?> Bayar!
                                            </span>
                                            <?php elseif($sub['status'] == 'warning') : ?>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-exclamation me-1"></i>
                                                H-<?= $sub['days_left']; ?>
                                            </span>
                                            <?php else : ?>
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check me-1"></i>
                                                Aman (<?= $sub['days_left']; ?> hari)
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASEURL; ?>/subscription/delete/<?= $sub['id']; ?>"
                                                class="btn btn-outline-danger btn-sm px-3"
                                                onclick="return confirmDelete(this, event)">
                                                <i class="fas fa-times me-1"></i> Stop
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
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
                    <h5 class="fw-bold">Hentikan Tagihan?</h5>
                    <p class="text-muted mb-0">
                        Tagihan akan dihentikan secara permanen.<br>
                        Tidak ada pengingat lagi untuk layanan ini.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Ya, Hentikan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes blink {
    50% {
        opacity: 0.5;
    }
}

.animate-blink {
    animation: blink 1s linear infinite;
}

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

.card {
    border-radius: 16px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.badge {
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }

    .table-responsive {
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem !important;
    }
}
</style>

<script>
// Enhanced form UX with inline validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addSubscriptionForm');
    const amountInput = form.querySelector('input[name="amount"]');
    const submitBtn = document.getElementById('submitBtn');

    // Format amount on blur
    amountInput.addEventListener('blur', function() {
        formatAmount(this);
        validateField(this);
    });

    // Real-time validation on input
    form.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', function() {
            validateField(this);
        });

        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            showLoading(submitBtn, 'Menyimpan...');
            this.submit();
        } else {
            showCustomAlert('Harap periksa kembali form Anda', 'warning');
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
    });

    // Auto-format number input
    amountInput.addEventListener('focus', function() {
        const value = this.value.replace(/[.,]/g, '');
        this.value = value;
    });

    // Mobile optimizations
    if (window.innerWidth <= 768) {
        document.querySelectorAll('input, select').forEach(el => {
            el.style.minHeight = '44px';
        });
    }
});

// Format amount function
function formatAmount(inputElement) {
    const input = inputElement.tagName === 'INPUT' ? inputElement : inputElement.previousElementSibling;
    const value = input.value.replace(/[.,]/g, '');

    if (value && !isNaN(value) && value > 0) {
        input.value = parseFloat(value).toLocaleString('id-ID');
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else if (value && (isNaN(value) || value <= 0)) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
    }
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const minLength = field.getAttribute('minlength');
    const maxLength = field.getAttribute('maxlength');
    const pattern = field.getAttribute('pattern');

    // Reset validation classes
    field.classList.remove('is-valid', 'is-invalid');

    // Check if field is required and empty
    if (isRequired && !value) {
        field.classList.add('is-invalid');
        return false;
    }

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

    // Check pattern (for amount)
    if (pattern && value) {
        const regex = new RegExp(pattern);
        if (!regex.test(value.replace(/[.,]/g, ''))) {
            field.classList.add('is-invalid');
            return false;
        }
    }

    // Check date min
    if (field.type === 'date' && value < field.min) {
        field.classList.add('is-invalid');
        return false;
    }

    // If all checks pass
    if (value) {
        field.classList.add('is-valid');
    }

    return true;
}

// Validate entire form
function validateForm() {
    const form = document.getElementById('addSubscriptionForm');
    let isValid = true;

    form.querySelectorAll('[required]').forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// Confirm delete
function confirmDelete(link, event) {
    event.preventDefault();

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();

    // Handle confirm button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        showLoading(this, 'Menghapus...');
        window.location.href = link.href;
    };

    return false;
}

// Show loading state
function showLoading(button, text = 'Memproses...') {
    const originalText = button.innerHTML;
    button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${text}`;
    button.disabled = true;

    return {
        restore: function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    };
}

// Custom alert function
function showCustomAlert(message, type = 'info', duration = 5000) {
    // Remove existing custom alerts
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

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, duration);
    }
}

// Helper functions for custom alert
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
    
    .custom-alert {
        border-radius: 12px;
        border: none;
    }
`;
document.head.appendChild(style);
</script>