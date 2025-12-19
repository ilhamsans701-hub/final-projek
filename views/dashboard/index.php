<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="fw-bold mb-0">Dashboard Orang Tua</h5>
    </div>

    <div class="p-4">
        <?php Flasher::flash(); ?>

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
                        $totalIncome = 0;
                        foreach($data['children_data'] ?? [] as $child) {
                            $totalIncome += $child['income'] ?? 0;
                        }
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
                        $totalExpense = 0;
                        foreach($data['children_data'] ?? [] as $child) {
                            $totalExpense += $child['expense'] ?? 0;
                        }
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
                            <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                                <div
                                    class="family-code-box p-3 rounded bg-primary bg-opacity-10 border border-primary flex-grow-1">
                                    <code class="fs-4 fw-bold text-primary" id="familyCode">
                                        <?= htmlspecialchars($data['family_code'] ?? 'BELUM ADA'); ?>
                                    </code>
                                </div>
                                <button class="btn btn-primary btn-copy" id="copyButton"
                                    <?= empty($data['family_code']) || $data['family_code'] == 'BELUM ADA' ? 'disabled' : ''; ?>>
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
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php
                                            // Ambil foto profil anak
                                            $childPhoto = $child['info']['photo'] ?? '';
                                            $childUsername = htmlspecialchars($child['info']['username'] ?? 'Anak');

                                            // Buat URL avatar
                                            if (!empty($childPhoto)) {
                                                $avatarUrl = BASEURL . '/img/profile/' . $childPhoto;
                                                $fallbackAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($childUsername) . '&background=6366f1&color=fff&size=64';
                                            } else {
                                                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($childUsername) . '&background=6366f1&color=fff&size=64';
                                                $fallbackAvatar = $avatarUrl;
                                            }
                                        ?>

                                        <img src="<?= $avatarUrl; ?>" class="rounded-circle border"
                                            style="width: 48px; height: 48px; object-fit: cover;"
                                            alt="<?= $childUsername; ?>"
                                            onerror="this.onerror=null; this.src='<?= $fallbackAvatar; ?>';">
                                        <div>
                                            <h6 class="fw-bold mb-0">
                                                <?= htmlspecialchars($child['info']['username'] ?? 'Anak'); ?></h6>
                                            <small class="text-muted">
                                                Bergabung:
                                                <?= date('d M Y', strtotime($child['info']['created_at'] ?? 'now')); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge-income mt-2 mt-md-0">
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
                                    <a href="<?= BASEURL; ?>/dashboard/detail/<?= $child['info']['id'] ?? ''; ?>"
                                        class="btn btn-outline-primary btn-sm">
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

        <?php if(!empty($data['children_data'])) : ?>
        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Perbandingan Keuangan Anak <?= date('F Y'); ?></h5>
                    </div>
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="parentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Floating Action Button -->
<button class="fab" title="Refresh Data" onclick="location.reload()">
    <i class="fas fa-sync-alt"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// COPY FUNCTIONALITY - Single consolidated function
document.addEventListener('DOMContentLoaded', function() {
    // Copy Family Code Function
    const copyButton = document.getElementById('copyButton');
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            const familyCodeElement = document.getElementById('familyCode');
            if (!familyCodeElement) return;

            const familyCode = familyCodeElement.textContent.trim();

            // Check if code is valid
            if (familyCode === 'BELUM ADA' || familyCode === '') {
                return;
            }

            // Save original state
            const originalHTML = this.innerHTML;
            const originalClass = this.className;

            // Use modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(familyCode)
                    .then(() => {
                        // Success feedback
                        this.innerHTML = '<i class="fas fa-check me-2"></i>Tersalin!';
                        this.className = 'btn btn-success btn-copy';
                        this.disabled = true;

                        // Revert after 2 seconds
                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.className = originalClass;
                            this.disabled = false;
                        }, 2000);
                    })
                    .catch(err => {
                        console.error('Failed to copy: ', err);
                        fallbackCopyText(familyCode, this, originalHTML, originalClass);
                    });
            } else {
                fallbackCopyText(familyCode, this, originalHTML, originalClass);
            }
        });

        // Make code selectable on click
        const familyCodeElement = document.getElementById('familyCode');
        if (familyCodeElement) {
            familyCodeElement.style.cursor = 'pointer';
            familyCodeElement.addEventListener('click', function() {
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(this);
                selection.removeAllRanges();
                selection.addRange(range);
            });
        }
    }

    // Fallback method using execCommand
    function fallbackCopyText(text, button, originalHTML, originalClass) {
        const textArea = document.createElement('textarea');
        textArea.value = text;

        // Make the textarea out of viewport
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);

        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');

            if (successful) {
                // Success feedback
                button.innerHTML = '<i class="fas fa-check me-2"></i>Tersalin!';
                button.className = 'btn btn-success btn-copy';
                button.disabled = true;

                // Revert after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.className = originalClass;
                    button.disabled = false;
                }, 2000);
            } else {
                alert('Gagal menyalin kode. Silakan salin manual dari kotak kode.');
            }
        } catch (err) {
            console.error('Fallback copy failed: ', err);
            alert('Gagal menyalin kode. Silakan salin manual dari kotak kode.');
        } finally {
            document.body.removeChild(textArea);
        }
    }

    // CHART FUNCTIONALITY
    // Prepare chart data from PHP
    const childNames = <?= json_encode(array_map(function($child) {
        return $child['info']['username'] ?? 'Anak';
    }, $data['children_data'] ?? [])); ?>;
    const childIncomes = <?= json_encode(array_column($data['children_data'] ?? [], 'income')); ?>;
    const childExpenses = <?= json_encode(array_column($data['children_data'] ?? [], 'expense')); ?>;

    // Initialize chart if there's data
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
                    },
                    {
                        label: 'Pengeluaran',
                        data: childExpenses,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4],
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            },
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

        // Responsive adjustments - Fixed version
        function adjustChart() {
            if (window.innerWidth <= 768) {
                if (parentChart.options.plugins.legend.labels) {
                    parentChart.options.plugins.legend.labels.font.size = 11;
                }
                if (parentChart.options.scales.x.ticks) {
                    parentChart.options.scales.x.ticks.maxRotation = 45;
                    parentChart.options.scales.x.ticks.font.size = 10;
                }
                if (parentChart.options.scales.y.ticks) {
                    parentChart.options.scales.y.ticks.font.size = 10;
                }
            } else {
                if (parentChart.options.plugins.legend.labels) {
                    parentChart.options.plugins.legend.labels.font.size = 12;
                }
                if (parentChart.options.scales.x.ticks) {
                    parentChart.options.scales.x.ticks.maxRotation = 0;
                    parentChart.options.scales.x.ticks.font.size = 11;
                }
                if (parentChart.options.scales.y.ticks) {
                    parentChart.options.scales.y.ticks.font.size = 11;
                }
            }
            parentChart.update();
        }

        adjustChart();
        window.addEventListener('resize', adjustChart);
    }
});
</script>

<style>
/* Custom styles for parent dashboard */
.family-code-box {
    flex: 1;
    min-width: 200px;
}

.family-code-box code {
    font-family: 'Consolas', 'Monaco', monospace;
    word-break: break-all;
    cursor: pointer;
    user-select: all;
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

.badge-income {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 500;
    white-space: nowrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .family-code-box code {
        font-size: 1rem !important;
    }

    .family-code-box {
        padding: 0.75rem !important;
    }

    .child-card {
        padding: 1rem !important;
    }

    .child-card .table th,
    .child-card .table td {
        padding: 0.5rem !important;
        font-size: 0.875rem;
    }

    .text-truncate {
        max-width: 100px !important;
    }

    .chart-area {
        height: 250px !important;
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

    .family-code-box {
        min-width: 100%;
    }

    .btn-copy {
        width: 100%;
        margin-top: 0.5rem;
    }
}
</style>