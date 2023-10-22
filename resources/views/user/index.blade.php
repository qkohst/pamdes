@include('layouts.main.header')
@include('layouts.main.sidebar')
<div class="content">
    <div class="page-inner">

        <div class="card">
            <div class="card-header px-0 py-0 mx-0 my-0" id="headingFilter">
                <button class="btn btn-block btn-primary text-left" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>
            <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter" data-parent="#accordionExample">
                <form id="form-filter">
                    <div class="card-body py-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_nama">Nama</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama" placeholder="Nama">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_username">Username</label>
                                    <input type="text" class="form-control" id="filter_username" name="filter_username" placeholder="Username">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_status">Status</label>
                                    <select class="form-control select2" id="filter_status" name="filter_status" style="width: 100%;">
                                        <option value="">Semua Status</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-sm btn-default" id="btn-reset" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">RESET</button>
                        <button class="btn btn-sm btn-primary" type="submit" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">FILTER</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data {{$title}}</h4>
                            <button class="btn btn-primary btn-round btn-sm ml-auto" data-toggle="modal" data-target="#modalAddData">
                                <i class="fa fa-plus mr-1"></i>
                                TAMBAH
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-data" class="datatable display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Aksi</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--  -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modalAddData" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Tambah {{$title}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0">
                <form id="form-add">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control first-uppercase required" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="username">Username</label>
                                <input type="text" class="form-control no-spasi required" id="username" name="username">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="password">Password</label>
                                <input type="password" class="form-control no-spasi required" id="password" name="password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="konfirmasi_password">Konfirmasi Password</label>
                                <input type="password" class="form-control no-spasi required" id="konfirmasi_password" name="konfirmasi_password">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                <button type="button" id="btn-save" class="btn btn-primary btn-sm">SIMPAN</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Add -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEditData" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit {{$title}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0">
                <form id="form-edit">
                    <div class="row">
                        <input type="hidden" name="user_id">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control first-uppercase required" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="username">Username</label>
                                <input type="text" class="form-control no-spasi required" id="username" name="username">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="status">Status</label>
                                <select class="form-control select2 required" id="status" name="status" style="width: 100%;">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group border-bottom">
                                <small class="form-text text-danger">* Hanya diisi ketika ingin ganti password</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control no-spasi" id="password" name="password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="konfirmasi_password">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control no-spasi" id="konfirmasi_password" name="konfirmasi_password">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                <button type="button" id="btn-update" class="btn btn-primary btn-sm">SIMPAN</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit -->
@include('layouts.main.footer')