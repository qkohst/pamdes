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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="filter_kode">Kode</label>
                                    <input type="text" class="form-control" id="filter_kode" name="filter_kode" placeholder="Kode">
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
                            <div class="card-title">{{$title}}</div>
                            <div class="card-tools">
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" id="btn-print">
                                    <i class="fa fa-print mr-1"></i>
                                    PRINT
                                </button>
                                <button class="btn btn-info btn-border btn-round btn-sm mr-1" id="btn-export-excel">
                                    <i class="fa fa-file-download mr-1"></i>
                                    EXPORT EXCEL
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-data" class="datatable display table table-striped table-hover table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Bulan Tahun</th>
                                        <th rowspan="2">Pelanggan</th>
                                        <th rowspan="2">Kode Transaksi</th>
                                        <th colspan="3" class="text-center">Pemakaian (M<sup>3</sup>)</th>
                                        <th colspan="3" class="text-center">Biaya (Rp)</th>
                                        <th colspan="2" class="text-center">Tagihan (Rp)</th>
                                        <th rowspan="2">Tanggal Pembayaran</th>
                                        <th rowspan="2">Status</th>
                                    </tr>
                                    <tr>
                                        <th>Awal</th>
                                        <th>Akhir</th>
                                        <th>Total</th>
                                        <th>Pemakaian</th>
                                        <th>Pemeliharaan</th>
                                        <th>Administrasi</th>
                                        <th>Total Tagihan</th>
                                        <th>Bayar</th>
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

@include('layouts.main.footer')