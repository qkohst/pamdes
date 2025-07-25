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
                                    <label for="filter_bulan_tahun">Bulan Tahun</label>
                                    <select class="form-control select2" id="filter_bulan_tahun" name="filter_bulan_tahun" style="width: 100%;">
                                        <option value="">Semua Data</option>
                                        @foreach ($option_bulan_tahun as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_pelanggan">Pelanggan</label>
                                    <select class="form-control select2" id="filter_pelanggan" name="filter_pelanggan" style="width: 100%;">
                                        <option value="">Semua Data</option>
                                        @foreach($data_pelanggan as $pelanggan)
                                        <option value="{{$pelanggan->id}}">{{$pelanggan->kode}} | {{$pelanggan->nama_lengkap}}</option>
                                        @endforeach
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
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" data-toggle="modal" data-target="#modalImportData">
                                    <i class="fa fa-upload mr-1"></i>
                                    IMPORT
                                </button>
                                <button class="btn btn-primary btn-round btn-sm" id="btn-tambah" data-toggle="modal" data-target="#modalAddData">
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
                                        <th>Kode Transaksi</th>
                                        <th>Bulan Tahun</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Total Pemakaian (M<sup>3</sup>)</th>
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
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control no-spasi uppercase required" name="kode_transaksi" value="<AUTO GENERATE>" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label>Bulan Tahun</label>
                                <select class="form-control select2 required" name="bulan_tahun" style="width: 100%;">
                                    <option value="">--Pilih Data--</option>
                                    @foreach ($option_bulan_tahun as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <select class="form-control select2 required" name="pelanggan" style="width: 100%;">
                                    <option value="">--Pilih Data--</option>
                                    @foreach($data_pelanggan as $pelanggan)
                                    <option value="{{$pelanggan->id}}">{{$pelanggan->kode}} | {{$pelanggan->nama_lengkap}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="pemakaian_sebelumnya">Pemakaian Air Bulan Sebelumnya (M<sup>3</sup>)</label>
                                <input type="text" class="form-control autonumeric required" name="pemakaian_sebelumnya">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="pemakaian_saat_ini">Pemakaian Air Saat Ini (M<sup>3</sup>)</label>
                                <input type="text" class="form-control autonumeric required" name="pemakaian_saat_ini">
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
                        <input type="hidden" name="transaksi_id">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control no-spasi uppercase required" name="kode_transaksi" value="<AUTO GENERATE>" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label>Bulan Tahun</label>
                                <input type="text" class="form-control" name="bulan_tahun" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <input type="text" class="form-control" name="pelanggan" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="pemakaian_sebelumnya">Pemakaian Air Bulan Sebelumnya (M<sup>3</sup>)</label>
                                <input type="text" class="form-control autonumeric required" name="pemakaian_sebelumnya" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="pemakaian_saat_ini">Pemakaian Air Saat Ini (M<sup>3</sup>)</label>
                                <input type="text" class="form-control autonumeric required" name="pemakaian_saat_ini">
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
                                <input type="file" class="form-control required" id="file_import" name="file_import" accept=".xlsx, .xls">
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