<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <!-- Header dengan Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= BASEURL; ?>/dashboard" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-child me-1"></i> <?= htmlspecialchars($data['child']['username']); ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASEURL; ?>/dashboard" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="<?= BASEURL; ?>/dashboard/export_child/<?= $data['child']['id']; ?>"
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

        <!-- Profile Card & Quick Stats -->
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <?php
                            // Foto profil anak
                            $childPhoto = $data['child']['photo'] ?? '';
                            $childUsername = htmlspecialchars($data['child']['username']);
                            
                            if (!empty($childPhoto)) {
                                $avatarUrl = BASEURL . '/img/profile/' . $childPhoto;
                                $fallbackAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($childUsername) . '&background=6366f1&color=fff&size=150';
                            } else {
                                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($childUsername) . '&background=6366f1&color=fff&size=150';
                                $fallbackAvatar = $avatarUrl;
                            }
                        ?>

                        <img src="<?= $avatarUrl; ?>" class="rounded-circle border mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;" alt="<?= $childUsername; ?>"
                            onerror="this.onerror=null; this.src='<?= $fallbackAvatar; ?>';">

                        <h4 class="fw-bold mb-2"><?= $childUsername; ?></h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($data['child']['email']); ?>
                        </p>

                        <div class="row g-2 text-start">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Bergabung</small>
                                    <strong><?= date('d M Y', strtotime($data['child']['created_at'])); ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Total Transaksi</small>
                                    <strong><?= ($data['summary']['income_count'] + $data['summary']['expense_count']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stats-card stats-balance h-100">
                            <div class="stats-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h5 class="text-muted mb-2">Saldo Tersedia</h5>
                            <h3 class="fw-bold mb-0">Rp <?= number_format($data['saldo'], 0, ',', '.'); ?></h3>
                            <small class="<?= $data['saldo'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                <i class="fas fa-<?= $data['saldo'] >= 0 ? 'arrow-up' : 'arrow-down'; ?> me-1"></i>
                                <?= $data['saldo'] >= 0 ? 'Positif' : 'Negatif'; ?>
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card stats-income h-100">
                            <div class="stats-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="text-muted mb-2">Total Pemasukan</h5>
                            <h3 class="fw-bold mb-0">Rp
                                <?= number_format($data['summary']['total_income'], 0, ',', '.'); ?></h3>
                            <small class="text-success">
                                <i class="fas fa-chart-bar me-1"></i>
                                <?= $data['summary']['income_count']; ?> transaksi
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card stats-expense h-100">
                            <div class="stats-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5 class="text-muted mb-2">Total Pengeluaran</h5>
                            <h3 class="fw-bold mb-0">Rp
                                <?= number_format($data['summary']['total_expense'], 0, ',', '.'); ?></h3>
                            <small class="text-danger">
                                <i class="fas fa-chart-line me-1"></i>
                                <?= $data['summary']['expense_count']; ?> transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Statistik Keuangan <?= date('Y'); ?>
                        </h5>
                    </div>
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="childChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Categories & Transaction List -->
        <div class="row mb-4">
            <!-- Top Expense Categories -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-fire me-2 text-danger"></i>Top Pengeluaran
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <?php if(empty($data['top_expense_categories'])) : ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada pengeluaran</p>
                        </div>
                        <?php else : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($data['top_expense_categories'] as $index => $category) : ?>
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="<?= $category['category_icon']; ?> fa-lg text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                <?= htmlspecialchars($category['category_name']); ?></h6>
                                            <small class="text-muted">
                                                <?= $category['transaction_count']; ?> transaksi
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="fw-bold text-danger mb-1">
                                            Rp <?= number_format($category['total_amount'], 0, ',', '.'); ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= round(($category['total_amount'] / $data['summary']['total_expense']) * 100, 1); ?>%
                                        </small>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-danger"
                                        style="width: <?= min(100, round(($category['total_amount'] / $data['summary']['total_expense']) * 100, 1)); ?>%">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-history me-2 text-primary"></i>Transaksi Terbaru
                            </h6>
                            <span class="badge bg-primary">
                                <?= count($data['transactions']); ?> transaksi
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="transactionsTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th class="text-end">Nominal</th>
                                        <th class="text-center">Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['transactions'])) : ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                                <p class="mb-0">Belum ada transaksi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php else : ?>
                                    <?php foreach($data['transactions'] as $trx) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="date-badge me-2 text-center">
                                                    <div class="fw-bold">
                                                        <?= date('d', strtotime($trx['transaction_date'])); ?></div>
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
                                                title="<?= htmlspecialchars($trx['description']); ?>">
                                                <?= htmlspecialchars($trx['description']); ?>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold">
                                            Rp <?= number_format($trx['amount'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="text-center">
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
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables if transactions exist
    if ($('#transactionsTable').length && <?= !empty($data['transactions']) ? 'true' : 'false'; ?>) {
        const table = $('#transactionsTable').DataTable({
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
                    targets: 1
                },
                {
                    responsivePriority: 5,
                    targets: 2
                }
            ],
            initComplete: function() {
                if (window.innerWidth <= 768) {
                    this.api().columns([1]).visible(false);
                }
            }
        });

        $(window).on('resize', function() {
            if (window.innerWidth <= 768) {
                table.column(1).visible(false);
            } else {
                table.column(1).visible(true);
            }
        });
    }

    // Initialize Chart
    if (document.getElementById('childChart')) {
        const incomeData = <?= $data['chart_income']; ?>;
        const expenseData = <?= $data['chart_expense']; ?>;

        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        const ctx = document.getElementById('childChart').getContext('2d');
        const childChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthNames,
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

        // Responsive adjustments for chart
        function adjustChartForMobile() {
            if (window.innerWidth <= 768) {
                childChart.options.plugins.legend.position = 'top';
                childChart.options.plugins.legend.labels.padding = 10;
                childChart.options.plugins.legend.labels.font.size = 11;
                childChart.options.scales.x.ticks.maxRotation = 45;
                childChart.options.scales.x.ticks.font.size = 10;
                childChart.options.scales.y.ticks.font.size = 10;
                childChart.update();
            } else {
                childChart.options.plugins.legend.position = 'top';
                childChart.options.plugins.legend.labels.padding = 15;
                childChart.options.plugins.legend.labels.font.size = 12;
                childChart.options.scales.x.ticks.maxRotation = 0;
                childChart.options.scales.x.ticks.font.size = 11;
                childChart.options.scales.y.ticks.font.size = 11;
                childChart.update();
            }
        }

        adjustChartForMobile();
        window.addEventListener('resize', adjustChartForMobile);
    }
});
</script>

<style>
/* Custom styles for child detail page */
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

/* Mobile adjustments */
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
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.5rem;
    }

    .chart-area {
        height: 250px;
    }
}

@media (max-width: 576px) {
    .chart-area {
        height: 200px;
    }

    .table th,
    .table td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }

    .stats-card {
        padding: 1rem !important;
    }

    .stats-card h3 {
        font-size: 1.25rem;
    }
}

/* Badge styles */
.badge-income {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.badge-expense {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

/* List group customization */
.list-group-item {
    border: none;
    border-bottom: 1px solid var(--border-light) !important;
}

.list-group-item:last-child {
    border-bottom: none !important;
}

.progress {
    background-color: var(--bg-tertiary);
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

/* Breadcrumb customization */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: var(--primary);
    transition: color 0.2s;
}

.breadcrumb-item a:hover {
    color: var(--primary-dark);
}

.breadcrumb-item.active {
    color: var(--text-secondary);
}
</style>