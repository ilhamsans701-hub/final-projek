<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Catat Transaksi Baru</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/student/store" method="POST">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="date" class="form-control" value="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Transaksi</label>
                                <select name="type" class="form-select" id="type_select" required>
                                    <option value="expense">Pengeluaran (Expense)</option>
                                    <option value="income">Pemasukan (Income)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php foreach($data['categories'] as $cat) : ?>
                                <option value="<?= $cat['id']; ?>" data-type="<?= $cat['type']; ?>">
                                    <?= ucfirst($cat['type']); ?> - <?= $cat['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Pilih kategori yang sesuai dengan jenis transaksi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control"
                                placeholder="Contoh: Makan Siang, Beli Buku" required>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Mata Uang</label>
                                <select name="currency" class="form-select" id="currency_select">
                                    <option value="IDR" selected>IDR - Rupiah</option>
                                    <option value="USD">USD - Dollar AS</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="SGD">SGD - Dollar Singapura</option>
                                    <option value="JPY">JPY - Yen Jepang</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="currency_label">Rp</span>
                                    <input type="number" name="amount" class="form-control" placeholder="0" min="0"
                                        required>
                                </div>
                                <small class="text-info" id="rate_info" style="display:none;">
                                    <i class="fas fa-info-circle"></i> Sistem akan otomatis mengkonversi ke IDR.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASEURL; ?>/student" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success px-4">Simpan Transaksi</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script Sederhana untuk UX
document.getElementById('currency_select').addEventListener('change', function() {
    var currency = this.value;
    var label = document.getElementById('currency_label');
    var info = document.getElementById('rate_info');

    label.innerText = currency;

    if (currency !== 'IDR') {
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
});
</script>