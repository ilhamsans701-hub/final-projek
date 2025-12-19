<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<!-- Stats Cards Row -->
<div class="row mb-4 stats-row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-balance">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <h5 class="text-muted mb-2">Anak Terhubung</h5>
            <h3 class="fw-bold mb-0"><?= count($data['children_data'] ?? []); ?> Anak</h3>
            <small class="text-primary">
                <i class="fas fa-link me-1"></i>
                <?= count($data['children_data'] ?? []) > 0 ? 'Aktif' : 'Belum Ada'; ?>
            </small>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-income">
            <div class="stats-icon">
                <i class="fas fa-arrow-up"></i>
            </div>
            <h5 class="text-muted mb-2">Total Pemasukan Anak</h5>
            <?php 
                $totalIncome = array_sum(array_column($data['children_data'] ?? [], 'income'));
            ?>
            <h3 class="fw-bold mb-0">Rp <?= number_format($totalIncome, 0, ',', '.'); ?></h3>
            <small class="text-success">
                <i class="fas fa-calendar me-1"></i>
                Bulan ini
            </small>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card stats-expense">
            <div class="stats-icon">
                <i class="fas fa-arrow-down"></i>
            </div>
            <h5 class="text-muted mb-2">Total Pengeluaran Anak</h5>
            <?php 
                $totalExpense = array_sum(array_column($data['children_data'] ?? [], 'expense'));
            ?>
            <h3 class="fw-bold mb-0">Rp <?= number_format($totalExpense, 0, ',', '.'); ?></h3>
            <small class="text-danger">
                <i class="fas fa-chart-line me-1"></i>
                <?= $totalExpense > $totalIncome ? 'Perlu perhatian' : 'Aman'; ?>
            </small>
        </div>
    </div>
</div>

<!-- Family Code Panel -->
<div class="row mb-4">
    <div class="col-12">
        <div class="advice-panel">
            <div class="d-flex align-items-start">
                <div class="me-3" style="font-size: 2rem;">
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-2">Kode Keluarga Anda</h5>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="family-code-box p-3 rounded bg-primary bg-opacity-10 border border-primary">
                            <code class="fs-4 fw-bold text-primary">
                                <?= htmlspecialchars($data['family_code'] ?? 'BELUM ADA'); ?>
                            </code>
                        </div>
                        <button class="btn btn-primary btn-copy"
                            data-code="<?= htmlspecialchars($data['family_code'] ?? ''); ?>">
                            <i class="fas fa-copy me-2"></i>Salin
                        </button>
                    </div>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Berikan kode ini kepada anak Anda agar akun mereka terhubung dengan panel ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Children Monitoring Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-child me-2 text-primary"></i>Monitoring Keuangan Anak
                    <span class="badge bg-primary ms-2"><?= count($data['children_data'] ?? []); ?> Anak</span>
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Semua Anak</a></li>
                        <li><a class="dropdown-item" href="#">Aktif Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Ada Transaksi</a></li>
                    </ul>
                </div>
            </div>

            <?php if(empty($data['children_data'])) : ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold mb-3">Belum ada anak yang terhubung</h5>
                    <p class="text-muted mb-4">
                        Salin Kode Keluarga di atas dan berikan kepada anak Anda untuk terhubung.
                    </p>
                </div>
            </div>
            <?php else : ?>
            <!-- Children Cards -->
            <div class="row g-3">
                <?php foreach($data['children_data'] as $child) : ?>
                <div class="col-lg-6">
                    <div class="child-card p-4 border rounded-3 bg-white">
                        <!-- Child Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="child-avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-child fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($child['info']['username']); ?></h6>
                                    <small class="text-muted">
                                        Bergabung:
                                        <?= date('d M Y', strtotime($child['info']['created_at'] ?? 'now')); ?>
                                    </small>
                                </div>
                            </div>
                            <span class="badge-income">
                                <i class="fas fa-wallet me-1"></i>
                                Rp <?= number_format($child['saldo'] ?? 0, 0, ',', '.'); ?>
                            </span>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <small class="text-muted d-block mb-1">Pemasukan</small>
                                    <h6 class="fw-bold text-success mb-0">
                                        Rp <?= number_format($child['income'] ?? 0, 0, ',', '.'); ?>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <small class="text-muted d-block mb-1">Pengeluaran</small>
                                    <h6 class="fw-bold text-danger mb-0">
                                        Rp <?= number_format($child['expense'] ?? 0, 0, ',', '.'); ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <h6 class="fw-bold mb-2">Transaksi Terbaru</h6>
                        <?php if(empty($child['transactions'])) : ?>
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-lg text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada transaksi</p>
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($child['transactions'] as $t) : ?>
                                    <tr>
                                        <td>
                                            <div class="small">
                                                <?= date('d/m', strtotime($t['transaction_date'])); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 120px;"
                                                title="<?= htmlspecialchars($t['description']); ?>">
                                                <?= htmlspecialchars(substr($t['description'], 0, 20)); ?>
                                                <?= strlen($t['description']) > 20 ? '...' : ''; ?>
                                            </div>
                                        </td>
                                        <td
                                            class="text-end fw-bold <?= ($t['type'] == 'expense') ? 'text-danger' : 'text-success'; ?>">
                                            <?= ($t['type'] == 'expense') ? '-' : '+'; ?>
                                            Rp <?= number_format($t['amount'], 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Update: <?= date('H:i'); ?>
                            </small>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-chart-line me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart Section (Optional - Monthly Overview) -->
<?php if(!empty($data['children_data'])) : ?>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Ringkasan Bulanan <?= date('F Y'); ?></h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-1"></i> <?= date('F Y'); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Januari 2024</a></li>
                        <li><a class="dropdown-item" href="#">Desember 2023</a></li>
                        <li><a class="dropdown-item" href="#">November 2023</a></li>
                    </ul>
                </div>
            </div>
            <div class="chart-area" style="height: 250px;">
                <canvas id="parentChart"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Floating Action Button -->
<button class="fab" title="Tambah Anak" id="addChildBtn">
    <i class="fas fa-user-plus"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare chart data
const childNames = <?= json_encode(array_column($data['children_data'] ?? [], 'info.username')); ?>;
const childIncomes = <?= json_encode(array_column($data['children_data'] ?? [], 'income')); ?>;
const childExpenses = <?= json_encode(array_column($data['children_data'] ?? [], 'expense')); ?>;

// Parent Chart
if (document.getElementById('parentChart') && childNames.length > 0) {
    const ctx = document.getElementById('parentChart').getContext('2d');
    const parentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: childNames,
            datasets: [{
                    label: 'Pemasukan',
                    data: childIncomes,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Pengeluaran',
                    data: childExpenses,
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
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleFont: {
                        family: "'Inter', sans-serif"
                    },
                    bodyFont: {
                        family: "'Inter', sans-serif"
                    },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
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
                        maxRotation: window.innerWidth <= 768 ? 45 : 0
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
                        callback: function(value) {
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
            }
        }
    });

    // Mobile adjustments
    function adjustParentChart() {
        if (window.innerWidth <= 768) {
            parentChart.options.plugins.legend.position = 'top';
            parentChart.options.plugins.legend.labels.padding = 10;
            parentChart.options.plugins.legend.labels.font.size = 11;
            parentChart.options.scales.x.ticks.maxRotation = 45;
            parentChart.options.scales.x.ticks.font.size = 10;
            parentChart.options.scales.y.ticks.font.size = 10;
            parentChart.update();
        } else {
            parentChart.options.plugins.legend.position = 'top';
            parentChart.options.plugins.legend.labels.padding = 15;
            parentChart.options.plugins.legend.labels.font.size = 12;
            parentChart.options.scales.x.ticks.maxRotation = 0;
            parentChart.options.scales.x.ticks.font.size = 11;
            parentChart.options.scales.y.ticks.font.size = 11;
            parentChart.update();
        }
    }

    adjustParentChart();
    window.addEventListener('resize', adjustParentChart);
}

// Copy Family Code Functionality
document.querySelectorAll('.btn-copy').forEach(button => {
    button.addEventListener('click', function() {
        const code = this.getAttribute('data-code');
        if (!code || code === 'BELUM ADA') {
            return;
        }

        navigator.clipboard.writeText(code).then(() => {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check me-2"></i>Tersalin!';
            this.classList.remove('btn-primary');
            this.classList.add('btn-success');

            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
            }, 2000);
        });
    });
});

// FAB Button Action
document.getElementById('addChildBtn')?.addEventListener('click', function() {
    const modalHTML = `
        <div class="modal fade" id="addChildModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus me-2 text-primary"></i>Tambah Anak
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            Anak dapat terhubung dengan menggunakan Kode Keluarga:
                        </div>
                        <div class="text-center mb-4">
                            <code class="fs-4 fw-bold text-primary d-block mb-2">
                                ${document.querySelector('.family-code-box code').textContent}
                            </code>
                            <small class="text-muted">Berikan kode ini kepada anak Anda</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.getElementById('addChildModal'));
    modal.show();

    document.getElementById('addChildModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
});
</script>

<style>
/* Custom styles for parent dashboard */
.family-code-box {
    flex: 1;
}

.family-code-box code {
    font-family: 'Consolas', 'Monaco', monospace;
}

.child-card {
    border: 1px solid var(--border-light);
    transition: all 0.3s;
    height: 100%;
}

.child-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-md);
}

.child-avatar {
    font-size: 1.25rem;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .family-code-box {
        font-size: 1rem !important;
        padding: 0.75rem !important;
        word-break: break-all;
    }

    .btn-copy {
        width: 100%;
        margin-top: 0.5rem;
    }

    .child-card {
        padding: 1rem !important;
    }

    .child-card h6 {
        font-size: 1rem;
    }

    .child-card .table th,
    .child-card .table td {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.8125rem;
    }

    .text-truncate {
        max-width: 80px !important;
    }
}

@media (max-width: 576px) {
    .row.g-3>.col-lg-6 {
        margin-bottom: 1rem;
    }

    .child-card .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }

    .child-card .badge-income {
        align-self: flex-start;
    }

    .chart-area {
        height: 200px !important;
    }
}

/* Dark mode support */
[data-bs-theme="dark"] .child-card {
    background: var(--bg-primary);
    border-color: var(--border-medium);
}

[data-bs-theme="dark"] .family-code-box {
    background: rgba(99, 102, 241, 0.15) !important;
    border-color: var(--primary) !important;
}
</style>