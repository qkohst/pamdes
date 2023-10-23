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
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" id="btn-print-all" data-toggle="modal" data-target="#modalPrintAll">
                                    <i class="fa fa-print mr-1"></i>
                                    PRINT
                                </button>
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" id="btn-export-excel">
                                    <i class="fa fa-file-download mr-1"></i>
                                    EXPORT EXCEL
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
                                        <th>Total Tagihan (Rp)</th>
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
                                <label>Bulan Tahun</label>
                                <select class="form-control select2 required" name="bulan_tahun" style="width: 100%;">
                                    <option value="">--Pilih Data--</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" class="required" name="transaksi_id">
                        <input type="hidden" class="autonumeric-2 required" name="tarif_per_meter">
                        <input type="hidden" class="autonumeric-2 required" name="biaya_pemeliharaan">
                        <input type="hidden" class="autonumeric-2 required" name="biaya_administrasi">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_pemakaian">Total Pemakaian (M<sup>3</sup>)</label>
                                <input type="text" class="form-control autonumeric required" name="total_pemakaian" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_tagihan">Total Tagihan</label>
                                <input type="text" class="form-control autonumeric-2 required" name="total_tagihan" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="input_pembayaran">Input Pembayaran</label>
                                <input type="text" class="form-control autonumeric-2 required" name="input_pembayaran">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pt-0">
                                <label for="tanggal_pembayaran">Tanggal Pembayaran</label>
                                <input type="date" class="form-control required" name="tanggal_pembayaran" max="{{ now()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                <button type="button" id="btn-save" class="btn btn-primary btn-sm">BAYAR</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Add -->

<!-- Modal Detail -->
<div class="modal fade" id="modalDetailData" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Detail {{$title}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <form id="form-detail">
                    <div class="form-group row pb-0">
                        <label class="col-sm-4 col-form-label">Kode Transaksi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control no-spasi uppercase required" name="kode_transaksi" disabled>
                        </div>
                    </div>
                    <div class="form-group row py-0">
                        <label class="col-sm-4 col-form-label">Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pelanggan" disabled>
                        </div>
                    </div>
                    <div class="form-group row py-0">
                        <label class="col-sm-4 col-form-label">Bulan Tahun</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="bulan_tahun" disabled>
                        </div>
                    </div>
                    <div class="form-group row py-0">
                        <label class="col-sm-4 col-form-label">Total Pemakaian (M<sup>3</sup>)</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control autonumeric" name="total_pemakaian" disabled>
                        </div>
                    </div>
                    <div class="form-group row py-0">
                        <label class="col-sm-4 col-form-label">Total Tagihan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control autonumeric-2" name="total_tagihan" disabled>
                        </div>
                    </div>
                    <div class="form-group row pt-0">
                        <label class="col-sm-4 col-form-label">Tanggal Pembayaran</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="tanggal_pembayaran" disabled>
                        </div>
                    </div>
                    <div class="form-group row pt-0">
                        <label class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="status" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit -->

<!-- Modal modalPrintAll -->
<div class="modal fade" id="modalPrintAll" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Print Slip {{$title}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0">
                <form id="form-print-all">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="periode_bulan">Bulan Tahun</label>
                                <select class="form-control select2" id="periode_bulan" name="periode_bulan" style="width: 100%;">
                                    <option value="">Pilih Data</option>
                                    @foreach ($data_transaksi_sudah_bayar as $transaksi)
                                    <option value="{{ $transaksi->bulan_tahun }}">{{ $transaksi->bulan_tahun_indo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">BATAL</button>
                <button type="button" id="btn-cetak" class="btn btn-primary btn-sm">PRINT</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal modalPrintAll -->

@include('layouts.main.footer')