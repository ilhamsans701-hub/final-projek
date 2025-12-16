<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="font-weight-light my-2">Registrasi Akun</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php Flasher::flash(); ?>
                        </div>
                    </div>

                    <form action="<?= BASEURL; ?>/auth/processRegister" method="POST">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="username" type="text" placeholder="Username" required />
                            <label>Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="email" type="email" placeholder="name@example.com"
                                required />
                            <label>Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="password" type="password" placeholder="Password"
                                required />
                            <label>Password</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Daftar Sebagai:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="role_ortu" value="orangtua"
                                    checked onclick="toggleFamilyCode()">
                                <label class="form-check-label" for="role_ortu">Orang Tua</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="role_anak" value="anak"
                                    onclick="toggleFamilyCode()">
                                <label class="form-check-label" for="role_anak">Mahasiswa (Anak)</label>
                            </div>
                        </div>

                        <div class="form-floating mb-3" id="family_code_input" style="display: none;">
                            <input class="form-control" name="input_family_code" id="input_family_code" type="text"
                                placeholder="Kode Keluarga" />
                            <label>Masukkan Kode Keluarga (Dari Orang Tua)</label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-success btn-lg" type="submit">Daftar Sekarang</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?= BASEURL; ?>/auth">Sudah punya akun? Login</a></div>
                </div>
            </div>
        </div>
    </div>
</div>