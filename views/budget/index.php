<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Kelola Anggaran Anak</h5>
        <a href="<?= BASEURL; ?>/dashboard" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <div class="row g-4">
            <!-- Form Set Anggaran -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-coins me-2 text-primary"></i>Atur Anggaran Baru
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASEURL; ?>/budget/store" method="POST" id="budgetForm" novalidate>
                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-child me-1"></i> Pilih Anak *
                                </label>
                                <select name="child_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih anak</option>
                                    <?php foreach($data['children'] as $child) : ?>
                                    <option value="<?= $child['id']; ?>">
                                        <?= htmlspecialchars($child['username']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih anak.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-calendar me-1"></i> Bulan & Tahun *
                                </label>
                                <input type="month" name="month_year" class="form-control"
                                    value="<?= $data['current_month']; ?>" min="2024-01" max="2026-12" required>
                                <div class="invalid-feedback">
                                    Pilih bulan dan tahun.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-money-bill-wave me-1"></i> Jumlah Anggaran (IDR) *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="amount" class="form-control" placeholder="0" required
                                        data-type="currency">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatAmount(this)">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Jumlah anggaran harus diisi.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Simpan Anggaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Anggaran -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-list-alt me-2 text-primary"></i>Daftar Anggaran Aktif
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($data['budgets'])) : ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-coins fa-3x text-warning mb-3"></i>
                                <h5 class="fw-bold">Belum ada anggaran</h5>
                                <p class="text-muted mb-0">Atur anggaran pertama untuk anak Anda</p>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="budgetsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-medium">Anak</th>
                                        <th class="fw-medium">Periode</th>
                                        <th class="fw-medium">Anggaran</th>
                                        <th class="fw-medium">Status</th>
                                        <th class="fw-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['budgets'] as $budget) : 
                                            // Hitung pengeluaran aktual bulan ini
                                            $monthYear = $budget['month_year'];
                                            list($year, $month) = explode('-', $monthYear);
                                            $actualExpense = $this->model('Transaction_model')->getMonthlyExpense($budget['user_id'], $month, $year);
                                            $progress = $actualExpense > 0 ? ($actualExpense / $budget['amount']) * 100 : 0;
                                        ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($budget['child_name']); ?></div>
                                        </td>
                                        <td>
                                            <?= date('F Y', strtotime($budget['month_year'] . '-01')); ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold">Rp
                                                <?= number_format($budget['amount'], 0, ',', '.'); ?></div>
                                            <small class="text-muted">
                                                Terpakai: Rp <?= number_format($actualExpense, 0, ',', '.'); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if($progress >= 100) : ?>
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Melebihi
                                            </span>
                                            <?php elseif($progress >= 80) : ?>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-exclamation me-1"></i>
                                                Hampir Habis
                                            </span>
                                            <?php else : ?>
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check me-1"></i>
                                                <?= round(100 - $progress, 1); ?>% Tersisa
                                            </span>
                                            <?php endif; ?>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar <?= $progress >= 100 ? 'bg-danger' : ($progress >= 80 ? 'bg-warning' : 'bg-success'); ?>"
                                                    style="width: <?= min(100, $progress); ?>%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?= BASEURL; ?>/budget/delete/<?= $budget['id']; ?>"
                                                class="btn btn-outline-danger btn-sm px-3 delete-btn"
                                                data-child="<?= htmlspecialchars($budget['child_name']); ?>"
                                                data-month="<?= date('F Y', strtotime($budget['month_year'] . '-01')); ?>">
                                                <i class="fas fa-trash me-1"></i> Hapus
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

<!-- Delete Modal -->
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
                    <h5 class="fw-bold">Hapus Anggaran?</h5>
                    <p class="text-muted mb-0">
                        Anggaran untuk <strong id="childName"></strong><br>
                        periode <strong id="monthName"></strong><br>
                        akan dihapus permanen.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Budget Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Format currency
    function formatAmount(inputElement) {
        const input = inputElement.tagName === 'INPUT' ? inputElement : inputElement.previousElementSibling;
        const value = input.value.replace(/[.,]/g, '');

        if (value && !isNaN(value) && value > 0) {
            input.value = parseFloat(value).toLocaleString('id-ID');
        }
    }

    // Setup delete buttons
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const url = this.href;
        const childName = $(this).data('child');
        const monthName = $(this).data('month');

        $('#childName').text(childName);
        $('#monthName').text(monthName);

        const modal = new bootstrap.Modal($('#deleteModal')[0]);
        modal.show();

        $('#confirmDeleteBtn').off('click').on('click', function() {
            window.location.href = url;
        });
    });

    // Form validation
    const form = document.getElementById('budgetForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const amountInput = form.querySelector('input[name="amount"]');
            const amountValue = amountInput.value.replace(/[.,]/g, '');

            if (!amountValue || isNaN(amountValue) || amountValue <= 0) {
                e.preventDefault();
                amountInput.classList.add('is-invalid');
                showCustomAlert('Jumlah anggaran harus lebih dari 0', 'warning');
            }
        });
    }
});

function showCustomAlert(message, type = 'info') {
    // Custom alert implementation
    alert(message);
}
</script>