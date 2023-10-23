let table;
let params = [];

$(document).ready(function () {
    table = $("#table-data").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/laporan",
            data: function (d) {
                d.filter_record = params;
            }
        },
        columns: [{
                data: "bulan_tahun_indo",
                name: "bulan_tahun_indo"
            },
            {
                data: "nama_pelanggan",
                name: "nama_pelanggan"
            },
            {
                data: "kode",
                name: "kode"
            },
            {
                data: "pemakaian_sebelumnya",
                name: "pemakaian_sebelumnya"
            },
            {
                data: "pemakaian_saat_ini",
                name: "pemakaian_saat_ini"
            },
            {
                data: "total_pemakaian",
                name: "total_pemakaian"
            },
            {
                data: "biaya_air",
                name: "biaya_air"
            },
            {
                data: "biaya_pemeliharaan",
                name: "biaya_pemeliharaan"
            },
            {
                data: "biaya_administrasi",
                name: "biaya_administrasi"
            },
            {
                data: "total_tagihan",
                name: "total_tagihan"
            },
            {
                data: "bayar",
                name: "bayar"
            },
            {
                data: "tanggal_pembayaran",
                name: "tanggal_pembayaran"
            },
            {
                data: "status_str",
                name: "status_str"
            },
        ],
        columnDefs: [
            {
                targets: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                orderable: false,
                searchable: false,
            },
            {
                targets: [1],
                className: 'w-col-nama-pelanggan'
            },
            {
                targets: [0, 11],
                className: 'w-col-tanggal'
            }
        ],
        order: [
            [0, 'desc']
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

// EXPORT EXCEL
$("#btn-export-excel").click(function (e) {
    e.preventDefault();
    params = $('#form-filter').serialize();
    window.open(baseUrl+'/pembayaran/export?'+params, '_blank');
});

// PRINT SLIP TUNGGAL
$(document).on('click', '.btn-print', function (e) {
    e.preventDefault();
    let transaksi_id = $(this).data('id');
    window.open(baseUrl+'/pembayaran/print?transaksi_id='+transaksi_id, '_blank');
});
