<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">MyMoney (Orang Tua)</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Halo, <?= $data['user']; ?>
            </span>
            <a href="<?= BASEURL; ?>/auth/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4>Selamat Datang di Panel Pengawasan</h4>
                <p>Anda login sebagai Orang Tua. Di sini Anda dapat memantau pengeluaran anak dan mengatur anggaran.</p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Kode Keluarga Anda</h5>
                    <h2 class="text-primary">FAM-XXXX</h2>
                    <p class="card-text text-muted">Berikan kode ini kepada anak Anda saat registrasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>