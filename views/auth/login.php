<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="font-weight-light my-2">Login MyMoney</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php Flasher::flash(); ?>
                        </div>
                    </div>

                    <form action="<?= BASEURL; ?>/auth/processLogin" method="POST">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="username" name="username" type="text" placeholder="Username"
                                required />
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="password" name="password" type="password"
                                placeholder="Password" required />
                            <label for="password">Password</label>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary btn-lg" type="submit">Masuk</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?= BASEURL; ?>/auth/register">Belum punya akun? Daftar disini</a></div>
                </div>
            </div>
        </div>
    </div>
</div>