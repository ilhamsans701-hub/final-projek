<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan_<?= $data['month_name']; ?>_<?= $data['year']; ?>_<?= $data['user']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* CSS Khusus Cetak - Modern & Professional */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --light-gray: #f8f9fa;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    body {
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
        line-height: 1.5;
        background: white;
        font-size: 14px;
    }

    /* Header Laporan */
    .header-laporan {
        border-bottom: 2px solid var(--primary);
        margin-bottom: 25px;
        padding-bottom: 15px;
    }

    .period-badge {
        display: inline-block;
        background: var(--primary);
        color: white;
        padding: 6px 20px;
        border-radius: 20px;
        font-weight: 500;
        margin-top: 5px;
    }

    .info-box {
        background: rgba(99, 102, 241, 0.05);
        border-left: 4px solid var(--primary);
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    /* Tabel Transaksi */
    .table-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table thead {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .table thead th {
        border: none;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tfoot {
        background: rgba(99, 102, 241, 0.05);
        font-weight: 600;
    }

    .income-cell {
        color: var(--success);
        font-weight: 500;
    }

    .expense-cell {
        color: var(--danger);
        font-weight: 500;
    }

    .total-row {
        background: rgba(248, 250, 252, 0.8) !important;
        font-size: 0.95rem;
    }

    .balance-highlight {
        background: linear-gradient(135deg, var(--success), rgba(16, 185, 129, 0.9)) !important;
        color: white !important;
        font-size: 1rem;
        font-weight: 600;
    }

    /* Badge styles */
    .badge {
        font-size: 0.75rem;
        padding: 4px 10px;
    }

    /* Print-specific styles */
    @media print {
        @page {
            margin: 1cm;
            size: A4 portrait;
        }

        body {
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .table {
            font-size: 10pt;
        }

        .table thead th {
            padding: 8px 12px;
        }

        .table tbody td {
            padding: 8px 12px;
        }

        .header-laporan {
            page-break-after: avoid;
        }

        .table-container {
            page-break-inside: avoid;
        }

        /* Force portrait orientation for print */
        @page {
            size: portrait;
        }
    }

    /* Screen-only styles */
    @media screen {
        body {
            background: #f8fafc;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
    }

    /* Utility classes */
    .text-important {
        color: var(--text-primary);
        font-weight: 600;
    }

    .text-subtle {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    /* Action buttons for screen */
    .action-buttons {
        padding: 15px;
        background: white;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table {
            font-size: 0.8rem;
        }

        .table thead th,
        .table tbody td {
            padding: 8px 10px;
        }

        .container {
            padding: 15px;
            margin: 10px;
        }

        .info-box {
            padding: 10px 12px;
        }
    }

    /* Watermark for print */
    .print-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        opacity: 0.03;
        font-size: 6rem;
        font-weight: 900;
        color: var(--text-primary);
        pointer-events: none;
        z-index: -1;
        font-family: 'Inter', sans-serif;
    }

    @media print {
        .print-watermark {
            display: block;
        }
    }

    @media screen {
        .print-watermark {
            display: none;
        }
    }

    /* Stats cards */
    .stat-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-card h4 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stat-card small {
        color: var(--text-secondary);
    }

    /* Footer info */
    .footer-info {
        padding: 15px 0;
        border-top: 1px solid var(--border-color);
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <!-- Watermark hanya muncul saat print -->
    <div class="print-watermark">MYMONEY</div>

    <div class="container mt-3">

        <!-- Action buttons hanya untuk screen -->
        <div class="action-buttons no-print mb-4">
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak / Simpan PDF
                </button>
                <button class="btn btn-outline-secondary" onclick="window.close()">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                Gunakan tombol di atas untuk mencetak atau menyimpan sebagai PDF
            </small>
        </div>

        <!-- Header Laporan -->
        <div class="header-laporan text-center">
            <h3 class="fw-bold mb-2" style="color: var(--primary);">LAPORAN KEUANGAN MYMONEY</h3>
            <div class="period-badge mb-2">
                <i class="fas fa-calendar-alt me-2"></i>
                <?= $data['month_name']; ?> <?= $data['year']; ?>
            </div>
            <p class="text-subtle mb-0">Dicetak otomatis dari Sistem MyMoney</p>
        </div>

        <!-- Informasi Laporan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box">
                    <h6 class="text-important mb-2">
                        <i class="fas fa-user me-2"></i>Informasi Pemilik
                    </h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Nama:</strong> <?= htmlspecialchars($data['user']); ?><br>
                            <strong>Tanggal Cetak:</strong> <?= date('d F Y'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <h6 class="text-important mb-2">
                        <i class="fas fa-chart-line me-2"></i>Informasi Periode
                    </h6>
                    <strong>Status:</strong> <span class="badge bg-success">Laporan Final</span><br>
                    <strong>Jumlah Transaksi:</strong> <?= count($data['transactions'] ?? []); ?> entri<br>
                    <strong>Mata Uang:</strong> IDR (Rupiah Indonesia)
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Kategori</th>
                        <th width="30%">Keterangan</th>
                        <th width="15%" class="text-end">Pemasukan</th>
                        <th width="15%" class="text-end">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $totalMasuk = 0;
                    $totalKeluar = 0;
                    $transactions = $data['transactions'] ?? [];
                    ?>

                    <?php if(empty($transactions)) : ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox fa-lg text-muted mb-3 d-block"></i>
                            <span class="text-muted">Tidak ada transaksi pada periode ini</span>
                        </td>
                    </tr>
                    <?php else : ?>
                    <?php foreach($transactions as $trx) : ?>
                    <?php 
                    $isIncome = $trx['type'] == 'income';
                    if($isIncome) $totalMasuk += $trx['amount'];
                    else $totalKeluar += $trx['amount'];
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?= $no++; ?></td>
                        <td class="text-center">
                            <strong><?= date('d', strtotime($trx['transaction_date'])); ?></strong>
                            <div class="text-subtle"><?= date('M Y', strtotime($trx['transaction_date'])); ?></div>
                        </td>
                        <td>
                            <span class="badge 
                                <?= $isIncome ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger'; ?> 
                                rounded-pill">
                                <?= htmlspecialchars($trx['category_name']); ?>
                            </span>
                        </td>
                        <td>
                            <?= htmlspecialchars($trx['description']); ?>
                            <?php if(!empty($trx['currency_code']) && $trx['currency_code'] != 'IDR'): ?>
                            <small class="text-muted d-block">
                                <i class="fas fa-globe me-1"></i>
                                <?= $trx['currency_code']; ?> <?= number_format($trx['amount_origin'], 2); ?>
                            </small>
                            <?php endif; ?>
                        </td>
                        <td class="text-end income-cell">
                            <?= $isIncome ? 'Rp ' . number_format($trx['amount'], 0, ',', '.') : '-' ?>
                        </td>
                        <td class="text-end expense-cell">
                            <?= !$isIncome ? 'Rp ' . number_format($trx['amount'], 0, ',', '.') : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4" class="text-end">
                            <strong>TOTAL PERIODE</strong>
                        </td>
                        <td class="text-end income-cell">
                            <strong>Rp <?= number_format($totalMasuk, 0, ',', '.'); ?></strong>
                        </td>
                        <td class="text-end expense-cell">
                            <strong>Rp <?= number_format($totalKeluar, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <?php $saldo = $totalMasuk - $totalKeluar; ?>
                    <tr class="balance-highlight">
                        <td colspan="4" class="text-end">
                            <strong>SALDO AKHIR PERIODE</strong>
                        </td>
                        <td colspan="2" class="text-center">
                            <strong>Rp <?= number_format($saldo, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Ringkasan Statistik -->
        <?php if(!empty($transactions)): ?>
        <div class="row mt-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted mb-2">RATA-RATA/HARI</h6>
                    <h4 class="text-important">Rp <?= number_format($totalKeluar/30, 0, ',', '.'); ?></h4>
                    <small class="text-subtle">Pengeluaran per hari</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted mb-2">RASIO TABUNGAN</h6>
                    <h4 class="text-success">
                        <?= $totalMasuk > 0 ? number_format(($saldo/$totalMasuk)*100, 1) : '0'; ?>%
                    </h4>
                    <small class="text-subtle">Dari total pemasukan</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted mb-2">JUMLAH TRANSAKSI</h6>
                    <h4 class="text-important"><?= count($transactions); ?></h4>
                    <small class="text-subtle">Entri dalam laporan</small>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Informasi -->
        <div class="footer-info text-center">
            <small class="text-subtle">
                <i class="fas fa-info-circle me-1"></i>
                Laporan ini dihasilkan otomatis oleh Sistem MyMoney • Dicetak pada: <?= date('d F Y H:i:s'); ?>
            </small>
            <div class="mt-2">
                <small class="text-muted">
                    © <?= date('Y'); ?> MyMoney
                </small>
            </div>
        </div>
    </div>

    <script>
    // Auto print jika parameter print=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        window.print();
    }

    // Keyboard shortcut for print (Ctrl+P or Cmd+P)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
    </script>
</body>

</html>