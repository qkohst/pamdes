@include('layouts.auth.header-search')
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="card">
                <div class="card-header px-0 py-0 mx-0 my-0" id="headingFilter">
                    <button class="btn btn-block btn-primary text-left" id="show-filter" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                        <i class="fa fa-search"></i> Cari Data
                    </button>
                </div>
                <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter" data-parent="#accordionExample">
                    <form id="form-filter">
                        <div class="card-body py-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="filter_kode">Kode Pelanggan</label>
                                        <input type="text" class="form-control" id="filter_kode" name="filter_kode" placeholder="Kode Pelanggan">
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
                                        <label for="filter_status">Status</label>
                                        <select class="form-control select2" id="filter_status" name="filter_status" style="width: 100%;">
                                            <option value="">Semua Data</option>
                                            <option value="1">Lunas</option>
                                            <option value="0">Belum Lunas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-sm btn-default" id="btn-reset" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">RESET</button>
                            <button class="btn btn-sm btn-primary" type="submit" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">CARI</button>
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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-data" class="datatable display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Pelanggan</th>
                                            <th>Bulan Tahun</th>
                                            <th>Kode Transaksi</th>
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
    <footer class="footer">
        <div class="container-fluid">
            <div class="copyright ml-auto">
                St Pamdes V.10 | Developed by <a href="https://www.instagram.com/qkoh_st" target="_blank">Qkoh St</a> | 2023
            </div>
        </div>
    </footer>
</div>
@include('layouts.auth.footer-search')