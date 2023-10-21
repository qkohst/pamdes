let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/pembayaran",
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
            {
                data: "total_tagihan",
                name: "total_tagihan"
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
            [1, 'desc']
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

// GET TRANSAKSI BELUM DIBAYAR
$(document).on('change', '[name="pelanggan"]', function(e) {
    e.preventDefault();
    let pelanggan_id = $(this).val();
    $(".loading-container-1").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/pembayaran/transaksi",
        data:{
            pelanggan_id: pelanggan_id
        },
        dataType: 'json',
        success: function (result, jqXHR) {
            resetFormAdd();
            let form = $('#form-add');            
            let option = '<option value="">-- Pilih Data --</option>';
            result.data.forEach(item => {
                option += `<option value="${item.bulan_tahun}" 
                    data-transaksi_id="${item.transaksi_id}"
                    data-total_pemakaian="${item.total_pemakaian}"
                    data-tarif_per_meter="${item.tarif_per_meter}"
                    data-biaya_pemeliharaan="${item.biaya_pemeliharaan}"
                    data-biaya_administrasi="${item.biaya_administrasi}"
                    data-total_tagihan="${item.total_tagihan}"
                    >${item.bulan_tahun}</option>`;
            });
            form.find("select[name=bulan_tahun]").html(option);
            $(".loading-container-1").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container-1").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});

// RESET FORM ADD KETIKA MENGGANTI PELANGGAN 
function resetFormAdd(){
    let form = $('#form-add'); 
    form.find("input[name=transaksi_id]").val('');
    form.find("input[name=total_pemakaian]").val('');
    form.find("input[name=tarif_per_meter]").val('');
    form.find("input[name=biaya_pemeliharaan]").val('');
    form.find("input[name=biaya_administrasi]").val('');
    form.find("input[name=total_tagihan]").val('');
    form.find("input[name=input_pembayaran]").val('')
}

// SET DATA KETIKA MEMILIH BULAN TAHUN
$(document).on('change', '[name="bulan_tahun"]', function(e) {
    e.preventDefault();
    $(".loading-container-1").fadeIn(100);
    let val = $(this).find(':selected').val();
    if(val != ''){
        let transaksi_id = $(this).find(':selected').data('transaksi_id');
        let total_pemakaian = $(this).find(':selected').data('total_pemakaian');
        let tarif_per_meter = $(this).find(':selected').data('tarif_per_meter');
        let biaya_pemeliharaan = $(this).find(':selected').data('biaya_pemeliharaan');
        let biaya_administrasi = $(this).find(':selected').data('biaya_administrasi');
        let total_tagihan = $(this).find(':selected').data('total_tagihan');

        let form = $('#form-add'); 
        form.find("input[name=transaksi_id]").val(transaksi_id);
        form.find("input[name=total_pemakaian]").autoNumeric('set', total_pemakaian);
        form.find("input[name=tarif_per_meter]").autoNumeric('set', tarif_per_meter);
        form.find("input[name=biaya_pemeliharaan]").autoNumeric('set', biaya_pemeliharaan);
        form.find("input[name=biaya_administrasi]").autoNumeric('set', biaya_administrasi);
        form.find("input[name=total_tagihan]").autoNumeric('set', total_tagihan);
        form.find("input[name=input_pembayaran]").val('');
    }else{
        resetFormAdd();
    }
    $(".loading-container-1").fadeOut(100);
});

// SAVE DATA
$("#btn-save").click(function (e) {
    e.preventDefault();
    let isValid = checkValidateForm('form-add');
    if (isValid == true) {
        let form = $('#form-add');
        let transaksi_id = form.find("input[name=transaksi_id]").val();        
        let tarif_per_meter = form.find("input[name=tarif_per_meter]").autoNumeric('get');
        let biaya_pemeliharaan = form.find("input[name=biaya_pemeliharaan]").autoNumeric('get');
        let biaya_administrasi = form.find("input[name=biaya_administrasi]").autoNumeric('get');
        let total_tagihan = form.find("input[name=total_tagihan]").autoNumeric('get');
        let input_pembayaran = form.find("input[name=input_pembayaran]").autoNumeric('get');
        let tanggal_pembayaran = form.find("input[name=tanggal_pembayaran]").val();

        if(total_tagihan != input_pembayaran){
            invalidMessage(form.find("input[name=input_pembayaran]"), 'Input pembayaran harus sama dengan total tagihan');
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
                    url: "/pembayaran",
                    data: {
                        transaksi_id: transaksi_id,
                        tarif_per_meter: tarif_per_meter,
                        biaya_pemeliharaan: biaya_pemeliharaan,
                        biaya_administrasi: biaya_administrasi,
                        tanggal_pembayaran: tanggal_pembayaran,
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

// DETAIL DATA
$(document).on('click', '.btn-show', function (e) {
    e.preventDefault();
    let dataID = $(this).data('id');
    $(".loading-container-1").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/pembayaran/" + dataID,
        dataType: 'json',
        success: function (data, jqXHR) {
            console.log(data);
            let form = $('#form-detail');
            form.find("input[name=kode_transaksi]").val(data.kode_transaksi);
            form.find("input[name=bulan_tahun]").val(data.bulan_tahun);
            form.find("input[name=pelanggan]").val(data.pelanggan);
            form.find("input[name=total_pemakaian]").autoNumeric('set', data.total_pemakaian);
            form.find("input[name=total_tagihan]").autoNumeric('set', data.total_tagihan);
            form.find("input[name=tanggal_pembayaran]").val(data.tanggal_pembayaran);
            form.find("input[name=status]").val(data.status);
            $(".loading-container-1").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container-1").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});

// EXPORT EXCEL
$("#btn-export-excel").click(function (e) {
    e.preventDefault();
    params = $('#form-filter').serialize();
    window.open(baseUrl+'/pembayaran/export?'+params, '_blank');
});