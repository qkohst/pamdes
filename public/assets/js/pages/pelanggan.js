let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/pelanggan",
            data: function (d) {
                d.filter_record = params;
            }
        },
        columns: [{
                data: "action",
                name: "action"
            },
            {
                data: "kode",
                name: "kode"
            },
            {
                data: "nama_lengkap",
                name: "nama_lengkap"
            },
            {
                data: "nomor_hp_wa",
                name: "nomor_hp_wa"
            },
            {
                data: "alamat",
                name: "alamat"
            },
            {
                data: "status_str",
                name: "status_str"
            },
        ],
        columnDefs: [{
            targets: [0],
            orderable: false,
            searchable: false,
            className: 'w-col-action'
        }],
        order: [
            [1, 'asc']
        ],
        fixedColumns: {
            left: 1
        },
        deferRender: true,
        language: {
            sEmptyTable: "Tidak ada data yang tersedia dalam tabel",
            sInfo: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            sInfoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
            sInfoFiltered: "(disaring dari total _MAX_ data)",
            sInfoPostFix: "",
            sInfoThousands: ".",
            sLengthMenu: "Tampilkan _MENU_ data",
            sLoadingRecords: "Memuat...",
            sProcessing: "Sedang memproses...",
            sSearch: "Cari:",
            sZeroRecords: "Data tidak ditemukan",
            oPaginate: {
                sFirst: "Pertama",
                sLast: "Terakhir",
                sNext: "Selanjutnya",
                sPrevious: "Sebelumnya"
            },
        }
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

$('#kode_pelanggan').focus(function(){
    if($(this).val() == '<AUTO GENERATE>'){
        $(this).val('');
    }
});

$('#kode_pelanggan').focusout(function(){
    if($(this).val() == ''){
        $(this).val('<AUTO GENERATE>');
    }
})

// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let kode_pelanggan = form.find("input[name=kode_pelanggan]").val();
        let nama_lengkap = form.find("input[name=nama_lengkap]").val();
        let nomor_hp_wa = form.find("input[name=nomor_hp_wa]").val();
        let alamat = form.find("textarea[name=alamat]").val();

        if(kode_pelanggan != '<AUTO GENERATE>' && kode_pelanggan.length > 7){
            invalidMessage(form.find("input[name=kode_pelanggan]"), 'Kode pelanggan maximal 7 karakter');
            return false;
        }
        if(nama_lengkap.length > 100){
            invalidMessage(form.find("input[name=nama_lengkap]"), 'Nama lengkap maximal 100 karakter');
            return false;
        }
        if(nomor_hp_wa.length > 13){
            invalidMessage(form.find("input[name=nomor_hp_wa]"), 'Nomor hp wa maximal 13 karakter');
            return false;
        }
        if(alamat.length > 255){
            invalidMessage(form.find("textarea[name=alamat]"), 'Alamat maximal 255 karakter');
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
                    url: "/pelanggan",
                    data: {
                        kode_pelanggan: kode_pelanggan,
                        nama_lengkap: nama_lengkap,
                        nomor_hp_wa: nomor_hp_wa,
                        alamat: alamat,
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
        url: "/pelanggan/" + dataID,
        dataType: 'json',
        success: function (data, jqXHR) {
            console.log(data);
            let form = $('#form-edit');
            form.find("input[name=pelanggan_id]").val(data.pelanggan_id);
            form.find("input[name=kode_pelanggan]").val(data.kode_pelanggan);
            form.find("input[name=nama_lengkap]").val(data.nama_lengkap);
            form.find("input[name=nomor_hp_wa]").val(data.nomor_hp_wa);
            form.find("textarea[name=alamat]").val(data.alamat);
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
        let pelanggan_id = form.find("input[name=pelanggan_id]").val();
        let nama_lengkap = form.find("input[name=nama_lengkap]").val();
        let nomor_hp_wa = form.find("input[name=nomor_hp_wa]").val();
        let alamat = form.find("textarea[name=alamat]").val();
        let status = form.find("select[name=status]").val();

        if(nama_lengkap.length > 100){
            invalidMessage(form.find("input[name=nama_lengkap]"), 'Nama lengkap maximal 100 karakter');
            return false;
        }
        if(nomor_hp_wa.length > 13){
            invalidMessage(form.find("input[name=nomor_hp_wa]"), 'Nomor hp wa maximal 13 karakter');
            return false;
        }
        if(alamat.length > 255){
            invalidMessage(form.find("textarea[name=alamat]"), 'Alamat maximal 255 karakter');
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
                    url: "/pelanggan/" + pelanggan_id,
                    data: {
                        nama_lengkap: nama_lengkap,
                        nomor_hp_wa: nomor_hp_wa,
                        alamat: alamat,
                        status: status,
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
                url: "/pelanggan/" + dataID,
                dataType: 'json',
                success: function (data, jqXHR) {
                    $(".loading-container-1").fadeOut(100);
                    if (data.status == 'success') {
                        sweetAlert("", data.message, "success")
                        table.ajax.reload();
                    } else {
                        sweetAlert("", data.message, "error")
                        table.ajax.reload();
                    }
                },
                error: function (data, jqXHR) {
                    sweetAlert("", data.responseJSON.message, "error")
                }
            });
        }
    })
});

// DOWNLOAD FROMAT IMPORT
$("#btn-dowload-format").click(function (e) {
    e.preventDefault();
    window.open(baseUrl+'/pelanggan/import', '_blank');
});

// UPLOAD DATA
$("#btn-upload").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-import');
    if (isValid == true) {
        let file_import = $("[name=file_import]")[0].files[0];
        let payload_data = new FormData();
        payload_data.append('file_import', file_import);

        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Import data",
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
                    url: "/pelanggan/import",
                    data: payload_data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        if (data.status == 'success') {
                            sweetAlert("", data.message, "success");
                            $('#modalImportData').modal('hide');
                            table.ajax.reload();
                        } else {
                            sweetAlert("", data.message, "error");
                            $('#modalImportData').modal('hide');
                        }
                    },
                    error: function (data, jqXHR) {
                        $(".loading-container-1").fadeOut(100);
                        $('#modalImportData').modal('hide');
                        sweetAlert("", data.responseJSON.message, "error")
                    }
                });
            }
        })
    }
});

// HAPUS FILE YANG DIPILIH KETIKA POP UP DITUTUP
$('#modalImportData').on('hidden.bs.modal', function() {
    $('#file_import').val('');
});

// EXPORT EXCEL
$("#btn-export-excel").click(function (e) {
    e.preventDefault();
    params = $('#form-filter').serialize();
    window.open(baseUrl+'/pelanggan/export?'+params, '_blank');
});
