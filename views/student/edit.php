<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Edit Transaksi</h5>
            <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Form dengan layout full width -->
        <form action="<?= BASEURL; ?>/student/update" method="POST" id="editTransactionForm" novalidate>
            <input type="hidden" name="id" value="<?= $data['trx']['id']; ?>">
            <input type="hidden" name="exchange_rate_old" value="<?= $data['trx']['exchange_rate']; ?>">

            <div class="row">
                <!-- Kolom kiri (form inputs) -->
                <div class="col-12">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-calendar me-1"></i> Tanggal Transaksi *
                            </label>
                            <input type="date" name="date" class="form-control"
                                value="<?= date('Y-m-d', strtotime($data['trx']['transaction_date'])); ?>" required
                                max="<?= date('Y-m-d'); ?>">
                            <div class="invalid-feedback">
                                Tanggal transaksi tidak boleh kosong dan tidak boleh lebih dari hari ini.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="fas fa-exchange-alt me-1"></i> Jenis Transaksi *
                            </label>
                            <select name="type" class="form-select" id="type_select" required>
                                <option value="expense" <?= ($data['trx']['type'] == 'expense') ? 'selected' : ''; ?>>
                                    Pengeluaran
                                </option>
                                <option value="income" <?= ($data['trx']['type'] == 'income') ? 'selected' : ''; ?>>
                                    Pemasukan
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih jenis transaksi.
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            <i class="fas fa-tags me-1"></i> Kategori *
                        </label>
                        <select name="category_id" class="form-select" id="category_select" required>
                            <option value="" disabled>Pilih kategori</option>
                            <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id']; ?>" data-type="<?= $cat['type']; ?>"
                                <?= ($cat['id'] == $data['trx']['category_id']) ? 'selected' : ''; ?>>
                                <i class="<?= $cat['icon']; ?> me-2"></i>
                                <?= ucfirst($cat['type']); ?> - <?= $cat['name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Pilih kategori transaksi.
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Pilih kategori yang sesuai dengan jenis transaksi
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            <i class="fas fa-align-left me-1"></i> Deskripsi *
                        </label>
                        <input type="text" name="description" class="form-control"
                            value="<?= htmlspecialchars($data['trx']['description']); ?>"
                            placeholder="Deskripsi transaksi..." required minlength="3" maxlength="100">
                        <div class="invalid-feedback">
                            Deskripsi harus diisi (3-100 karakter).
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Jelaskan transaksi secara singkat dan jelas
                        </small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">
                                <i class="fas fa-globe me-1"></i> Mata Uang *
                            </label>
                            <select name="currency" class="form-select" id="currency_select" required>
                                <option value="IDR" <?= ($data['trx']['currency_code'] == 'IDR') ? 'selected' : ''; ?>>
                                    IDR - Rupiah</option>
                                <option value="USD" <?= ($data['trx']['currency_code'] == 'USD') ? 'selected' : ''; ?>>
                                    USD - Dollar AS</option>
                                <option value="EUR" <?= ($data['trx']['currency_code'] == 'EUR') ? 'selected' : ''; ?>>
                                    EUR - Euro</option>
                                <option value="SGD" <?= ($data['trx']['currency_code'] == 'SGD') ? 'selected' : ''; ?>>
                                    SGD - Dollar Singapura</option>
                                <option value="JPY" <?= ($data['trx']['currency_code'] == 'JPY') ? 'selected' : ''; ?>>
                                    JPY - Yen Jepang</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih mata uang.
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-medium">
                                <i class="fas fa-money-bill-wave me-1"></i> Nominal *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" id="currency_label">
                                    <?= $data['trx']['currency_code'] == 'IDR' ? 'Rp' : 
                                       ($data['trx']['currency_code'] == 'USD' ? '$' : 
                                       ($data['trx']['currency_code'] == 'EUR' ? '€' : 
                                       ($data['trx']['currency_code'] == 'SGD' ? 'S$' : '¥'))); ?>
                                </span>
                                <input type="text" name="amount" class="form-control" placeholder="0" required
                                    pattern="[0-9.,]+" data-type="currency"
                                    value="<?= number_format($data['trx']['amount_origin'], 0, ',', '.'); ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="formatAmount(this)">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Nominal harus diisi dengan angka yang valid.
                            </div>

                            <!-- Conversion Info -->
                            <div class="mt-2" id="conversion_info"
                                style="display:<?= $data['trx']['currency_code'] != 'IDR' ? 'block' : 'none'; ?>;">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-exchange-alt me-1"></i>
                                                    <strong>Konversi ke IDR:</strong>
                                                </small>
                                                <h6 class="fw-bold mb-0">
                                                    Rp <?= number_format($data['trx']['amount'], 0, ',', '.'); ?>
                                                </h6>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">
                                                    <strong>Kurs:</strong> 1 <?= $data['trx']['currency_code']; ?>
                                                </small>
                                                <h6 class="fw-bold mb-0 text-primary">
                                                    Rp <?= number_format($data['trx']['exchange_rate'], 2, ',', '.'); ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan nominal dalam mata uang asli transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="<?= BASEURL; ?>/student" class="btn btn-outline-secondary me-md-2">
                    <i class="fas fa-times me-1"></i> Batal
                </a>
                <button type="button" class="btn btn-outline-danger me-md-2" onclick="confirmDelete()">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <i class="fas fa-save me-1"></i> Update Transaksi
                </button>
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
                    <h5 class="fw-bold">Hapus Transaksi?</h5>
                    <p class="text-muted mb-0">
                        Transaksi <strong>"<?= htmlspecialchars($data['trx']['description']); ?>"</strong><br>
                        dengan nominal <strong>Rp
                            <?= number_format($data['trx']['amount'], 0, ',', '.'); ?></strong><br>
                        akan dihapus secara permanen.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <a href="<?= BASEURL; ?>/student/delete/<?= $data['trx']['id']; ?>" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced form UX with inline validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editTransactionForm');
    const typeSelect = document.getElementById('type_select');
    const categorySelect = document.getElementById('category_select');
    const currencySelect = document.getElementById('currency_select');
    const currencyLabel = document.getElementById('currency_label');
    const conversionInfo = document.getElementById('conversion_info');
    const amountInput = document.querySelector('input[name="amount"]');
    const submitBtn = document.getElementById('submitBtn');

    // Currency symbols
    const symbols = {
        'IDR': 'Rp',
        'USD': '$',
        'EUR': '€',
        'SGD': 'S$',
        'JPY': '¥'
    };

    // Currency change handler
    currencySelect.addEventListener('change', function() {
        const currency = this.value;
        currencyLabel.textContent = symbols[currency] || currency;

        if (currency !== 'IDR') {
            conversionInfo.style.display = 'block';
            fetchNewExchangeRate(currency);
        } else {
            conversionInfo.style.display = 'none';
        }

        validateField(this);
    });

    // Type change handler - filter categories
    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        const options = categorySelect.options;

        // Reset selection if doesn't match
        const currentOption = options[categorySelect.selectedIndex];
        if (currentOption && currentOption.getAttribute('data-type') !== selectedType) {
            categorySelect.selectedIndex = 0;
            categorySelect.classList.remove('is-valid');
        }

        // Filter categories
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const optionType = option.getAttribute('data-type');

            if (optionType && optionType !== selectedType) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = '';
                option.disabled = false;
            }
        }

        validateField(this);
    });

    // Initialize type filter
    typeSelect.dispatchEvent(new Event('change'));

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

    // Check date max
    if (field.type === 'date' && value > field.max) {
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
    const form = document.getElementById('editTransactionForm');
    let isValid = true;

    form.querySelectorAll('[required]').forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// Confirm delete
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
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

// Fetch new exchange rate (simulated)
function fetchNewExchangeRate(currency) {
    // In real app, you would make an API call here
    console.log('Fetching new exchange rate for', currency);
    // Update conversion info with new rate
}

// Check if form has changes
function formHasChanges() {
    const form = document.getElementById('editTransactionForm');
    const originalData = {
        date: '<?= date("Y-m-d", strtotime($data['trx']['transaction_date'])); ?>',
        type: '<?= $data['trx']['type']; ?>',
        category_id: '<?= $data['trx']['category_id']; ?>',
        description: '<?= addslashes(htmlspecialchars($data['trx']['description'])); ?>',
        currency: '<?= $data['trx']['currency_code']; ?>',
        amount: '<?= $data['trx']['amount_origin']; ?>'
    };

    return form.date.value !== originalData.date ||
        form.type.value !== originalData.type ||
        form.category_id.value != originalData.category_id ||
        form.description.value !== originalData.description ||
        form.currency.value !== originalData.currency ||
        form.amount.value.replace(/[.,]/g, '') != originalData.amount;
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

/* Style full width */
.card {
    border-radius: 16px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }

    .d-md-flex .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>