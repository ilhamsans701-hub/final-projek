<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Kelola Tagihan Rutin</h5>
        <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <!-- Form Tambah Tagihan -->
        <div class="row g-4 mb-4">
            <!-- FORM TAMBAH DI KIRI -->
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
                                    <input type="text" name="amount_display" class="form-control" placeholder="0"
                                        required id="amountDisplay" data-type="currency">
                                    <input type="hidden" name="amount" id="realAmount">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatAmountDisplay(document.getElementById('amountDisplay'))">
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
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Simpan Tagihan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- DAFTAR TAGIHAN DI KANAN -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-list-alt me-2 text-primary"></i>Daftar Tagihan Aktif
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <?php if(empty($data['subscriptions'])) : ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-smile fa-3x text-warning mb-3"></i>
                                <h5 class="fw-bold">Tidak ada tagihan aktif</h5>
                                <p class="text-muted mb-0">Hidup tenang!</p>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover" id="subscriptionsTable">
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
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
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
                                                class="btn btn-outline-danger btn-sm px-3 delete-btn"
                                                data-service="<?= htmlspecialchars($sub['service_name']); ?>"
                                                data-amount="<?= number_format($sub['amount'], 0, ',', '.'); ?>">
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
                        Tagihan <strong id="serviceName"></strong><br>
                        dengan biaya <strong id="serviceAmount"></strong><br>
                        akan dihentikan secara permanen.
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

<!-- Floating Action Button -->
<a href="#addSubscriptionForm" class="fab" title="Tambah Tagihan Baru"
    onclick="document.getElementById('service_name').focus()">
    <i class="fas fa-plus"></i>
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

/* Badge styles */
.badge {
    font-size: 0.8rem;
    white-space: nowrap;
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

/* Responsive */
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

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem !important;
    }
}
</style>

<script>
// DataTables Initialization
// DataTables Initialization
$(document).ready(function() {
    // Initialize DataTables jika ada data
    if ($('#subscriptionsTable').length) {
        const table = $('#subscriptionsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [
                [2, 'asc']
            ], // Sort by due date
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50],
                [5, 10, 25, 50]
            ],
            responsive: true,
            autoWidth: false,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: 3
                },
                {
                    responsivePriority: 3,
                    targets: 1
                },
                {
                    responsivePriority: 4,
                    targets: 2
                },
                {
                    responsivePriority: 5,
                    targets: 4
                },
                {
                    orderable: false,
                    targets: 4
                }
            ],
            initComplete: function() {
                // Setup delete button handlers
                setupDeleteButtons();
            }
        });

        // Reinitialize delete buttons after DataTables redraw
        table.on('draw', function() {
            setupDeleteButtons();
        });

        // Adjust table for mobile
        if (window.innerWidth <= 768) {
            table.responsive.recalc();
        }

        $(window).on('resize', function() {
            if (window.innerWidth <= 768) {
                table.responsive.recalc();
            }
        });
    }

    // Setup form validation
    setupFormValidation();

    // Setup amount input formatting
    setupAmountFormatting();
});

// Setup delete buttons
function setupDeleteButtons() {
    $('.delete-btn').off('click').on('click', function(e) {
        e.preventDefault();

        const url = this.href;
        const serviceName = $(this).data('service');
        const serviceAmount = $(this).data('amount');

        // Update modal content
        $('#serviceName').text(serviceName);
        $('#serviceAmount').text('Rp ' + serviceAmount);

        // Show modal
        const modal = new bootstrap.Modal($('#deleteModal')[0]);
        modal.show();

        // Setup confirm button
        $('#confirmDeleteBtn').off('click').on('click', function() {
            const originalText = $(this).html();
            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...');
            $(this).prop('disabled', true);

            window.location.href = url;
        });

        // Reset confirm button when modal is hidden
        $('#deleteModal').on('hidden.bs.modal', function() {
            $('#confirmDeleteBtn').html('<i class="fas fa-trash me-2"></i>Ya, Hentikan');
            $('#confirmDeleteBtn').prop('disabled', false);
        });
    });
}

// Setup amount input formatting
function setupAmountFormatting() {
    const amountDisplay = document.getElementById('amountDisplay');
    const realAmount = document.getElementById('realAmount');

    if (!amountDisplay || !realAmount) return;

    // Saat input fokus, tampilkan angka tanpa format
    amountDisplay.addEventListener('focus', function() {
        const rawValue = this.value.replace(/[^\d]/g, '');
        this.value = rawValue;
        this.classList.remove('is-valid', 'is-invalid');
    });

    // Saat input tidak fokus (blur), format tampilan
    amountDisplay.addEventListener('blur', function() {
        formatAmountDisplay(this);
    });

    // Saat user mengetik, update real value
    amountDisplay.addEventListener('input', function() {
        const rawValue = this.value.replace(/[^\d]/g, '');
        realAmount.value = rawValue;

        // Validasi sederhana sambil mengetik
        validateAmountField(this, rawValue);
    });

    // Trigger format awal jika ada nilai
    if (amountDisplay.value) {
        formatAmountDisplay(amountDisplay);
    }
}

// Format amount display
function formatAmountDisplay(inputElement) {
    const realInput = document.getElementById('realAmount');
    let rawValue = inputElement.value.replace(/[^\d]/g, '');

    // Update real input
    realInput.value = rawValue;

    // Validasi
    if (!rawValue || rawValue === '0' || rawValue === '') {
        inputElement.classList.add('is-invalid');
        inputElement.classList.remove('is-valid');
        return;
    }

    // Konversi ke number
    const numberValue = parseInt(rawValue);

    if (!isNaN(numberValue) && numberValue > 0) {
        // Format tampilan dengan titik sebagai pemisah ribuan
        inputElement.value = numberValue.toLocaleString('id-ID');

        // Update real input dengan nilai yang sudah diparse
        realInput.value = numberValue;

        inputElement.classList.add('is-valid');
        inputElement.classList.remove('is-invalid');
    } else {
        inputElement.classList.add('is-invalid');
        inputElement.classList.remove('is-valid');
    }
}

// Validate amount field
function validateAmountField(field, rawValue) {
    if (!rawValue || rawValue === '0' || rawValue === '') {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        return false;
    }

    const numberValue = parseInt(rawValue);

    if (!isNaN(numberValue) && numberValue > 0) {
        field.classList.add('is-valid');
        field.classList.remove('is-invalid');
        return true;
    } else {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        return false;
    }
}

// Validate individual field
function validateField(field) {
    // Skip amount display karena sudah dihandle khusus
    if (field.id === 'amountDisplay') {
        const rawValue = field.value.replace(/[^\d]/g, '');
        return validateAmountField(field, rawValue);
    }

    // Skip real amount input
    if (field.id === 'realAmount') {
        return true;
    }

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

    // Check pattern (if any)
    if (pattern && value) {
        const regex = new RegExp(pattern);
        if (!regex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }

    // Check date min
    if (field.type === 'date' && value && value < field.min) {
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

    // Validasi amount khusus
    const amountDisplay = document.getElementById('amountDisplay');
    const realAmount = document.getElementById('realAmount');

    if (amountDisplay) {
        const rawValue = amountDisplay.value.replace(/[^\d]/g, '');
        const realValue = realAmount ? realAmount.value : rawValue;

        if (!realValue || parseInt(realValue) <= 0) {
            amountDisplay.classList.add('is-invalid');
            isValid = false;
        } else {
            amountDisplay.classList.remove('is-invalid');
        }
    }

    // Validasi field lainnya
    form.querySelectorAll('[required]').forEach(field => {
        if (field.id !== 'realAmount' && !validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// Setup form validation
function setupFormValidation() {
    const form = document.getElementById('addSubscriptionForm');
    if (!form) return;

    const submitBtn = document.getElementById('submitBtn');

    // Real-time validation untuk semua field kecuali realAmount
    form.querySelectorAll('input, select').forEach(field => {
        if (field.id !== 'realAmount') {
            field.addEventListener('input', function() {
                validateField(this);
            });

            field.addEventListener('blur', function() {
                validateField(this);
            });
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Pastikan realAmount diisi dengan nilai yang benar
        const amountDisplay = document.getElementById('amountDisplay');
        const realAmount = document.getElementById('realAmount');

        if (amountDisplay && realAmount) {
            const rawValue = amountDisplay.value.replace(/[^\d]/g, '');
            const numberValue = parseInt(rawValue);

            if (!isNaN(numberValue) && numberValue > 0) {
                realAmount.value = numberValue;
            } else {
                // Jika ada format yang salah, coba ambil dari value asli
                const displayValue = amountDisplay.value.replace(/[^\d]/g, '');
                if (displayValue) {
                    realAmount.value = displayValue;
                }
            }
        }

        if (validateForm()) {
            showLoading(submitBtn, 'Menyimpan...');

            // Debug: lihat nilai yang akan dikirim
            console.log('Nilai yang dikirim:');
            console.log('Service Name:', form.querySelector('[name="service_name"]').value);
            console.log('Amount (real):', realAmount.value);
            console.log('Billing Cycle:', form.querySelector('[name="billing_cycle"]').value);
            console.log('Due Date:', form.querySelector('[name="due_date"]').value);

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
if (!document.querySelector('#slideInRightAnimation')) {
    const style = document.createElement('style');
    style.id = 'slideInRightAnimation';
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
}

// Helper function untuk format button (jika ada)
function formatAmountBtn(button) {
    const input = button.previousElementSibling;
    if (input && input.id === 'amountDisplay') {
        formatAmountDisplay(input);
    }
}
</script>