// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let tarif_per_meter = form.find("input[name=tarif_per_meter]").autoNumeric('get');
        let biaya_pemeliharaan = form.find("input[name=biaya_pemeliharaan]").autoNumeric('get');
        let biaya_administrasi = form.find("input[name=biaya_administrasi]").autoNumeric('get');

        if(tarif_per_meter == 0){
            invalidMessage(form.find("input[name=tarif_per_meter]"), 'Tarif Per M3 tidak boleh 0');
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
                    url: "/tarif-air",
                    data: {
                        tarif_per_meter: tarif_per_meter,
                        biaya_pemeliharaan: biaya_pemeliharaan,
                        biaya_administrasi: biaya_administrasi
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