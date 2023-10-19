@include('layouts.main.header')
@include('layouts.main.sidebar')
<div class="content">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{$title}}</div>
            </div>
            <div class="card-body">
                <form id="form-add">
                    <div class="form-group row">
                        <label for="tarif_per_meter" class="col-sm-2 col-form-label">Tarif Per M<sup>3</sup></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control autonumeric required text-right" id="tarif_per_meter" name="tarif_per_meter" value="{{$tarif_air->tarif_per_meter}}">
                        </div>
                        <label for="biaya_pemeliharaan" class="col-sm-2 col-form-label">Biaya Pemeliharaan</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control autonumeric required text-right" id="biaya_pemeliharaan" name="biaya_pemeliharaan" value="{{$tarif_air->biaya_pemeliharaan}}">
                        </div>
                        <label for="biaya_administrasi" class="col-sm-2 col-form-label">Biaya Administrasi</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control autonumeric required text-right" id="biaya_administrasi" name="biaya_administrasi" value="{{$tarif_air->biaya_administrasi}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" id="btn-save" class="btn btn-primary btn-sm float-right">SIMPAN</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.main.footer')