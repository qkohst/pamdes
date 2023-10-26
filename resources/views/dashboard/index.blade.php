@include('layouts.main.header')
@include('layouts.main.sidebar')
<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white fw-bold">Dashboard</h2>
                    <h5 class="text-white op-7 mb-1">Selamat Datang {{Auth::user()->nama}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-4">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Rekap Pelanggan</div>
                        <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-1"></div>
                                <h6 class="fw-bold mt-3 mb-0">Aktif</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-2"></div>
                                <h6 class="fw-bold mt-3 mb-0">Non Aktif</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-3"></div>
                                <h6 class="fw-bold mt-3 mb-0">Total Pelanggan</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Rekap & Statistik Pembayaran</div>
                        <div class="row py-3">
                            <div class="col-md-6 d-flex flex-column justify-content-around">
                                <div>
                                    <h6 class="fw-bold text-uppercase text-success op-8">Pembayaran Bulan Ini</h6>
                                    <h3 class="fw-bold" id="pembayaran-bulan-ini">Rp 0</h3>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-around">
                                <div>
                                    <h6 class="fw-bold text-uppercase text-primary op-8">Pembayaran Tahun Ini</h6>
                                    <h3 class="fw-bold" id="pembayaran-tahun-ini">Rp 0</h3>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-around">
                                <div>
                                    <h6 class="fw-bold text-uppercase text-danger op-8">Total Pembayaran</h6>
                                    <h3 class="fw-bold" id="total-pembayaran">Rp 0</h3>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="chart-container">
                                    <canvas id="totalIncomeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.main.footer')