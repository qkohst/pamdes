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
        scrollX: true,
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
    let isValid = checkValidateForm('tambah-user');

    if (isValid == true) {
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
                $(".loading-container").fadeIn(100);
                let form = $('#modal-tambah');
                let nama_lengkap = form.find("input[name=nama_lengkap]").val();
                let gelar = form.find("input[name=gelar]").val();
                let nip = form.find("input[name=nip]").val();
                let nuptk = form.find("input[name=nuptk]").val();
                let jenis_kelamin = form.find("select[name=jenis_kelamin]").val();
                let tempat_lahir = form.find("input[name=tempat_lahir]").val();
                let tanggal_lahir = form.find("input[name=tanggal_lahir]").val();
                let alamat = form.find("textarea[name=alamat]").val();

                $.ajax({
                    type: 'POST',
                    url: "/guru",
                    data: {
                        nama_lengkap: nama_lengkap,
                        gelar: gelar,
                        nip: nip,
                        nuptk: nuptk,
                        jenis_kelamin: jenis_kelamin,
                        tempat_lahir: tempat_lahir,
                        tanggal_lahir: tanggal_lahir,
                        alamat: alamat
                    },
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success")
                            $('#modal-tambah').modal('hide');
                            $('#btn-save').prop('disabled', false);
                            table.ajax.reload();
                        } else {
                            sweetAlert("", data.message, "error")
                            $('#btn-save').prop('disabled', false);
                        }
                    },
                    error: function (data, jqXHR) {
                        $(".loading-container").fadeOut(100);
                        $('#btn-save').prop('disabled', false);
                        sweetAlert("", data.responseJSON.message, "error")
                    }
                });
            }
        })
    }
});

$('#modal-tambah').on('hidden.bs.modal', function () {
    resetForm('modal-tambah');
});

// EDIT DATA
$(document).on('click', '.btn-edit', function (e) {
    e.preventDefault();
    let dataID = $(this).data('id');
    $(".loading-container").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/guru/" + dataID,
        dataType: 'json',
        success: function (data, jqXHR) {
            let form = $('#modal-edit');
            form.find("input[name=guru_id]").val(data.guru_id);
            form.find("input[name=nama_lengkap]").val(data.nama_lengkap);
            form.find("input[name=gelar]").val(data.gelar);
            form.find("input[name=nip]").val(data.nip);
            form.find("input[name=nuptk]").val(data.nuptk);
            form.find("select[name=jenis_kelamin]").val(data.jenis_kelamin).trigger("change");
            form.find("input[name=tempat_lahir]").val(data.tempat_lahir);
            form.find("input[name=tanggal_lahir]").val(data.tanggal_lahir);
            form.find("textarea[name=alamat]").val(data.alamat);
            $(".loading-container").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});

$('#modal-edit').on('hidden.bs.modal', function () {
    resetForm('modal-edit');
});

$("#btn-update").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('modal-edit');
    if (isValid == true) {
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
                $(".loading-container").fadeIn(100);
                let form = $('#modal-edit');
                let guru_id = form.find("input[name=guru_id]").val();
                let nama_lengkap = form.find("input[name=nama_lengkap]").val();
                let gelar = form.find("input[name=gelar]").val();
                let nip = form.find("input[name=nip]").val();
                let nuptk = form.find("input[name=nuptk]").val();
                let jenis_kelamin = form.find("select[name=jenis_kelamin]").val();
                let tempat_lahir = form.find("input[name=tempat_lahir]").val();
                let tanggal_lahir = form.find("input[name=tanggal_lahir]").val();
                let alamat = form.find("textarea[name=alamat]").val();

                $.ajax({
                    type: 'PUT',
                    url: "/guru/" + guru_id,
                    data: {
                        nama_lengkap: nama_lengkap,
                        gelar: gelar,
                        nip: nip,
                        nuptk: nuptk,
                        jenis_kelamin: jenis_kelamin,
                        tempat_lahir: tempat_lahir,
                        tanggal_lahir: tanggal_lahir,
                        alamat: alamat
                    },
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success");
                            $('#modal-edit').modal('hide');
                            table.ajax.reload();
                        } else {
                            sweetAlert("", data.message, "error");
                        }
                    },
                    error: function (data, jqXHR) {
                        $(".loading-container").fadeOut(100);
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
            $(".loading-container").fadeIn(100);
            $.ajax({
                type: 'DELETE',
                url: "/guru/" + dataID,
                dataType: 'json',
                success: function (data, jqXHR) {
                    $(".loading-container").fadeOut(100);
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
