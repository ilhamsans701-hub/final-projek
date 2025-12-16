<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Tambah Tagihan Rutin</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/subscription/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="service_name" class="form-control"
                                placeholder="Contoh: Kosan, Netflix, SPP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biaya (IDR)</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Siklus</label>
                            <select name="billing_cycle" class="form-select">
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jatuh Tempo Berikutnya</label>
                            <input type="date" name="due_date" class="form-control" required>
                            <small class="text-muted">Sistem akan mengingatkan H-3.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-dark">Daftar Tagihan Aktif</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php Flasher::flash(); ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Layanan</th>
                                    <th>Biaya</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status (Alert)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['subscriptions'])) : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada tagihan aktif. Hidup
                                        tenang! 😎</td>
                                </tr>
                                <?php else : ?>
                                <?php foreach($data['subscriptions'] as $sub) : ?>
                                <tr>
                                    <td class="fw-bold"><?= $sub['service_name']; ?></td>
                                    <td>Rp <?= number_format($sub['amount'], 0, ',', '.'); ?></td>
                                    <td><?= date('d M Y', strtotime($sub['due_date'])); ?></td>

                                    <td>
                                        <?php if($sub['status'] == 'overdue') : ?>
                                        <span class="badge bg-dark">Telat <?= $sub['days_left']; ?> hari</span>
                                        <?php elseif($sub['status'] == 'danger') : ?>
                                        <span class="badge bg-danger animate-blink">H-<?= $sub['days_left']; ?>
                                            Bayar!</span>
                                        <?php elseif($sub['status'] == 'warning') : ?>
                                        <span class="badge bg-warning text-dark">H-<?= $sub['days_left']; ?></span>
                                        <?php else : ?>
                                        <span class="badge bg-success">Aman (<?= $sub['days_left']; ?> hari)</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="<?= BASEURL; ?>/subscription/delete/<?= $sub['id']; ?>"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Yakin stop langganan ini?');">
                                            <i class="fas fa-times"></i> Stop
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
</style>