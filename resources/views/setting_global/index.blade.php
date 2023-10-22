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
                        <label for="nama_instansi" class="col-sm-3 col-form-label">Nama Instansi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase required" id="nama_instansi" name="nama_instansi" value="{{$setting_global->nama}}">
                        </div>
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase required" id="alamat" name="alamat" value="{{$setting_global->alamat}}">
                        </div>
                        <label for="nomor_hp_wa" class="col-sm-3 col-form-label">Nomor HP/WA</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control hanya-angka required" id="nomor_hp_wa" name="nomor_hp_wa" value="{{$setting_global->nomor_hp_wa}}">
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