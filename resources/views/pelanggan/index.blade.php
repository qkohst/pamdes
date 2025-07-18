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
                                    <label for="filter_kode">Kode</label>
                                    <input type="text" class="form-control" id="filter_kode" name="filter_kode" placeholder="Kode">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="filter_nama_lengkap" name="filter_nama_lengkap" placeholder="Nama Lengkap">
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
                        <div class="card-head-row">
                            <div class="card-title">Data {{$title}}</div>
                            <div class="card-tools">
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" id="btn-export-excel">
                                    <i class="fa fa-file-download mr-1"></i>
                                    EXPORT EXCEL
                                </button>
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" data-toggle="modal" data-target="#modalImportData">
                                    <i class="fa fa-upload mr-1"></i>
                                    IMPORT
                                </button>
                                <button class="btn btn-primary btn-round btn-sm" data-toggle="modal" data-target="#modalAddData">
                                    <i class="fa fa-plus mr-1"></i>
                                    TAMBAH
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-data" class="datatable display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Aksi</th>
                                        <th>Kode</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nomor HP/WA</th>
                                        <th>Alamat</th>
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
                                <label for="kode_pelanggan">Kode Pelanggan</label>
                                <input type="text" class="form-control no-spasi uppercase required" id="kode_pelanggan" name="kode_pelanggan" value="<AUTO GENERATE>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control first-uppercase required" id="nama_lengkap" name="nama_lengkap">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="nomor_hp_wa">Nomor HP/WA</label>
                                <input type="text" class="form-control hanya-angka required" id="nomor_hp_wa" name="nomor_hp_wa">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control first-uppercase required" id="alamat" name="alamat" rows="3"></textarea>
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
                        <input type="hidden" name="pelanggan_id">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kode_pelanggan">Kode Pelanggan</label>
                                <input type="text" class="form-control no-spasi uppercase required" id="kode_pelanggan" name="kode_pelanggan" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control first-uppercase required" id="nama_lengkap" name="nama_lengkap">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="nomor_hp_wa">Nomor HP/WA</label>
                                <input type="text" class="form-control hanya-angka required" id="nomor_hp_wa" name="nomor_hp_wa">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control first-uppercase required" id="alamat" name="alamat" rows="3"></textarea>
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

<!-- Modal Import -->
<div class="modal fade" id="modalImportData" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Import {{$title}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0">
                <form id="form-import">
                    <div class="alert alert-primary mt-3" role="alert">
                        <h5>Download format import</h5>
                        <p>Silahkan download file format import melalui tombol dibawah ini.</p>
                        <button class="btn btn-sm btn-primary text-white" name="btn-dowload-format" id="btn-dowload-format"><i class="fas fa-file-download mr-1"></i> Download</button>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file_import">File Import</label>
                                <input type="file" class="form-control no-spasi uppercase required" id="file_import" name="file_import" accept=".xlsx, .xls">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                <button type="button" id="btn-upload" class="btn btn-primary btn-sm">UPLOAD</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Import -->

@include('layouts.main.footer')