<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Laporan Keuangan</h5>
        <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Form Generate Laporan -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Generate Laporan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" id="reportForm" target="_blank" novalidate>
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-calendar-alt me-1"></i> Periode Laporan *
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <select name="month" class="form-select" id="monthSelect" required>
                                            <option value="" disabled selected>Pilih Bulan</option>
                                            <?php 
                                            for($m=1; $m<=12; $m++){
                                                $monthName = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                                $selected = ($m == date('n')) ? 'selected' : '';
                                                echo "<option value='$m' $selected>$monthName</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Pilih bulan.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="year" class="form-select" id="yearSelect" required>
                                            <option value="" disabled selected>Pilih Tahun</option>
                                            <?php 
                                            $currentYear = date('Y');
                                            for($y=$currentYear; $y>=$currentYear-5; $y--){
                                                $selected = ($y == $currentYear) ? 'selected' : '';
                                                echo "<option value='$y' $selected>$y</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Pilih tahun.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row g-3 mt-4 pt-3 border-top">
                                <div class="col-md-6">
                                    <button type="submit" formaction="<?= BASEURL; ?>/report/print"
                                        class="btn btn-primary w-100">
                                        <i class="fas fa-file-pdf me-2"></i>Cetak PDF
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" formaction="<?= BASEURL; ?>/report/export_csv"
                                        class="btn btn-success w-100">
                                        <i class="fas fa-file-excel me-2"></i>Download Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="advice-panel">
                    <div class="d-flex align-items-start">
                        <div class="me-3" style="font-size: 2rem;">
                            <i class="fas fa-info-circle text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2">Petunjuk Laporan</h5>
                            <ul class="mb-0 ps-3">
                                <li><strong>PDF</strong> - Tampilan untuk dicetak atau disimpan</li>
                                <li><strong>Excel/CSV</strong> - Data mentah untuk analisis lanjutan</li>
                                <li><strong>Print</strong> - Gunakan fitur print browser untuk hasil terbaik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="<?= BASEURL; ?>/student" class="fab" title="Kembali ke Dashboard">
    <i class="fas fa-home"></i>
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

    .advice-panel {
        padding: 1rem !important;
    }
}
</style>

<script>
// Report Form Validation & Interaction
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reportForm');
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');

    // Set default values if not selected
    if (monthSelect.value === '') {
        monthSelect.value = '<?= date('n'); ?>';
    }
    if (yearSelect.value === '') {
        yearSelect.value = '<?= date('Y'); ?>';
    }

    // Validate fields on change
    [monthSelect, yearSelect].forEach(field => {
        field.addEventListener('change', function() {
            validateField(this);
        });

        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Initialize validation
    validateField(monthSelect);
    validateField(yearSelect);
});

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');

    // Reset validation classes
    field.classList.remove('is-valid', 'is-invalid');

    // Check if field is required and empty
    if (isRequired && !value) {
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
    const form = document.getElementById('reportForm');
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