let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/user",
            data: function (d) {
                d.filter_record = params;
            }
        },
        columns: [{
                data: "action",
                name: "action"
            },
            {
                data: "nama",
                name: "nama"
            },
            {
                data: "username",
                name: "username"
            },
            {
                data: "status_user",
                name: "status_user"
            },
        ],
        columnDefs: [{
            targets: [0],
            orderable: false,
            className: 'w-col-action'
        }],
        order: [
            [1, 'asc']
        ],
        fixedColumns: {
            left: 1
        },
        // scrollX: true,
        deferRender: true
    });
});

// FILTER 
$("#form-filter").submit(function (e) {
    e.preventDefault();
    params = $('#form-filter').serialize();
    table.ajax.reload();
});

$("#btn-reset").click(function (e) {
    e.preventDefault();
    resetForm('form-filter');
    params = $('#form-filter').serialize();
    table.ajax.reload();
});

// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let nama = form.find("input[name=nama]").val();
        let username = form.find("input[name=username]").val();
        let password = form.find("input[name=password]").val();
        let konfirmasi_password = form.find("input[name=konfirmasi_password]").val();

        if (username.indexOf(' ') !== -1) {
            invalidMessage(form.find("input[name=username]"), 'Username tidak boleh mengandung spasi');
            return false;
        }
        if (password.indexOf(' ') !== -1) {
            invalidMessage(form.find("input[name=password]"), 'Password tidak boleh mengandung spasi');
            return false;
        }
        if(password.length < 8){
            invalidMessage(form.find("input[name=password]"), 'Password minimal 8 karakter');
            return false;
        }
        if(password != konfirmasi_password){
            invalidMessage(form.find("input[name=konfirmasi_password]"), 'Konfirmasi password tidak sesuai');
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
                    url: "/user",
                    data: {
                        nama: nama,
                        username: username,
                        password: password
                    },
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success")
                            $('#modalAddData').modal('hide');
                            table.ajax.reload();
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

// EDIT DATA
$(document).on('click', '.btn-edit', function (e) {
    e.preventDefault();
    let dataID = $(this).data('id');
    $(".loading-container-1").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/user/" + dataID,
        dataType: 'json',
        success: function (data, jqXHR) {
            let form = $('#form-edit');
            form.find("input[name=user_id]").val(data.user_id);
            form.find("input[name=nama]").val(data.nama);
            form.find("input[name=username]").val(data.username);
            form.find("select[name=status]").val(data.status).trigger("change");
            $(".loading-container-1").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container-1").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});


$("#btn-update").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-edit');
    if (isValid == true) {
        let form = $('#form-edit');
        let user_id = form.find("input[name=user_id]").val();
        let nama = form.find("input[name=nama]").val();
        let username = form.find("input[name=username]").val();
        let status = form.find("select[name=status]").val();
        let password = form.find("input[name=password]").val();
        let konfirmasi_password = form.find("input[name=konfirmasi_password]").val();

        if (username.indexOf(' ') !== -1) {
            invalidMessage(form.find("input[name=username]"), 'Username tidak boleh mengandung spasi');
            return false;
        }
        if (password != '' && password.indexOf(' ') !== -1) {
            invalidMessage(form.find("input[name=password]"), 'Password tidak boleh mengandung spasi');
            return false;
        }
        if(password != '' && password.length < 8){
            invalidMessage(form.find("input[name=password]"), 'Password minimal 8 karakter');
            return false;
        }
        if(password != '' && password != konfirmasi_password){
            invalidMessage(form.find("input[name=konfirmasi_password]"), 'Konfirmasi password tidak sesuai');
            return false;
        }

        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Simpan perubahan data",
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
                    type: 'PUT',
                    url: "/user/" + user_id,
                    data: {
                        nama: nama,
                        username: username,
                        status: status,
                        password: password
                    },
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success");
                            $('#modalEditData').modal('hide');
                            table.ajax.reload();
                        } else {
                            sweetAlert("", data.message, "error");
                        }
                    },
                    error: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        sweetAlert("", data.responseJSON.message, "error")
                    }
                });
            }
        });
    }
});

// DELETE DATA
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    let dataID = $(this).data('id');
    Swal.fire({
        title: 'Apakah anda yakin ?',
        text: "Hapus data secara permanen",
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
                type: 'DELETE',
                url: "/user/" + dataID,
                dataType: 'json',
                success: function (data, jqXHR) {
                    $(".loading-container-1").fadeOut(100);
                    if (data.status == 'success') {
                        sweetAlert("", data.message, "success")
                        table.ajax.reload();
                    } else {
                        sweetAlert("", data.message, "error")
                    }
                },
                error: function (data, jqXHR) {
                    sweetAlert("", data.responseJSON.message, "error")
                }
            });
        }
    })
});
