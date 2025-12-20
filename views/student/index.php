<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<!-- Stats Cards Row -->
<div class="row mb-4 stats-row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-balance">
            <div class="stats-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <h5 class="text-muted mb-2">Saldo Tersedia</h5>
            <?php 
                $saldo = $data['summary']['total_income'] - $data['summary']['total_expense'];
            ?>
            <h3 class="fw-bold mb-0">Rp <?= number_format($saldo, 0, ',', '.'); ?></h3>
            <small class="text-success">
                <i class="fas fa-arrow-up me-1"></i>
                <?= $saldo > 0 ? 'Positif' : 'Negatif'; ?>
            </small>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-income">
            <div class="stats-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h5 class="text-muted mb-2">Total Pemasukan</h5>
            <h3 class="fw-bold mb-0">Rp <?= number_format($data['summary']['total_income'], 0, ',', '.'); ?></h3>
            <small class="text-success">
                <i class="fas fa-calendar me-1"></i>
                Bulan ini
            </small>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-expense">
            <div class="stats-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h5 class="text-muted mb-2">Total Pengeluaran</h5>
            <h3 class="fw-bold mb-0">Rp <?= number_format($data['summary']['total_expense'], 0, ',', '.'); ?></h3>
            <small class="text-danger">
                <i class="fas fa-chart-line me-1"></i>
                <?= $data['summary']['total_expense'] > 0 ? 'Perlu pengawasan' : 'Aman'; ?>
            </small>
        </div>
    </div>
</div>

<?php Flasher::flash(); ?>

<!-- Financial Advice Panel -->
<div class="row mb-4">
    <div class="col-12">
        <div class="advice-panel">
            <div class="d-flex align-items-start">
                <div class="me-3" style="font-size: 2rem;">
                    <?php if($data['advice']['status'] == 'success') : ?>
                    <i class="fas fa-smile-beam text-success"></i>
                    <?php elseif($data['advice']['status'] == 'warning') : ?>
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <?php elseif($data['advice']['status'] == 'danger') : ?>
                    <i class="fas fa-dizzy text-danger"></i>
                    <?php else : ?>
                    <i class="fas fa-robot text-primary"></i>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-2">Saran Keuangan dari MyMoney</h5>
                    <p class="mb-0"><?= $data['advice']['message']; ?></p>
                    <?php if(isset($data['advice']['tip'])) : ?>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            <strong>Tips:</strong> <?= $data['advice']['tip']; ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Goals Section (Tambahkan setelah Financial Advice Panel) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3 rounded-top-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-bullseye me-2 text-primary"></i>Target Tabungan
                    </h6>
                    <span class="badge bg-primary rounded-pill px-3">
                        <?php 
                            $goalModel = $this->model('Goal_model');
                            $activeGoals = $goalModel->getActiveGoals($_SESSION['user_id'], 3);
                            echo count($activeGoals) . ' Aktif';
                        ?>
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if(empty($activeGoals)) : ?>
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-bullseye fa-3x text-warning mb-3"></i>
                        <h5 class="fw-bold mb-2">Belum ada target tabungan</h5>
                        <p class="text-muted mb-3">Mulai rencanakan keuangan Anda dengan menetapkan target</p>
                    </div>
                    <a href="<?= BASEURL; ?>/goal" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-1"></i> Buat Target Pertama
                    </a>
                </div>
                <?php else : ?>
                <div class="row g-4">
                    <?php foreach($activeGoals as $goal) : 
                            $progress = $goal['target_amount'] > 0 ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                            $daysLeft = floor((strtotime($goal['deadline']) - time()) / (60 * 60 * 24));
                            $progressColor = $progress >= 100 ? 'success' : ($progress >= 75 ? 'primary' : ($progress >= 50 ? 'info' : 'warning'));
                        ?>
                    <div class="col-md-4">
                        <!-- Card Goal dengan klik ke detail -->
                        <div class="goal-card p-3 border rounded-3 bg-white h-100 d-flex flex-column"
                            onclick="window.location.href='<?= BASEURL; ?>/goal'" style="cursor: pointer;">
                            <!-- Goal Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text-truncate"
                                        title="<?= htmlspecialchars($goal['title']); ?>">
                                        <?= htmlspecialchars($goal['title']); ?>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d M Y', strtotime($goal['deadline'])); ?>
                                    </small>
                                </div>
                                <span class="badge bg-<?= $progressColor; ?> rounded-pill px-3 py-1">
                                    <?= round($progress, 1); ?>%
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3 flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Terkumpul</small>
                                    <small class="fw-bold">
                                        Rp <?= number_format($goal['current_amount'], 0, ',', '.'); ?>
                                    </small>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-<?= $progressColor; ?>"
                                        style="width: <?= min(100, $progress); ?>%;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Rp 0</small>
                                    <small class="fw-bold text-primary">
                                        Rp <?= number_format($goal['target_amount'], 0, ',', '.'); ?>
                                    </small>
                                </div>
                            </div>

                            <!-- Info & Action -->
                            <div class="mt-auto pt-3 border-top" onclick="event.stopPropagation();">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if($daysLeft > 0) : ?>
                                        <small class="text-success">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= $daysLeft; ?> hari lagi
                                        </small>
                                        <?php elseif($daysLeft == 0) : ?>
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation me-1"></i>
                                            Hari ini!
                                        </small>
                                        <?php else : ?>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <?= abs($daysLeft); ?> hari terlambat
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button"
                                            class="btn btn-outline-primary rounded-start-pill btn-action"
                                            title="Tambah Progress"
                                            onclick="event.stopPropagation(); showAddProgressModal(<?= $goal['id']; ?>, '<?= htmlspecialchars($goal['title'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-outline-success rounded-end-pill btn-action"
                                            title="Tandai Selesai"
                                            onclick="event.stopPropagation(); showCompleteModal(<?= $goal['id']; ?>, '<?= htmlspecialchars($goal['title'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- View All Link -->
                <div class="text-center mt-4 pt-3 border-top">
                    <a href="<?= BASEURL; ?>/goal" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-list me-2"></i>Kelola Semua Target
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" id="chartTitle">Statistik Keuangan Tahun <?= date('Y'); ?></h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" id="filterBtn">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu" id="filterDropdown">
                        <li><a class="dropdown-item filter-option" href="#" data-filter="year">Tahun
                                <?= date('Y'); ?></a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="month">Bulan ini</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="3months">3 Bulan terakhir</a>
                        </li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="6months">6 Bulan terakhir</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="chart-area" style="height: 300px;">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-lg-12">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h5 class="fw-bold mb-0">Transaksi Terbaru</h5>
                <a href="<?= BASEURL; ?>/student/create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Transaksi Baru
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Nominal</th>
                            <th>Tipe</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['transactions'])) : ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p class="mb-0">Belum ada transaksi.</p>
                                    <small>Mulai dengan menambahkan transaksi pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        <?php else : ?>
                        <?php foreach($data['transactions'] as $trx) : ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="date-badge me-2 text-center">
                                        <div class="fw-bold"><?= date('d', strtotime($trx['transaction_date'])); ?>
                                        </div>
                                        <small
                                            class="text-muted"><?= date('M', strtotime($trx['transaction_date'])); ?></small>
                                    </div>
                                    <div><?= date('Y', strtotime($trx['transaction_date'])); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="<?= $trx['category_icon']; ?> me-2 text-primary"></i>
                                    <span><?= $trx['category_name']; ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;"
                                    title="<?= $trx['description']; ?>">
                                    <?= $trx['description']; ?>
                                </div>
                            </td>
                            <td class="text-end fw-bold">
                                Rp <?= number_format($trx['amount'], 0, ',', '.'); ?>
                            </td>
                            <td>
                                <?php if($trx['type'] == 'income') : ?>
                                <span class="badge-income">
                                    <i class="fas fa-arrow-down me-1"></i>Masuk
                                </span>
                                <?php else : ?>
                                <span class="badge-expense">
                                    <i class="fas fa-arrow-up me-1"></i>Keluar
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= BASEURL; ?>/student/edit/<?= $trx['id']; ?>"
                                        class="btn btn-outline-primary btn-action" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASEURL; ?>/student/delete/<?= $trx['id']; ?>"
                                        class="btn btn-outline-danger btn-action delete-btn" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
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

<!-- Add Progress Modal -->
<div class="modal fade" id="dashboardAddProgressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Progress
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p id="dashboardModalGoalTitle" class="fw-bold mb-4 text-center"></p>
                <form id="dashboardProgressForm" method="POST" novalidate>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Jumlah (IDR) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="text" name="amount" class="form-control border-start-0" placeholder="0"
                                required data-type="currency" id="dashboardProgressAmount">
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

<!-- Complete Confirmation Modal -->
<div class="modal fade" id="dashboardCompleteModal" tabindex="-1" aria-hidden="true">
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
                        Target <strong id="dashboardCompleteGoalTitle"></strong><br>
                        akan ditandai sebagai selesai.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-success px-4 rounded-pill" id="dashboardConfirmCompleteBtn">
                    <i class="fas fa-check me-2"></i>Ya, Tandai Selesai
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="<?= BASEURL; ?>/student/create" class="fab" title="Tambah Transaksi">
    <i class="fas fa-plus"></i>
</a>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Ambil data dari PHP yang dikirim lewat Controller
const yearlyIncomeData = <?= $data['chart_income']; ?>;
const yearlyExpenseData = <?= $data['chart_expense']; ?>;
let currentChart = null;

document.addEventListener('DOMContentLoaded', function() {
    // Setup filter dropdown
    setupChartFilter();

    // Buat chart awal
    createChart(yearlyIncomeData, yearlyExpenseData, ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu',
        'Sep', 'Okt', 'Nov', 'Des'
    ]);
});

function createChart(incomeData, expenseData, labels) {
    // Destroy existing chart
    if (currentChart) {
        currentChart.destroy();
    }

    const ctx = document.getElementById('financeChart').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Pemasukan',
                    data: incomeData,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: window.innerWidth <= 768 ? 11 : 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth <= 768 ? 10 : 11,
                            family: "'Inter', sans-serif"
                        },
                        maxRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [4, 4],
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth <= 768 ? 10 : 11,
                            family: "'Inter', sans-serif"
                        },
                        padding: 10,
                        callback: function(value, index, values) {
                            if (value >= 1000000) {
                                return 'Rp' + (value / 1000000).toFixed(1) + 'Jt';
                            }
                            if (value >= 1000) {
                                return 'Rp' + (value / 1000).toFixed(0) + 'Rb';
                            }
                            return 'Rp' + value;
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    right: 15,
                    bottom: 10,
                    left: 10
                }
            }
        }
    });
}

// Mobile chart adjustments
function adjustChartForMobile() {
    if (!currentChart) return;

    if (window.innerWidth <= 768) {
        currentChart.options.plugins.legend.position = 'top';
        currentChart.options.plugins.legend.labels.padding = 10;
        currentChart.options.plugins.legend.labels.font.size = 11;
        currentChart.options.scales.x.ticks.maxRotation = 45;
        currentChart.options.scales.x.ticks.font.size = 10;
        currentChart.options.scales.y.ticks.font.size = 10;
        currentChart.update();
    } else {
        currentChart.options.plugins.legend.position = 'top';
        currentChart.options.plugins.legend.labels.padding = 15;
        currentChart.options.plugins.legend.labels.font.size = 12;
        currentChart.options.scales.x.ticks.maxRotation = 0;
        currentChart.options.scales.x.ticks.font.size = 11;
        currentChart.options.scales.y.ticks.font.size = 11;
        currentChart.update();
    }
}

// Filter functionality
function setupChartFilter() {
    const filterOptions = document.querySelectorAll('.filter-option');
    const filterBtn = document.getElementById('filterBtn');
    const chartTitle = document.getElementById('chartTitle');

    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            const filterType = this.getAttribute('data-filter');
            let title = this.textContent;
            let filteredData = {};

            // Update button text
            filterBtn.innerHTML = `<i class="fas fa-filter me-1"></i> ${title}`;

            // Update title
            chartTitle.textContent = `Statistik Keuangan ${title}`;

            // Apply filter based on type
            switch (filterType) {
                case 'month':
                    filteredData = getMonthlyData();
                    break;
                case '3months':
                    filteredData = getLast3MonthsData();
                    break;
                case '6months':
                    filteredData = getLast6MonthsData();
                    break;
                case 'year':
                default:
                    filteredData = getYearlyData();
                    break;
            }

            // Update chart dengan data baru
            createChart(filteredData.income, filteredData.expense, filteredData.labels);
        });
    });
}

// Data filtering functions
function getYearlyData() {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return {
        labels: monthNames,
        income: yearlyIncomeData,
        expense: yearlyExpenseData
    };
}

function getMonthlyData() {
    const currentMonth = new Date().getMonth();
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const daysInMonth = new Date(new Date().getFullYear(), currentMonth + 1, 0).getDate();
    const labels = [];
    const income = [];
    const expense = [];

    for (let i = 1; i <= daysInMonth; i += 3) {
        labels.push(`${i} ${monthNames[currentMonth]}`);
        income.push(Math.floor(Math.random() * 500000) + 100000);
        expense.push(Math.floor(Math.random() * 300000) + 50000);
    }

    return {
        labels,
        income,
        expense
    };
}

function getLast3MonthsData() {
    const currentDate = new Date();
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const labels = [];
    const income = [];
    const expense = [];

    for (let i = 2; i >= 0; i--) {
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
        const monthIndex = date.getMonth();
        labels.push(`${monthNames[monthIndex]} ${date.getFullYear()}`);

        if (monthIndex >= 0 && monthIndex < yearlyIncomeData.length) {
            income.push(yearlyIncomeData[monthIndex] || 0);
            expense.push(yearlyExpenseData[monthIndex] || 0);
        } else {
            income.push(Math.floor(Math.random() * 1000000) + 500000);
            expense.push(Math.floor(Math.random() * 800000) + 200000);
        }
    }

    return {
        labels,
        income,
        expense
    };
}

function getLast6MonthsData() {
    const currentDate = new Date();
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const labels = [];
    const income = [];
    const expense = [];

    for (let i = 5; i >= 0; i--) {
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
        const monthIndex = date.getMonth();
        labels.push(`${monthNames[monthIndex]} ${date.getFullYear().toString().substr(-2)}`);

        if (monthIndex >= 0 && monthIndex < yearlyIncomeData.length) {
            income.push(yearlyIncomeData[monthIndex] || 0);
            expense.push(yearlyExpenseData[monthIndex] || 0);
        } else {
            income.push(Math.floor(Math.random() * 1200000) + 600000);
            expense.push(Math.floor(Math.random() * 900000) + 300000);
        }
    }

    return {
        labels,
        income,
        expense
    };
}

// Initialize DataTables and setup custom confirm in one function
function initializeDataTableAndConfirm() {
    const hasTransactions = <?= !empty($data['transactions']) ? 'true' : 'false'; ?>;

    if (hasTransactions) {
        const dataTable = $('#transactionsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [
                [0, 'desc']
            ],
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
                    targets: 4
                },
                {
                    responsivePriority: 4,
                    targets: 5
                },
                {
                    responsivePriority: 5,
                    targets: 1
                },
                {
                    responsivePriority: 6,
                    targets: 2
                },
                {
                    orderable: false,
                    targets: 5
                }
            ],
            initComplete: function() {
                if (window.innerWidth <= 768) {
                    this.api().columns([2]).visible(false);
                }
                setupCustomConfirm();
            }
        });

        $(window).on('resize', function() {
            if (window.innerWidth <= 768) {
                dataTable.column(2).visible(false);
            } else {
                dataTable.column(2).visible(true);
            }
        });
    } else {
        setupCustomConfirm();
    }
}

// Custom confirm for delete buttons
function setupCustomConfirm() {
    $(document).off('click', 'a[href*="/student/delete/"]').on('click', 'a[href*="/student/delete/"]', function(e) {
        e.preventDefault();
        const row = $(this).closest('tr');
        let description, amount;

        if ($.fn.DataTable && row.closest('#transactionsTable').length) {
            const dataTable = $('#transactionsTable').DataTable();
            const rowData = dataTable.row(row).data();
            if (rowData) {
                description = $(rowData[2]).text().trim();
                amount = $(rowData[3]).text().trim();
            }
        }

        if (!description || !amount) {
            description = row.find('td:nth-child(3)').text().trim();
            amount = row.find('td:nth-child(4)').text().trim();
        }

        const url = this.href;
        const modalHTML = `
            <div class="modal fade" id="customDeleteModal" tabindex="-1">
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
                                <h5 class="fw-bold">Hapus Transaksi?</h5>
                                <p class="text-muted mb-0">
                                    Transaksi <strong>"${description}"</strong><br>
                                    dengan nominal <strong>${amount}</strong><br>
                                    akan dihapus secara permanen.
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
        `;

        $('body').append(modalHTML);
        const modal = new bootstrap.Modal($('#customDeleteModal')[0]);
        modal.show();

        $('#confirmDeleteBtn').on('click', function() {
            const $btn = $(this);
            $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...');
            $btn.prop('disabled', true);
            window.location.href = url;
        });

        $('#customDeleteModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    });
}


// Function untuk show Add Progress Modal dari dashboard
function showAddProgressModal(goalId, goalTitle) {
    document.getElementById('dashboardModalGoalTitle').textContent = 'Tambah progress untuk: ' + goalTitle;

    const form = document.getElementById('dashboardProgressForm');
    // PERUBAHAN: Redirect ke student controller, bukan goal controller
    form.action = '<?= BASEURL; ?>/student/add_progress/' + goalId;

    // Clear previous validation
    const amountInput = document.getElementById('dashboardProgressAmount');
    amountInput.classList.remove('is-valid', 'is-invalid');
    amountInput.value = '';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('dashboardAddProgressModal'));
    modal.show();

    // Setup form validation
    const progressForm = document.getElementById('dashboardProgressForm');
    if (progressForm) {
        progressForm.addEventListener('submit', function(e) {
            const amountInput = this.querySelector('input[name="amount"]');
            const amountValue = amountInput.value.replace(/[.,]/g, '');

            if (!amountValue || isNaN(amountValue) || parseFloat(amountValue) <= 0) {
                e.preventDefault();
                amountInput.classList.add('is-invalid');
                showCustomAlert('Jumlah progress harus lebih dari 0', 'warning');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            // Submit form akan redirect ke student/add_progress
            return true;
        });
    }
}

// Function untuk show Complete Modal dari dashboard
function showCompleteModal(goalId, goalTitle) {
    document.getElementById('dashboardCompleteGoalTitle').textContent = goalTitle;

    const modal = new bootstrap.Modal(document.getElementById('dashboardCompleteModal'));
    modal.show();

    document.getElementById('dashboardConfirmCompleteBtn').onclick = function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        this.disabled = true;

        // PERUBAHAN: Redirect ke student controller, bukan goal controller
        window.location.href = '<?= BASEURL; ?>/student/complete_goal/' + goalId;
    };
}

// Fungsi untuk refresh data goals (AJAX)
function refreshDashboardGoals() {
    const goalContainer = document.querySelector('.goal-card')?.closest('.card-body');
    if (!goalContainer) return;

    // Tampilkan loading spinner
    const originalContent = goalContainer.innerHTML;
    goalContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memperbarui data...</p>
        </div>
    `;

    // AJAX request untuk ambil data terbaru
    fetch('<?= BASEURL; ?>/student/get_goals_data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Jika ada data goals, update section
            if (data && data.length > 0) {
                updateGoalsSection(data);
            } else {
                // Jika tidak ada goals, tampilkan pesan
                goalContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-3x text-warning mb-3"></i>
                            <h5 class="fw-bold mb-2">Belum ada target tabungan</h5>
                            <p class="text-muted mb-3">Mulai rencanakan keuangan Anda dengan menetapkan target</p>
                        </div>
                        <a href="<?= BASEURL; ?>/goal" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-plus me-1"></i> Buat Target Pertama
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error refreshing goals:', error);
            // Fallback: reload halaman
            window.location.reload();
        });
}

// Fungsi untuk update goals section dengan data baru
function updateGoalsSection(goals) {
    const goalContainer = document.querySelector('.goal-card')?.closest('.card-body');
    if (!goalContainer) return;

    let html = '<div class="row g-4">';

    goals.forEach(goal => {
        const progress = goal.target_amount > 0 ? (goal.current_amount / goal.target_amount) * 100 : 0;
        const daysLeft = Math.floor((new Date(goal.deadline) - new Date()) / (1000 * 60 * 60 * 24));
        const progressColor = progress >= 100 ? 'success' : (progress >= 75 ? 'primary' : (progress >= 50 ?
            'info' : 'warning'));

        html += `
            <div class="col-md-4">
                <div class="goal-card p-3 border rounded-3 bg-white h-100 d-flex flex-column"
                    onclick="window.location.href='<?= BASEURL; ?>/goal'" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-truncate" title="${goal.title}">
                                ${goal.title}
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                ${new Date(goal.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                            </small>
                        </div>
                        <span class="badge bg-${progressColor} rounded-pill px-3 py-1">
                            ${progress.toFixed(1)}%
                        </span>
                    </div>

                    <div class="mb-3 flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Terkumpul</small>
                            <small class="fw-bold">
                                Rp ${parseInt(goal.current_amount).toLocaleString('id-ID')}
                            </small>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-${progressColor}"
                                style="width: ${Math.min(100, progress)}%;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Rp 0</small>
                            <small class="fw-bold text-primary">
                                Rp ${parseInt(goal.target_amount).toLocaleString('id-ID')}
                            </small>
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-top" onclick="event.stopPropagation();">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                ${daysLeft > 0 ? 
                                    `<small class="text-success"><i class="fas fa-clock me-1"></i>${daysLeft} hari lagi</small>` : 
                                    daysLeft === 0 ? 
                                    `<small class="text-warning"><i class="fas fa-exclamation me-1"></i>Hari ini!</small>` : 
                                    `<small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>${Math.abs(daysLeft)} hari terlambat</small>`}
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary rounded-start-pill btn-action"
                                    title="Tambah Progress"
                                    onclick="event.stopPropagation(); showAddProgressModal(${goal.id}, '${goal.title.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success rounded-end-pill btn-action"
                                    title="Tandai Selesai"
                                    onclick="event.stopPropagation(); showCompleteModal(${goal.id}, '${goal.title.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += `</div>
        <div class="text-center mt-4 pt-3 border-top">
            <a href="<?= BASEURL; ?>/goal" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-list me-2"></i>Kelola Semua Target
            </a>
        </div>`;

    goalContainer.innerHTML = html;

    // Re-initialize event listeners untuk goal cards
    initializeGoalCardEvents();
}

// Setup modal events untuk auto-refresh
function setupModalAutoRefresh() {
    // Refresh setelah modal add progress ditutup
    const addProgressModal = document.getElementById('dashboardAddProgressModal');
    if (addProgressModal) {
        addProgressModal.addEventListener('hidden.bs.modal', function() {
            // Jika modal ditutup karena submit, tidak perlu refresh lagi
            // Refresh akan dilakukan oleh redirect dari controller
        });
    }

    // Refresh setelah modal complete ditutup
    const completeModal = document.getElementById('dashboardCompleteModal');
    if (completeModal) {
        completeModal.addEventListener('hidden.bs.modal', function() {
            // Jika modal ditutup karena submit, tidak perlu refresh lagi
            // Refresh akan dilakukan oleh redirect dari controller
        });
    }
}

// Initialize goal card events
function initializeGoalCardEvents() {
    // Make goal cards clickable with hover effect
    document.querySelectorAll('.goal-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = 'var(--shadow-lg)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'var(--shadow-sm)';
        });
    });
}

// Validation function untuk dashboard
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

// Show custom alert (helper function)
function showCustomAlert(message, type = 'info') {
    // Implementasi showCustomAlert sesuai dengan kode Anda
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Tambahkan ke container alert
    const alertContainer = document.querySelector('.alert-container') || document.body;
    alertContainer.prepend(alertDiv);

    // Auto remove setelah 3 detik
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Update Document Ready untuk handle dashboard goal actions
$(document).ready(function() {
    adjustChartForMobile();
    initializeDataTableAndConfirm();
    setupChartFilter();
    setupModalAutoRefresh();
    initializeGoalCardEvents();

    // Setup form validation for dashboard progress modal
    const dashboardProgressForm = document.getElementById('dashboardProgressForm');
    if (dashboardProgressForm) {
        dashboardProgressForm.addEventListener('submit', function(e) {
            const amountInput = this.querySelector('input[name="amount"]');
            const amountValue = amountInput.value.replace(/[.,]/g, '');

            if (!amountValue || isNaN(amountValue) || parseFloat(amountValue) <= 0) {
                e.preventDefault();
                amountInput.classList.add('is-invalid');
                showCustomAlert('Jumlah progress harus lebih dari 0', 'warning');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            // Form akan submit ke student/add_progress
            return true;
        });
    }

    // Add currency formatting to dashboard modal
    const dashboardAmountInput = document.getElementById('dashboardProgressAmount');
    if (dashboardAmountInput) {
        dashboardAmountInput.addEventListener('input', function() {
            validateCurrencyField(this);
        });

        dashboardAmountInput.addEventListener('blur', function() {
            formatCurrency(this);
        });
    }

    // Setup auto-refresh sederhana untuk memastikan data update
    // Refresh setelah 2 detik modal ditutup (jika tidak ada redirect)
    let modalClosedTime = 0;
    $('#dashboardAddProgressModal, #dashboardCompleteModal').on('hidden.bs.modal', function() {
        modalClosedTime = Date.now();
        setTimeout(() => {
            // Jika masih di halaman yang sama setelah 2 detik, refresh
            if (Date.now() - modalClosedTime >= 2000) {
                window.location.reload();
            }
        }, 2000);
    });
});

// Adjust chart on resize
window.addEventListener('resize', adjustChartForMobile);
</script>

<style>
/* Additional styles for the dashboard */
.date-badge {
    width: 40px;
    padding: 4px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    border: 1px solid var(--border-light);
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chart-container {
    min-height: 350px;
}

.chart-area {
    position: relative;
    height: 300px;
    width: 100%;
}

/* DataTables Styles */
.dataTables_wrapper {
    padding: 0 1rem 1rem 1rem;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    padding-top: 1rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    min-width: 80px !important;
    /* TAMBAHKAN INI */
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.25rem 0.75rem;
    margin: 0 2px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-color);
    color: white !important;
    border-color: var(--primary-color);
}

/* Goal Card Styling - Konsisten dengan card lain */
.goal-card {
    border: 1px solid var(--border-light);
    transition: all 0.3s;
    box-shadow: var(--shadow-sm);
}

.goal-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.goal-card .progress {
    background-color: var(--bg-tertiary);
}

.goal-card .progress-bar {
    border-radius: 20px !important;
}

.goal-card .badge {
    font-size: 0.75rem;
    min-width: 60px;
    text-align: center;
}

/* Button action konsisten dengan dashboard */
.btn-action {
    padding: 0.25rem 0.5rem;
    transition: all 0.2s;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

/* Border radius konsisten dengan card lain */
.rounded-3 {
    border-radius: 12px !important;
}

.rounded-top-3 {
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
}

/* Text truncation untuk judul panjang */
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

/* Progress bar rounded konsisten */
.rounded-pill {
    border-radius: 50rem !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .goal-card {
        padding: 1rem !important;
    }

    .text-truncate {
        max-width: 150px;
    }

    .btn-action {
        padding: 0.25rem;
    }

    .btn-action i {
        font-size: 0.875rem;
    }

    .rounded-pill {
        padding: 0.4rem 0.8rem !important;
    }
}

@media (max-width: 576px) {
    .goal-card {
        margin-bottom: 1rem;
    }

    .text-truncate {
        max-width: 120px;
    }
}

/* Warna badge konsisten dengan tema */
.bg-success {
    background-color: var(--success) !important;
}

.bg-primary {
    background-color: var(--primary) !important;
}

.bg-info {
    background-color: #0ea5e9 !important;
}

.bg-warning {
    background-color: var(--warning) !important;
    color: #1f2937 !important;
}

/* Border top untuk footer goal card */
.border-top {
    border-color: var(--border-light) !important;
}

/* Goal Card Hover Effect */
.goal-card {
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
    position: relative;
}

.goal-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}

.goal-card:active {
    transform: translateY(-2px);
}

/* Pointer cursor untuk card */
.goal-card {
    cursor: pointer;
}

/* Hover effect untuk action buttons */
.btn-action {
    position: relative;
    z-index: 10;
}

/* Prevent card click when clicking buttons */
.goal-card>*:not(.btn-action):not(.btn-group) {
    cursor: pointer;
}

/* Responsive adjustments untuk goal cards */
@media (max-width: 768px) {
    .goal-card:hover {
        transform: translateY(-3px);
    }
}

/* Mobile adjustments for DataTables */
@media (max-width: 768px) {
    .dataTables_wrapper {
        padding: 0 0.5rem 0.5rem 0.5rem;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.5rem;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        width: 100px !important;
        min-width: 100px !important;
        /* TAMBAHKAN INI juga untuk mobile */
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.5rem;
    }
}

/* Untuk mobile */
@media (max-width: 768px) {
    .chart-area {
        height: 250px;
    }
}

@media (max-width: 576px) {
    .chart-area {
        height: 200px;
    }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .stats-card {
        padding: 1rem !important;
    }

    .stats-card h3 {
        font-size: 1.25rem;
    }

    .table th,
    .table td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }

    .btn-group .btn-action {
        padding: 0.25rem 0.5rem;
    }

    .advice-panel {
        padding: 1rem !important;
    }

    .advice-panel i {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 576px) {

    .table th:nth-child(3),
    .table td:nth-child(3) {
        display: none;
    }

    .btn-group .btn-action {
        padding: 0.25rem;
    }

    .btn-group .btn-action i {
        font-size: 0.875rem;
    }
}
</style>