let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/pemakaian",
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
                data: "bulan_tahun_indo",
                name: "bulan_tahun_indo"
            },
            {
                data: "nama_pelanggan",
                name: "nama_pelanggan"
            },
            {
                data: "total_pemakaian",
                name: "total_pemakaian"
            },
        ],
        columnDefs: [
                {
                targets: [0],
                orderable: false,
                searchable: false,
                className: 'w-col-action'
            },
            {
                targets: [2],
                className: 'w-col-tanggal'
            },
            {
                targets: [3],
                className: 'w-col-nama-pelanggan'
            }
        ],
        order: [
            [1, 'desc']
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

// GET TRANSAKSI TERAKHIR
$(document).on('change', '[name="pelanggan"]', function(e) {
    e.preventDefault();
    let pelanggan_id = $(this).val();
    $(".loading-container-1").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/pemakaian/transaksi",
        data:{
            pelanggan_id: pelanggan_id
        },
        dataType: 'json',
        success: function (data, jqXHR) {
            console.log(data);
            let form = $('#form-add');
            form.find("input[name=pemakaian_sebelumnya]").autoNumeric('set', data.pemakaian_terakhir);
            $(".loading-container-1").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container-1").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});

// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let bulan_tahun = form.find("select[name=bulan_tahun]").val();        
        let pelanggan_id = form.find("select[name=pelanggan]").val();        
        let pemakaian_sebelumnya = form.find("input[name=pemakaian_sebelumnya]").autoNumeric('get');
        let pemakaian_saat_ini = form.find("input[name=pemakaian_saat_ini]").autoNumeric('get');

        if(pemakaian_saat_ini == 0){
            invalidMessage(form.find("input[name=pemakaian_saat_ini]"), 'Pemakaian saat ini tidak boleh 0');
            return false;
        }

        if(pemakaian_saat_ini <= pemakaian_sebelumnya){
            invalidMessage(form.find("input[name=pemakaian_saat_ini]"), 'Pemakaian saat ini harus lebih besar dari pemakaian sebelumnya');
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
                    url: "/pemakaian",
                    data: {
                        bulan_tahun: bulan_tahun,
                        pelanggan_id: pelanggan_id,
                        pemakaian_sebelumnya: pemakaian_sebelumnya,
                        pemakaian_saat_ini: pemakaian_saat_ini,
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
        url: "/pemakaian/" + dataID,
        dataType: 'json',
        success: function (data, jqXHR) {
            console.log(data);
            let form = $('#form-edit');
            form.find("input[name=transaksi_id]").val(data.transaksi_id);
            form.find("input[name=kode_transaksi]").val(data.kode_transaksi);
            form.find("input[name=bulan_tahun]").val(data.bulan_tahun);
            form.find("input[name=pelanggan]").val(data.pelanggan);
            form.find("input[name=pemakaian_sebelumnya]").autoNumeric('set', data.pemakaian_sebelumnya);
            form.find("input[name=pemakaian_saat_ini]").autoNumeric('set', data.pemakaian_saat_ini);
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
        let transaksi_id = form.find("input[name=transaksi_id]").val();
        let pemakaian_sebelumnya = form.find("input[name=pemakaian_sebelumnya]").autoNumeric('get');
        let pemakaian_saat_ini = form.find("input[name=pemakaian_saat_ini]").autoNumeric('get');

        if(pemakaian_saat_ini == 0){
            invalidMessage(form.find("input[name=pemakaian_saat_ini]"), 'Pemakaian saat ini tidak boleh 0');
            return false;
        }

        if(pemakaian_saat_ini <= pemakaian_sebelumnya){
            invalidMessage(form.find("input[name=pemakaian_saat_ini]"), 'Pemakaian saat ini harus lebih besar dari pemakaian sebelumnya');
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
                    url: "/pemakaian/" + transaksi_id,
                    data: {
                        pemakaian_saat_ini: pemakaian_saat_ini
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
                url: "/pemakaian/" + dataID,
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

// DOWNLOAD FROMAT IMPORT
$("#btn-dowload-format").click(function (e) {
    e.preventDefault();
    window.open(baseUrl+'/pemakaian/import', '_blank');
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
                    url: "/pemakaian/import",
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
    $('.file_import-invalid-message').remove();
});
