let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "/cek-tagihan",
            data: function (d) {
                d.filter_record = params;
            }
        },
        columns: [
            {
                data: "nama_pelanggan",
                name: "nama_pelanggan"
            },
            {
                data: "bulan_tahun_indo",
                name: "bulan_tahun_indo"
            },
            {
                data: "kode",
                name: "kode"
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
        columnDefs: [
            {
                targets: [0],
                className: 'w-col-nama-pelanggan'
            },
            {
                targets: [1],
                className: 'w-col-tanggal'
            }, 
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
    let kode_pelanggan = $("input[name=filter_kode]").val();
    if(kode_pelanggan == ''){
        sweetAlert("", 'Kode pelanggan tidak boleh kosong', "warning")
        return false;
    }
    params = $('#form-filter').serialize();
    table.ajax.reload();
});

$("#btn-reset").click(function (e) {
    e.preventDefault();
    resetForm('form-filter');
    params = $('#form-filter').serialize();
    table.ajax.reload();
});

function resetForm(form_id) {
    $('#' + form_id).find('input').each(function() {
        if ($(this).attr('type') == 'radio') {
            $(this).prop('checked', false);
        } else if ($(this).hasClass('uppercase')) {
            $(this).val('<AUTO GENERATE>');
        } else {
            $(this).val('');
        }
        let name = $(this).attr('name');
        name = name.replaceAll("[]", "");
        $('.' + name + '-invalid-message').remove();
    });

    $('#' + form_id).find('select.form-control').each(function() {
        let first_val = $(this).find('option:first').val();
        $(this).val(first_val);
        $(this).trigger('change.select2');
        let name = $(this).attr('name');
        name = name.replaceAll("[]", "");
        $('.' + name + '-invalid-message').remove();
    });

    $('#' + form_id).find('textarea').each(function() {
        $(this).empty();
        $(this).val('');
        let name = $(this).attr('name');
        name = name.replaceAll("[]", "");
        $('.' + name + '-invalid-message').remove();
    });

    $('#' + form_id).find('.note-editable').each(function() {
        $(this).empty();
    });

    $('#' + form_id).find('.duallistbox').each(function() {
        $(this).val([]).trigger('change');
        $(this).removeClass('is-invalid');
        let name = $(this).attr('name');
        name = name.replaceAll("[]", "");
        $('.' + name + '-invalid-message').remove();
        $('#form-dual-listbox').empty();
    });

}

function invalidMessage(form, message) {
    let name = form.attr('name');
    name = name.replaceAll("[]", "");
    $('.' + name + '-invalid-message').remove();
    let invalid_message = `<span class="text-sm text-danger ${name}-invalid-message">${message}</span>`;
    let div = form.closest('div');
    div.append(invalid_message);
    form.focus();
}

$(document).on('keyup', '.form-control', function(e) {
    e.preventDefault();
    $(this).removeClass('is-invalid');
    let name = $(this).attr('name');
    name = name.replaceAll("[]", "");
    $('.' + name + '-invalid-message').remove();
});