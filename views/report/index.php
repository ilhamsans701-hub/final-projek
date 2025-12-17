<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-print me-2"></i>Cetak Laporan Keuangan</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST" id="reportForm" target="_blank">

                        <div class="mb-3">
                            <label class="form-label">Pilih Bulan</label>
                            <select name="month" class="form-select" required>
                                <?php 
                                for($m=1; $m<=12; $m++){
                                    $monthName = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                    $selected = ($m == date('n')) ? 'selected' : '';
                                    echo "<option value='$m' $selected>$monthName</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Pilih Tahun</label>
                            <select name="year" class="form-select" required>
                                <?php 
                                $currentYear = date('Y');
                                for($y=$currentYear; $y>=$currentYear-5; $y--){
                                    echo "<option value='$y'>$y</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" formaction="<?= BASEURL; ?>/report/print" class="btn btn-primary">
                                <i class="fas fa-file-pdf me-2"></i>Preview & Cetak PDF
                            </button>

                            <button type="submit" formaction="<?= BASEURL; ?>/report/export_csv"
                                class="btn btn-success">
                                <i class="fas fa-file-csv me-2"></i>Download Excel (CSV)
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>