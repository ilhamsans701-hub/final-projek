<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan_<?= $data['month_name']; ?>_<?= $data['year']; ?>_<?= $data['user']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Source+Serif+Pro:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
    /* CSS Khusus Cetak - Modern & Professional */
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #3498db;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
        --light-gray: #f8f9fa;
        --border-color: #dee2e6;
    }

    body {
        font-family: 'Inter', sans-serif;
        color: var(--primary-color);
        line-height: 1.5;
        background: white;
    }

    .header-laporan {
        border-bottom: 2px solid var(--secondary-color);
        margin-bottom: 30px;
        padding-bottom: 20px;
        position: relative;
    }

    .company-name {
        font-family: 'Source Serif Pro', serif;
        font-weight: 700;
        color: var(--primary-color);
        letter-spacing: 0.5px;
    }

    .period-badge {
        display: inline-block;
        background: var(--secondary-color);
        color: white;
        padding: 6px 20px;
        border-radius: 20px;
        font-weight: 500;
        margin-top: 5px;
    }

    .info-box {
        background: var(--light-gray);
        border-left: 4px solid var(--secondary-color);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .table-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead {
        background: linear-gradient(135deg, var(--primary-color), #1a2530);
        color: white;
    }

    .table thead th {
        border: none;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: rgba(52, 152, 219, 0.05);
    }

    .table tfoot {
        background: var(--light-gray);
        font-weight: 600;
    }

    .income-cell {
        color: var(--success-color);
        font-weight: 500;
    }

    .expense-cell {
        color: var(--danger-color);
        font-weight: 500;
    }

    .total-row {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
        font-size: 1.05rem;
    }

    .balance-highlight {
        background: linear-gradient(135deg, var(--success-color), #2ecc71) !important;
        color: white !important;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .signature-area {
        margin-top: 60px;
        padding-top: 30px;
        border-top: 2px solid var(--border-color);
        position: relative;
    }

    .signature-line {
        width: 250px;
        border-top: 1px solid var(--primary-color);
        margin-top: 60px;
        display: inline-block;
    }

    .print-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        opacity: 0.03;
        font-size: 8rem;
        font-weight: 900;
        color: var(--primary-color);
        pointer-events: none;
        z-index: -1;
        font-family: 'Source Serif Pro', serif;
    }

    /* Print-specific styles */
    @media print {
        @page {
            margin: 1.5cm;
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

        .print-watermark {
            display: block;
        }

        .header-laporan {
            page-break-after: avoid;
        }

        .table-container {
            page-break-inside: avoid;
        }

        .signature-area {
            page-break-before: avoid;
        }
    }

    /* Screen-only styles */
    @media screen {
        .print-watermark {
            display: none;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
    }

    /* Utility classes */
    .text-important {
        color: var(--primary-color);
        font-weight: 600;
    }

    .text-subtle {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    }
    </style>
</head>

<body>
    <!-- Watermark hanya muncul saat print -->
    <div class="print-watermark">MYMONEY</div>

    <div class="container mt-3">

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
            <h1 class="company-name mb-3">LAPORAN KEUANGAN PRIBADI</h1>
            <div class="period-badge mb-2">
                <i class="fas fa-calendar-alt me-2"></i>
                <?= $data['month_name']; ?> <?= $data['year']; ?>
            </div>
            <p class="text-subtle mb-0">Dicetak otomatis • Sistem MyMoney</p>
        </div>

        <!-- Informasi Laporan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box">
                    <h6 class="text-important mb-2">
                        <i class="fas fa-user-circle me-2"></i>Informasi Pemilik
                    </h6>
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Nama:</strong> <?= htmlspecialchars($data['user']); ?><br>
                            <strong>Tanggal Cetak:</strong> <?= date('d F Y'); ?>
                        </div>
                        <div>
                            <strong>ID Referensi:</strong><br>
                            <code><?= strtoupper(uniqid('RPT-')); ?></code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <h6 class="text-important mb-2">
                        <i class="fas fa-info-circle me-2"></i>Informasi Laporan
                    </h6>
                    <strong>Status:</strong> <span class="badge bg-success">Final</span><br>
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
                        <th width="5%">#</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Kategori</th>
                        <th width="25%">Keterangan</th>
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
                            <i class="fas fa-inbox fa-2x text-muted mb-3 d-block"></i>
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
                                rounded-pill px-3 py-1">
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
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">RATA-RATA/HARI</h6>
                        <h4 class="text-important">Rp <?= number_format($totalKeluar/30, 0, ',', '.'); ?></h4>
                        <small class="text-subtle">Pengeluaran per hari</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">RASIO TABUNGAN</h6>
                        <h4 class="text-success">
                            <?= $totalMasuk > 0 ? number_format(($saldo/$totalMasuk)*100, 1) : '0'; ?>%</h4>
                        <small class="text-subtle">Dari total pemasukan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">TRANSACTION COUNT</h6>
                        <h4 class="text-important"><?= count($transactions); ?></h4>
                        <small class="text-subtle">Entri dalam laporan</small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Area Tanda Tangan -->
        <div class="signature-area">
            <div class="row">
                <div class="col-md-8">
                    <div class="text-subtle">
                        <i class="fas fa-file-contract me-2"></i>
                        <strong>Catatan:</strong> Laporan ini dihasilkan secara otomatis dan valid untuk keperluan
                        monitoring keuangan pribadi.
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <p class="mb-1">Karawang, <?= date('d F Y'); ?></p>
                    <div class="signature-line"></div>
                    <p class="fw-bold mt-3 mb-0"><?= htmlspecialchars($data['user']); ?></p>
                    <small class="text-subtle">Pemilik Akun</small>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 pt-3 border-top text-subtle">
            <small>
                Halaman ini dicetak dari Sistem MyMoney •
                <?= date('H:i:s'); ?>
            </small>
        </div>
    </div>

    <!-- Tambahkan Font Awesome untuk icon -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>