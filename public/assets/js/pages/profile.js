$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('edit-profile');
    if (isValid == true) {

        let nama = $("[name=nama]").val();
        let username = $("[name=username]").val();
        let foto_profile = $("[name=foto_profile]")[0].files[0];
        let password_lama = $("[name=password_lama]").val();
        let password_baru = $("[name=password_baru]").val();
        let konfirmasi_password = $("[name=konfirmasi_password]").val();
        if (password_lama != '' && password_baru == '') {
            invalidMessage($("[name=password_baru]"), 'Password baru harus diisi');
            return false;
        }
        if (password_baru != '' && password_lama == '') {
            invalidMessage($("[name=password_lama]"), 'Password lama harus diisi');
            return false;
        }
        if (konfirmasi_password != '' && password_lama == '') {
            invalidMessage($("[name=password_lama]"), 'Password lama harus diisi');
            return false;
        }
        if(password_baru != '' && password_baru.length < 8){
            invalidMessage($("[name=password_baru]"), 'Password baru minimal 8 karakter');
            return false;
        }
        if (password_lama != '' && password_baru != '' && (password_baru != konfirmasi_password)) {
            invalidMessage($("[name=konfirmasi_password]"), 'Konfirmasi password tidak sesuai');
            return false;
        }

        let payload_data = new FormData();
        payload_data.append('nama', nama);
        payload_data.append('username', username);
        payload_data.append('foto_profile', foto_profile);
        payload_data.append('password_lama', password_lama);
        payload_data.append('password_baru', password_baru);
        payload_data.append('konfirmasi_password', konfirmasi_password);

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
                    url: "/profile",
                    data: payload_data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data, jqXHR) {
                         $(".loading-container-1").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success")
                            $('#btn-save').prop('disabled', false);
                            location.reload();
                        } else {
                            sweetAlert("", data.message, "error")
                            $('#btn-save').prop('disabled', false);
                        }
                    },
                    error: function (data, jqXHR) {
                         $(".loading-container-1").fadeOut(100);
                        $('#btn-save').prop('disabled', false);
                        sweetAlert("", data.responseJSON.message, "error")
                    }
                });
            }
        })
    }
});
