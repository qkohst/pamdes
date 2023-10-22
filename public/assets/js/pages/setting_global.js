// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let nama_instansi = form.find("input[name=nama_instansi]").val();
        let alamat = form.find("input[name=alamat]").val();
        let nomor_hp_wa = form.find("input[name=nomor_hp_wa]").val();

        if(nama_instansi.length > 100){
            invalidMessage(form.find("input[name=nama_instansi]"), 'Nama instansi maximal 100 karakter');
            return false;
        }
        if(alamat.length > 255){
            invalidMessage(form.find("textarea[name=alamat]"), 'Alamat maximal 255 karakter');
            return false;
        }
        if(nomor_hp_wa.length > 13){
            invalidMessage(form.find("input[name=nomor_hp_wa]"), 'Nomor hp wa maximal 13 karakter');
            return false;
        }
        

        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Simpan data",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {
                $(".loading-container-1").fadeIn(100);
                $.ajax({
                    type: 'POST',
                    url: "/setting-global",
                    data: {
                        nama_instansi: nama_instansi,
                        alamat: alamat,
                        nomor_hp_wa: nomor_hp_wa
                    },
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success")
                        } else {
                            sweetAlert("", data.message, "error")
                        }
                    },
                    error: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        sweetAlert("", data.responseJSON.message, "error")
                    }
                });
            }
        })
    }
});