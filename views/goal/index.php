<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Target Tabungan Saya</h5>
        <a href="<?= BASEURL; ?>/student" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <div class="row g-4">
            <!-- Form Tambah Goal -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3 rounded-top-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-bullseye me-2 text-primary"></i>Tambah Target Baru
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASEURL; ?>/goal/store" method="POST" id="goalForm" novalidate>
                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-tag me-1 text-primary"></i> Nama Target *
                                </label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Contoh: Laptop Baru, Liburan, dll" required minlength="3"
                                    maxlength="100" oninput="validateField(this)">
                                <div class="invalid-feedback">
                                    Nama target harus diisi (3-100 karakter).
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-money-bill-wave me-1 text-primary"></i> Target Jumlah (IDR) *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="text" name="target_amount" class="form-control border-start-0"
                                        placeholder="0" required data-type="currency"
                                        oninput="validateCurrencyField(this)" onblur="formatCurrency(this)">
                                </div>
                                <div class="invalid-feedback">
                                    Target jumlah harus diisi.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-calendar-day me-1 text-primary"></i> Target Tanggal *
                                </label>
                                <input type="date" name="deadline" class="form-control" min="<?= date('Y-m-d'); ?>"
                                    required oninput="validateField(this)">
                                <div class="invalid-feedback">
                                    Pilih target tanggal.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">
                                <i class="fas fa-plus-circle me-1"></i> Tambah Target
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="advice-panel rounded-3">
                    <div class="d-flex align-items-start">
                        <div class="me-3" style="font-size: 2rem;">
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">Statistik Target</h6>
                            <?php 
                                $totalGoals = count($data['goals']);
                                $activeGoals = array_filter($data['goals'], fn($g) => $g['status'] === 'active');
                                $completedGoals = array_filter($data['goals'], fn($g) => $g['status'] === 'completed');
                            ?>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="text-center border rounded p-2">
                                        <small class="text-muted d-block">Total</small>
                                        <strong class="text-primary"><?= $totalGoals; ?></strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center border rounded p-2">
                                        <small class="text-muted d-block">Aktif</small>
                                        <strong class="text-success"><?= count($activeGoals); ?></strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center border rounded p-2">
                                        <small class="text-muted d-block">Selesai</small>
                                        <strong class="text-primary"><?= count($completedGoals); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Goals -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3 rounded-top-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-list-check me-2 text-primary"></i>Daftar Target Saya
                            </h6>
                            <span class="badge bg-primary rounded-pill px-3">
                                <?= count($data['goals']); ?> Target
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($data['goals'])) : ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-bullseye fa-3x text-warning mb-3"></i>
                                <h5 class="fw-bold">Belum ada target tabungan</h5>
                                <p class="text-muted mb-0">Mulai dengan menambahkan target pertama Anda</p>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($data['goals'] as $goal) : 
                                    $progress = $goal['target_amount'] > 0 ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                                    $daysLeft = floor((strtotime($goal['deadline']) - time()) / (60 * 60 * 24));
                                ?>
                            <div class="list-group-item border-0 px-4 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            <?= htmlspecialchars($goal['title']); ?>
                                            <?php if($goal['status'] === 'completed') : ?>
                                            <span class="badge bg-primary rounded-pill px-3 ms-2">Selesai</span>
                                            <?php elseif($goal['status'] === 'cancelled') : ?>
                                            <span class="badge bg-secondary rounded-pill px-3 ms-2">Dibatalkan</span>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Target: <?= date('d M Y', strtotime($goal['deadline'])); ?>
                                            <?php if($daysLeft > 0) : ?>
                                            <span class="text-success">
                                                (<?= $daysLeft; ?> hari lagi)
                                            </span>
                                            <?php elseif($daysLeft == 0) : ?>
                                            <span class="text-warning">
                                                (Hari ini!)
                                            </span>
                                            <?php else : ?>
                                            <span class="text-danger">
                                                (<?= abs($daysLeft); ?> hari terlambat)
                                            </span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary border-0 rounded-circle"
                                            type="button" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <?php if($goal['status'] === 'active') : ?>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#addProgressModal"
                                                    data-goal-id="<?= $goal['id']; ?>"
                                                    data-goal-title="<?= htmlspecialchars($goal['title']); ?>">
                                                    <i class="fas fa-plus me-2 text-primary"></i>Tambah Progress
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-success complete-btn" href="#"
                                                    data-goal-id="<?= $goal['id']; ?>"
                                                    data-goal-title="<?= htmlspecialchars($goal['title']); ?>">
                                                    <i class="fas fa-check me-2"></i>Tandai Selesai
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <a class="dropdown-item text-danger delete-btn" href="#"
                                                    data-goal-id="<?= $goal['id']; ?>"
                                                    data-goal-title="<?= htmlspecialchars($goal['title']); ?>">
                                                    <i class="fas fa-trash me-2"></i>Hapus Target
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">
                                            Terkumpul: <span class="fw-bold">Rp
                                                <?= number_format($goal['current_amount'], 0, ',', '.'); ?></span>
                                        </small>
                                        <small
                                            class="fw-bold <?= $progress >= 100 ? 'text-success' : 'text-primary'; ?>">
                                            <?= round($progress, 1); ?>%
                                        </small>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 8px;">
                                        <div class="progress-bar <?= $progress >= 100 ? 'bg-success' : 'bg-primary'; ?>"
                                            style="width: <?= min(100, $progress); ?>%">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Rp 0</small>
                                        <small class="text-muted fw-medium">
                                            Rp <?= number_format($goal['target_amount'], 0, ',', '.'); ?>
                                        </small>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <?php if($goal['status'] === 'active') : ?>
                                <div class="mt-3 pt-2 border-top">
                                    <div class="btn-group btn-group-sm rounded-pill" role="group">
                                        <button type="button" class="btn btn-outline-primary rounded-start-pill"
                                            data-bs-toggle="modal" data-bs-target="#addProgressModal"
                                            data-goal-id="<?= $goal['id']; ?>"
                                            data-goal-title="<?= htmlspecialchars($goal['title']); ?>">
                                            <i class="fas fa-plus me-1"></i> Tambah Progress
                                        </button>
                                        <a href="#" class="btn btn-outline-success rounded-end-pill complete-btn"
                                            data-goal-id="<?= $goal['id']; ?>"
                                            data-goal-title="<?= htmlspecialchars($goal['title']); ?>">
                                            <i class="fas fa-check me-1"></i> Tandai Selesai
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Progress Modal -->
<div class="modal fade" id="addProgressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Progress
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p id="modalGoalTitle" class="fw-bold mb-4 text-center"></p>
                <form id="progressForm" method="POST" novalidate>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Jumlah (IDR) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="text" name="amount" class="form-control border-start-0" placeholder="0"
                                required data-type="currency" oninput="validateCurrencyField(this)"
                                onblur="formatCurrency(this)">
                        </div>
                        <div class="invalid-feedback">
                            Jumlah harus diisi.
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-save me-2"></i>Simpan Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Hapus Target?</h5>
                    <p class="text-muted mb-0">
                        Target <strong id="deleteGoalTitle"></strong><br>
                        akan dihapus secara permanen.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger px-4 rounded-pill" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Complete Confirmation Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0">
                <h5 class="modal-title text-success">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Penyelesaian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Tandai Target Selesai?</h5>
                    <p class="text-muted mb-0">
                        Target <strong id="completeGoalTitle"></strong><br>
                        akan ditandai sebagai selesai.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-success px-4 rounded-pill" id="confirmCompleteBtn">
                    <i class="fas fa-check me-2"></i>Ya, Tandai Selesai
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Alert Container -->
<div id="customAlertContainer"></div>

<style>
/* Inline validation styles - Konsisten dengan halaman lain */
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

/* Input group styling konsisten */
.input-group-text {
    background-color: var(--bg-tertiary);
    border-color: var(--border-light);
    transition: all 0.2s;
}

.input-group:focus-within .input-group-text {
    border-color: var(--primary);
    background-color: rgba(99, 102, 241, 0.05);
}

/* Modal styling konsisten */
.modal-content {
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
}

.modal-header {
    background: rgba(99, 102, 241, 0.05);
    border-bottom: 1px solid rgba(99, 102, 241, 0.2);
}

#deleteModal .modal-header {
    background: rgba(239, 68, 68, 0.05);
    border-bottom: 1px solid rgba(239, 68, 68, 0.2);
}

#completeModal .modal-header {
    background: rgba(16, 185, 129, 0.05);
    border-bottom: 1px solid rgba(16, 185, 129, 0.2);
}

.modal-footer {
    background: var(--bg-tertiary);
}

/* Dropdown styling konsisten */
.dropdown-menu {
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: rgba(99, 102, 241, 0.1);
}

/* Progress bar rounded konsisten */
.rounded-pill {
    border-radius: 50rem !important;
}

.rounded-3 {
    border-radius: 12px !important;
}

.rounded-top-3 {
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
}

/* Button group rounded */
.rounded-start-pill {
    border-top-left-radius: 50rem !important;
    border-bottom-left-radius: 50rem !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

.rounded-end-pill {
    border-top-right-radius: 50rem !important;
    border-bottom-right-radius: 50rem !important;
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}

/* Custom alert styling */
.custom-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    box-shadow: var(--shadow-lg);
    animation: slideInRight 0.3s ease;
    border-radius: 12px;
    border: none;
}

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

/* Responsive adjustments */
@media (max-width: 768px) {
    .dropdown-menu {
        font-size: 0.875rem;
    }

    .dropdown-item {
        padding: 0.4rem 0.8rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup add progress modal
    const addProgressModal = document.getElementById('addProgressModal');
    if (addProgressModal) {
        addProgressModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const goalId = button.getAttribute('data-goal-id');
            const goalTitle = button.getAttribute('data-goal-title');

            const modalTitle = addProgressModal.querySelector('#modalGoalTitle');
            modalTitle.textContent = 'Tambah progress untuk: ' + goalTitle;

            const form = addProgressModal.querySelector('#progressForm');
            form.action = '<?= BASEURL; ?>/goal/add_progress/' + goalId;

            // Clear previous validation
            const amountInput = form.querySelector('input[name="amount"]');
            amountInput.classList.remove('is-valid', 'is-invalid');
            amountInput.value = '';
        });
    }

    // Form validation for add progress modal
    const progressForm = document.getElementById('progressForm');
    if (progressForm) {
        progressForm.addEventListener('submit', function(e) {
            const amountInput = this.querySelector('input[name="amount"]');
            const amountValue = amountInput.value.replace(/[.,]/g, '');

            if (!amountValue || isNaN(amountValue) || parseFloat(amountValue) <= 0) {
                e.preventDefault();
                amountInput.classList.add('is-invalid');
                showCustomAlert('Jumlah progress harus lebih dari 0', 'warning');
            }
        });
    }

    // Setup delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const goalId = this.getAttribute('data-goal-id');
            const goalTitle = this.getAttribute('data-goal-title');

            document.getElementById('deleteGoalTitle').textContent = goalTitle;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();

            document.getElementById('confirmDeleteBtn').onclick = function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';
                this.disabled = true;

                window.location.href = '<?= BASEURL; ?>/goal/delete/' + goalId;
            };
        });
    });

    // Setup complete buttons
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const goalId = this.getAttribute('data-goal-id');
            const goalTitle = this.getAttribute('data-goal-title');

            document.getElementById('completeGoalTitle').textContent = goalTitle;

            const modal = new bootstrap.Modal(document.getElementById('completeModal'));
            modal.show();

            document.getElementById('confirmCompleteBtn').onclick = function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                this.disabled = true;

                window.location.href = '<?= BASEURL; ?>/goal/complete/' + goalId;
            };
        });
    });

    // Form validation for goal form
    const goalForm = document.getElementById('goalForm');
    if (goalForm) {
        goalForm.addEventListener('submit', function(e) {
            if (!validateGoalForm()) {
                e.preventDefault();
                showCustomAlert('Harap periksa kembali form Anda', 'warning');

                const firstError = goalForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstError.focus();
                }
            } else {
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;
            }
        });
    }
});

// Validation functions konsisten dengan halaman lain
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const minLength = field.getAttribute('minlength');
    const maxLength = field.getAttribute('maxlength');

    field.classList.remove('is-valid', 'is-invalid');

    if (isRequired && !value) {
        field.classList.add('is-invalid');
        return false;
    }

    if (value) {
        if (minLength && value.length < minLength) {
            field.classList.add('is-invalid');
            return false;
        }

        if (maxLength && value.length > maxLength) {
            field.classList.add('is-invalid');
            return false;
        }
    }

    if (value) {
        field.classList.add('is-valid');
    }

    return true;
}

function validateCurrencyField(field) {
    const value = field.value.replace(/[.,]/g, '');

    field.classList.remove('is-valid', 'is-invalid');

    if (field.hasAttribute('required') && !value) {
        field.classList.add('is-invalid');
        return false;
    }

    if (value && (isNaN(value) || parseFloat(value) <= 0)) {
        field.classList.add('is-invalid');
        return false;
    }

    if (value) {
        field.classList.add('is-valid');
    }

    return true;
}

function formatCurrency(field) {
    const value = field.value.replace(/[.,]/g, '');

    if (value && !isNaN(value) && parseFloat(value) > 0) {
        field.value = parseFloat(value).toLocaleString('id-ID');
    }
}

function validateGoalForm() {
    const form = document.getElementById('goalForm');
    let isValid = true;

    form.querySelectorAll('[required]').forEach(field => {
        if (field.getAttribute('data-type') === 'currency') {
            if (!validateCurrencyField(field)) isValid = false;
        } else {
            if (!validateField(field)) isValid = false;
        }
    });

    return isValid;
}

// Custom alert function (konsisten dengan halaman lain)
function showCustomAlert(message, type = 'info') {
    const container = document.getElementById('customAlertContainer');

    // Remove existing alerts
    container.querySelectorAll('.custom-alert').forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getIconByType(type)} me-3"></i>
            <div class="flex-grow-1">
                <strong class="me-auto">${getTitleByType(type)}</strong>
                <div class="small mt-1">${message}</div>
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    container.appendChild(alertDiv);

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
</script>