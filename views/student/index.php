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

    // Setup chart filter
    setupChartFilter();
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

// Document ready
$(document).ready(function() {
    adjustChartForMobile();
    initializeDataTableAndConfirm();
    setupChartFilter();
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