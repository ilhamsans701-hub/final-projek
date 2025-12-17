<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Transaksi</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/student/update" method="POST">
                        <input type="hidden" name="id" value="<?= $data['trx']['id']; ?>">
                        <input type="hidden" name="exchange_rate_old" value="<?= $data['trx']['exchange_rate']; ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="date" class="form-control"
                                    value="<?= date('Y-m-d', strtotime($data['trx']['transaction_date'])); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Transaksi</label>
                                <select name="type" class="form-select" id="type_select" required>
                                    <option value="expense"
                                        <?= ($data['trx']['type'] == 'expense') ? 'selected' : ''; ?>>Pengeluaran
                                    </option>
                                    <option value="income" <?= ($data['trx']['type'] == 'income') ? 'selected' : ''; ?>>
                                        Pemasukan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach($data['categories'] as $cat) : ?>
                                <option value="<?= $cat['id']; ?>"
                                    <?= ($cat['id'] == $data['trx']['category_id']) ? 'selected' : ''; ?>>
                                    <?= ucfirst($cat['type']); ?> - <?= $cat['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control"
                                value="<?= $data['trx']['description']; ?>" required>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Mata Uang</label>
                                <select name="currency" class="form-select" id="currency_select">
                                    <option value="IDR"
                                        <?= ($data['trx']['currency_code'] == 'IDR') ? 'selected' : ''; ?>>IDR</option>
                                    <option value="USD"
                                        <?= ($data['trx']['currency_code'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                    <option value="EUR"
                                        <?= ($data['trx']['currency_code'] == 'EUR') ? 'selected' : ''; ?>>EUR</option>
                                    <option value="SGD"
                                        <?= ($data['trx']['currency_code'] == 'SGD') ? 'selected' : ''; ?>>SGD</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nominal (Asli)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= $data['trx']['currency_code']; ?></span>
                                    <input type="number" name="amount" class="form-control"
                                        value="<?= $data['trx']['amount_origin']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASEURL; ?>/student" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning px-4">Update Data</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>