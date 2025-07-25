@include('layouts.auth.header')

<div class="auth-wrapper">
    <div class="auth-content container">
        <div class="card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form id="form-login">
                        <div class="card-body">
                            <div class="login-logo">
                                <img src="../assets/img/logo-2.png" alt="Logo" class="brand-image img-circle">
                                <p>Login</p>
                            </div>
                            <h4 class="mb-3 f-w-400">Login untuk kelola pembayaran air</h4>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-mail"></i></span>
                                </div>
                                <input type="text" name="username" class="form-control required" placeholder="Username">
                            </div>

                            <div class="input-group mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control required" placeholder="Password">
                            </div>

                            <button type="submit" class="btn btn-block btn-primary mb-4 mt-2 btn-login">Login</button>
                            <p class="mb-2 text-muted">Tidak punya akses login ? <a href="/cek-tagihan" class="f-w-400">Cek Tagihan</a></p>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 d-none d-md-block">
                    <img src="../assets/img/bg-auth.gif" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.auth.footer')