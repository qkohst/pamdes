@include('layouts.main.header')
@include('layouts.main.sidebar')
<div class="content">
    <div class="page-inner">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card user-card">
                        <div class="card-header">
                            <h5>Profile</h5>
                        </div>
                        <div class="card-body  text-center">
                            <div class="usre-image">
                                <img src="/assets/img/user/{{Auth::user()->avatar}}" class="avatar-img rounded-circle w-75" alt=".....">
                            </div>
                            <h6 class="mt-3">{{Auth::user()->nama}}</h6>
                            <p>{{Auth::user()->level_user}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-teatle">Edit Profile</h5>
                        </div>
                        <div class="card-body">
                            <form id="edit-profile">
                                <div class="form-group row">
                                    <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control required" name="nama" value="{{Auth::user()->nama}}">
                                    </div>
                                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control no-spasi required" name="username" value="{{Auth::user()->username}}">
                                    </div>
                                    <label for="foto_profile" class="col-sm-3 col-form-label">Foto Profile</label>
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_profile" id="customFile" accept="image/*">
                                            <label class="custom-file-label" for="customFile">{{Auth::user()->avatar}}</label>
                                        </div>
                                    </div>
                                </div>
                                <a class="mt-2 text-danger" data-toggle="collapse" href="#collapseGantiPassword" role="button" aria-expanded="false" aria-controls="collapseGantiPassword">
                                    <i class="fa fa-unlock-alt mr-1"></i> Ganti Password
                                </a>

                                <div class="collapse" id="collapseGantiPassword">
                                    <hr>
                                    <small class="form-text text-muted mb-3">Hanya diisi ketika ingin ganti password</small>
                                    <div class="form-group row">
                                        <label for="password_lama" class="col-sm-3 col-form-label">Password Lama</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control no-spasi" name="password_lama" placeholder="Password Lama">
                                        </div>
                                        <label for="password_baru" class="col-sm-3 col-form-label">Password Baru</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control no-spasi" name="password_baru" placeholder="Password Baru">
                                        </div>
                                        <label for="konfirmasi_password" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control no-spasi" name="konfirmasi_password" placeholder="Konfirmasi Password">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer py-3">
                            <button type="button" class="btn btn-sm btn-primary float-right mr-0" id="btn-save">SIMPAN</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [ Main Content ] end -->
        </div>
    </div>
</div>

@include('layouts.main.footer')