<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Tambah Transaksi Baru</h5>
        <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <form action="<?= BASEURL; ?>/student/store" method="POST" id="transactionForm" novalidate>
            <div class="row g-4">
                <!-- Tanggal dan Jenis Transaksi -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">
                        <i class="fas fa-calendar me-1"></i> Tanggal Transaksi *
                    </label>
                    <input type="date" name="date" class="form-control" value="<?= date('Y-m-d'); ?>" required
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
                        <option value="expense" selected>Pengeluaran</option>
                        <option value="income">Pemasukan</option>
                    </select>
                    <div class="invalid-feedback">
                        Pilih jenis transaksi.
                    </div>
                </div>

                <!-- Kategori -->
                <div class="col-12">
                    <label class="form-label fw-medium">
                        <i class="fas fa-tags me-1"></i> Kategori *
                    </label>
                    <select name="category_id" class="form-select" id="category_select" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        <?php foreach($data['categories'] as $cat) : ?>
                        <option value="<?= $cat['id']; ?>" data-type="<?= $cat['type']; ?>">
                            <?= ucfirst($cat['type']); ?> - <?= $cat['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Pilih kategori transaksi.
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="col-12">
                    <label class="form-label fw-medium">
                        <i class="fas fa-align-left me-1"></i> Deskripsi *
                    </label>
                    <input type="text" name="description" class="form-control"
                        placeholder="Contoh: Makan siang, Beli buku, Transfer uang saku" required minlength="3"
                        maxlength="100">
                    <div class="invalid-feedback">
                        Deskripsi harus diisi (3-100 karakter).
                    </div>
                </div>

                <!-- Mata Uang dan Nominal -->
                <div class="col-md-4">
                    <label class="form-label fw-medium">
                        <i class="fas fa-globe me-1"></i> Mata Uang *
                    </label>
                    <select name="currency" class="form-select" id="currency_select" required>
                        <option value="IDR" selected>IDR - Rupiah</option>
                        <option value="USD">USD - Dollar AS</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="SGD">SGD - Dollar Singapura</option>
                        <option value="JPY">JPY - Yen Jepang</option>
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
                        <span class="input-group-text" id="currency_label">Rp</span>
                        <input type="text" name="amount" class="form-control" placeholder="0" required
                            pattern="[0-9.,]+" data-type="currency">
                        <button type="button" class="btn btn-outline-secondary" onclick="formatAmount(this)">
                            <i class="fas fa-calculator"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        Nominal harus diisi dengan angka yang valid.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASEURL; ?>/student" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Floating Action Button -->
<a href="<?= BASEURL; ?>/student" class="fab" title="Kembali ke Dashboard">
    <i class="fas fa-arrow-left"></i>
</a>

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

@media (max-width: 768px) {
    .table-container {
        padding: 1rem !important;
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
// Transaction Form Validation & Interaction
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('transactionForm');
    const typeSelect = document.getElementById('type_select');
    const categorySelect = document.getElementById('category_select');
    const currencySelect = document.getElementById('currency_select');
    const currencyLabel = document.getElementById('currency_label');
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

    // Real-time validation
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
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
            submitBtn.disabled = true;
        }
    });

    // Auto-format number input on focus
    amountInput.addEventListener('focus', function() {
        const value = this.value.replace(/[.,]/g, '');
        this.value = value;
    });
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

        // Check pattern (for amount)
        if (pattern) {
            const regex = new RegExp(pattern);
            if (!regex.test(value.replace(/[.,]/g, ''))) {
                field.classList.add('is-invalid');
                return false;
            }
        }

        // Check date max
        if (type === 'date' && value > field.max) {
            field.classList.add('is-invalid');
            return false;
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
    const form = document.getElementById('transactionForm');
    let isValid = true;

    form.querySelectorAll('[required]').forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
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