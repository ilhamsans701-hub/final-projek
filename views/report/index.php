<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Laporan Keuangan</h5>
            <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
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
                                        <select name="month" class="form-select" required>
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
                                        <select name="year" class="form-select" required>
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
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih bulan dan tahun untuk laporan yang diinginkan
                                </small>
                            </div>

                            <div class="d-grid gap-3">
                                <button type="submit" formaction="<?= BASEURL; ?>/report/print"
                                    class="btn btn-primary btn-lg py-3">
                                    <i class="fas fa-file-pdf me-2"></i>Preview & Cetak PDF
                                </button>

                                <button type="submit" formaction="<?= BASEURL; ?>/report/export_csv"
                                    class="btn btn-success btn-lg py-3">
                                    <i class="fas fa-file-csv me-2"></i>Download Excel (CSV)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Petunjuk
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>PDF Preview</strong> - Tampilan untuk dicetak atau disimpan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-file-excel text-success me-2"></i>
                                <strong>Excel/CSV</strong> - Data mentah untuk analisis lanjutan
                            </li>
                            <li>
                                <i class="fas fa-print text-primary me-2"></i>
                                <strong>Print</strong> - Gunakan fitur print browser untuk hasil terbaik
                            </li>
                        </ul>
                    </div>
                </div>
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

.card {
    border-radius: 16px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.btn-lg {
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }

    .btn-lg {
        padding: 0.75rem 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reportForm');

    // Untuk tombol PDF, validasi sederhana
    const pdfBtn = form.querySelector('button[formaction*="print"]');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showCustomAlert('Harap pilih bulan dan tahun terlebih dahulu', 'warning');
            }
        });
    }

    // Untuk tombol CSV, validasi sederhana
    const csvBtn = form.querySelector('button[formaction*="export_csv"]');
    if (csvBtn) {
        csvBtn.addEventListener('click', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showCustomAlert('Harap pilih bulan dan tahun terlebih dahulu', 'warning');
            }
        });
    }
});

function validateForm() {
    const form = document.getElementById('reportForm');
    let isValid = true;

    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });

    return isValid;
}

function showCustomAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
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