<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASEURL; ?>/dashboard">Panel Orang Tua</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASEURL; ?>/dashboard">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASEURL; ?>/report">Cetak Laporan</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-tie"></i> <?= $data['user']; ?>
                </span>
                <a href="<?= BASEURL; ?>/auth/logout" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-primary d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="alert-heading mb-1">Kode Keluarga: <strong><?= $data['family_code']; ?></strong></h4>
                    <small>Berikan kode ini kepada anak Anda agar akun mereka terhubung ke sini.</small>
                </div>
                <i class="fas fa-users fa-3x opacity-50"></i>
            </div>
        </div>
    </div>

    <h5 class="mb-3 text-secondary border-bottom pb-2">Monitoring Keuangan Anak</h5>

    <?php if(empty($data['children_data'])) : ?>
    <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="150" class="mb-3 opacity-50">
        <h5 class="text-muted">Belum ada anak yang terhubung.</h5>
        <p>Pastikan anak Anda mendaftar menggunakan Kode Keluarga di atas.</p>
    </div>
    <?php else : ?>

    <div class="row">
        <?php foreach($data['children_data'] as $child) : ?>
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100 border-left-primary">

                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-child"></i> <?= $child['info']['username']; ?>
                    </h6>
                    <span class="badge bg-success" style="font-size: 1em;">
                        Saldo: Rp <?= number_format($child['saldo'], 0, ',', '.'); ?>
                    </span>
                </div>

                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6 border-end">
                            <small class="text-muted">Pemasukan</small>
                            <div class="fw-bold text-success">Rp <?= number_format($child['income'], 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Pengeluaran</small>
                            <div class="fw-bold text-danger">Rp <?= number_format($child['expense'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>

                    <h6 class="small font-weight-bold text-secondary">5 Transaksi Terakhir:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th>Tgl</th>
                                    <th>Ket</th>
                                    <th class="text-end">Jml</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($child['transactions'])) : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data.</td>
                                </tr>
                                <?php else : ?>
                                <?php foreach($child['transactions'] as $t) : ?>
                                <tr>
                                    <td><?= date('d/m', strtotime($t['transaction_date'])); ?></td>
                                    <td><?= substr($t['description'], 0, 20); ?>..</td>
                                    <td
                                        class="text-end <?= ($t['type'] == 'expense') ? 'text-danger' : 'text-success'; ?>">
                                        <?= number_format($t['amount'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted"><i class="fas fa-clock"></i> Data Realtime</small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div>