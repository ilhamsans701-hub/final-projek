<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASEURL; ?>/student">MyMoney (Student)</a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASEURL; ?>/student">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASEURL; ?>/subscription">Tagihan & Alert</a>
                </li>
            </ul>

            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Halo, <?= $data['user']; ?>
                </span>
                <a href="<?= BASEURL; ?>/auth/logout" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-<?= $data['advice']['status']; ?> shadow-sm border-0 d-flex align-items-center"
                role="alert">
                <div class="me-3" style="font-size: 2rem;">
                    <?php if($data['advice']['status'] == 'success') : ?>
                    <i class="fas fa-smile-beam"></i>
                    <?php elseif($data['advice']['status'] == 'warning') : ?>
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php elseif($data['advice']['status'] == 'dark') : ?>
                    <i class="fas fa-dizzy"></i>
                    <?php else : ?>
                    <i class="fas fa-robot"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <?= $data['advice']['message']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow">
                <div class="card-header">Saldo Tersedia</div>
                <div class="card-body">
                    <?php 
                        $saldo = $data['summary']['total_income'] - $data['summary']['total_expense'];
                    ?>
                    <h3 class="card-title">Rp <?= number_format($saldo, 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow">
                <div class="card-header">Total Pemasukan</div>
                <div class="card-body">
                    <h3 class="card-title">Rp <?= number_format($data['summary']['total_income'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3 shadow">
                <div class="card-header">Total Pengeluaran</div>
                <div class="card-body">
                    <h3 class="card-title">Rp <?= number_format($data['summary']['total_expense'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Keuangan Tahun Ini</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Transaksi Terakhir</h5>
            <a href="<?= BASEURL; ?>/student/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Nominal</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['transactions'])) : ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                        <?php else : ?>
                        <?php foreach($data['transactions'] as $trx) : ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($trx['transaction_date'])); ?></td>
                            <td>
                                <i class="<?= $trx['category_icon']; ?> me-1"></i>
                                <?= $trx['category_name']; ?>
                            </td>
                            <td><?= $trx['description']; ?></td>
                            <td>Rp <?= number_format($trx['amount'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if($trx['type'] == 'income') : ?>
                                <span class="badge bg-success">Masuk</span>
                                <?php else : ?>
                                <span class="badge bg-danger">Keluar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Ambil data dari PHP yang dikirim lewat Controller
const incomeData = <?= $data['chart_income']; ?>;
const expenseData = <?= $data['chart_expense']; ?>;

const ctx = document.getElementById('myAreaChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar', // Bisa diganti 'line' kalau mau grafik garis
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
                label: 'Pemasukan',
                data: incomeData,
                backgroundColor: 'rgba(25, 135, 84, 0.7)', // Warna Hijau (Success)
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1
            },
            {
                label: 'Pengeluaran',
                data: expenseData,
                backgroundColor: 'rgba(220, 53, 69, 0.7)', // Warna Merah (Danger)
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    // Format mata uang Rupiah di sumbu Y
                    callback: function(value, index, values) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        }
    }
});
</script>