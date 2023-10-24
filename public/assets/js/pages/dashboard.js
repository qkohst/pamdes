$(document).ready(function () {
    $(".loading-container-1").fadeIn(100);
    $.ajax({
        type: 'GET',
        url: "/dashboard/rekap",
        dataType: 'json',
        success: function (result, jqXHR) {
            let data = result.data;
            console.log(data)
            // REKAP PELANGGAN 
            Circles.create({
                id: 'circles-1',
                radius: 45,
                value: data.pelanggan.aktif,
                maxValue: data.pelanggan.total,
                width: 7,
                text: data.pelanggan.aktif,
                colors: ['#f1f1f1', '#2BB930'],
                duration: 400,
                wrpClass: 'circles-wrp',
                textClass: 'circles-text',
                styleWrapper: true,
                styleText: true
            })
        
            Circles.create({
                id: 'circles-2',
                radius: 45,
                value: data.pelanggan.non_aktif,
                maxValue: data.pelanggan.total,
                width: 7,
                text: data.pelanggan.non_aktif,
                colors: ['#f1f1f1', '#F25961'],
                duration: 400,
                wrpClass: 'circles-wrp',
                textClass: 'circles-text',
                styleWrapper: true,
                styleText: true
            })
        
            Circles.create({
                id: 'circles-3',
                radius: 45,
                value: data.pelanggan.total,
                maxValue: data.pelanggan.total,
                width: 7,
                text: data.pelanggan.total,
                colors: ['#f1f1f1', '#FF9E27'],
                duration: 400,
                wrpClass: 'circles-wrp',
                textClass: 'circles-text',
                styleWrapper: true,
                styleText: true
            })
            // END REKAP PELANGGAN

            // REKAP PEMBAYARAN
            $('#pembayaran-bulan-ini').text(data.pembayaran.bulan_ini);
            $('#pembayaran-tahun-ini').text(data.pembayaran.tahun_ini);
            $('#total-pembayaran').text(data.pembayaran.total);
            // END REAP PEMBAYARAN

            // CARD STATISTIC
            var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');
            var mytotalIncomeChart = new Chart(totalIncomeChart, {
                type: 'bar',
                data: {
                    labels: data.statistic.label,
                    datasets: [{
                        label: "Total Pembayaran",
                        backgroundColor: '#ff9e27',
                        borderColor: 'rgb(23, 125, 255)',
                        data: data.statistic.value,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                display: false //this will remove only the label
                            },
                            gridLines: {
                                drawBorder: false,
                                display: false
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                drawBorder: false,
                                display: false
                            }
                        }]
                    },
                }
            });
            // END CARD STATISTIC
            $(".loading-container-1").fadeOut(100);
        },
        error: function (data, jqXHR) {
            $(".loading-container-1").fadeOut(100);
            alert(jqXHR.status);
        }
    });
});

