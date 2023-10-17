<footer class="footer">
    <div class="container-fluid">
        <div class="copyright ml-auto">
            2023, Developed by <a href="#">Qkoh St</a>
        </div>
    </div>
</footer>
</div>

</div>
<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.3.2.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>

<!-- jQuery UI -->
<script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


<!-- Chart JS -->
<script src="../assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="../assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<!-- <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script> -->

<!-- jQuery Vector Maps -->
<script src="../assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="../assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Sweet Alert -->
<!-- <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script> -->
<script src="../assets/auth/plugins/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- Atlantis JS -->
<script src="../assets/js/atlantis.min.js"></script>
<!-- Datatables -->
<script src="../../assets/js/plugin/datatables/datatables.min.js"></script>
<script src="../../assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Atlantis DEMO methods, don't include it in your project! -->
<!-- <script src="../assets/js/setting-demo.js"></script>
<script src="../assets/js/demo.js"></script> -->


<script>
    $(window).on("load", function() {
        $(".loading-container-1").fadeOut(500);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {
        setActiveSidebar();
        $('.select2').select2();
    });

    $(".btn-toggle").click(function() {
        $('#table-data').find('thead').addClass('d-none');
        params = $('#form-filter').serialize();
        table.ajax.reload();
    });

    function setActiveSidebar() {
        var currentURL = window.location.href;

        // Loop melalui setiap elemen dengan class "nav-item"
        $('.nav-item').each(function() {
            var href = $(this).find('a').attr('href');

            if (currentURL.indexOf(href) !== -1) {
                $(this).addClass('active');
            }
        });

        $('.collapse').each(function() {
            var href = $(this).find('a').attr('href');

            if (currentURL.indexOf(href) !== -1) {
                $(this).closest('.nav-item').addClass('active');
                $(this).addClass('show');
            }
        });

        $('li').each(function() {
            var href = $(this).find('a').attr('href');

            if (currentURL.indexOf(href) !== -1) {
                $(this).addClass('active');
            }
        });
    }

    function checkValidateForm(form_id) {
        let result = true;
        // validate required
        $('#' + form_id).find('.required').each(function() {
            // $(this).removeClass('is-invalid');
            let val = $(this).val();
            let name = $(this).attr('name');
            name = name.replaceAll("[]", "");
            $('.' + name + '-invalid-message').remove();

            if (val == '' || val == []) {
                // $(this).addClass('is-invalid');
                let message_name = name.replaceAll("_", " ");
                message_name = message_name.charAt(0).toUpperCase() + message_name.slice(1);
                let invalid_message = `<span class="text-sm text-danger ${name}-invalid-message">${message_name} wajib diisi</span>`;
                let div = $(this).closest('div');
                div.append(invalid_message);
                result = false;
            }
        });
        return result;
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

    $(document).on('change', '.form-control', function(e) {
        e.preventDefault();
        $(this).removeClass('is-invalid');
        let name = $(this).attr('name');
        name = name.replaceAll("[]", "");
        $('.' + name + '-invalid-message').remove();
    });

    $('.custom-file-input').change(function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);

        let name = $(this).attr('name');
        $('.' + name + '-invalid-message').remove();
    });

    function resetForm(form_id) {
        $('#' + form_id).find('input').each(function() {
            if ($(this).attr('type') == 'radio') {
                $(this).prop('checked', false);
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

    $('#modalAddData').on('hidden.bs.modal', function() {
        resetForm('form-add');
    });

    $('#modalEditData').on('hidden.bs.modal', function() {
        resetForm('form-edit');
    });

    // // UNTUK DI HALAMAN DASHBOARD
    // Circles.create({
    //     id: 'circles-1',
    //     radius: 45,
    //     value: 60,
    //     maxValue: 100,
    //     width: 7,
    //     text: 5,
    //     colors: ['#f1f1f1', '#FF9E27'],
    //     duration: 400,
    //     wrpClass: 'circles-wrp',
    //     textClass: 'circles-text',
    //     styleWrapper: true,
    //     styleText: true
    // })

    // Circles.create({
    //     id: 'circles-2',
    //     radius: 45,
    //     value: 70,
    //     maxValue: 100,
    //     width: 7,
    //     text: 36,
    //     colors: ['#f1f1f1', '#2BB930'],
    //     duration: 400,
    //     wrpClass: 'circles-wrp',
    //     textClass: 'circles-text',
    //     styleWrapper: true,
    //     styleText: true
    // })

    // Circles.create({
    //     id: 'circles-3',
    //     radius: 45,
    //     value: 40,
    //     maxValue: 100,
    //     width: 7,
    //     text: 12,
    //     colors: ['#f1f1f1', '#F25961'],
    //     duration: 400,
    //     wrpClass: 'circles-wrp',
    //     textClass: 'circles-text',
    //     styleWrapper: true,
    //     styleText: true
    // })

    // var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

    // var mytotalIncomeChart = new Chart(totalIncomeChart, {
    //     type: 'bar',
    //     data: {
    //         labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
    //         datasets: [{
    //             label: "Total Income",
    //             backgroundColor: '#ff9e27',
    //             borderColor: 'rgb(23, 125, 255)',
    //             data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
    //         }],
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         legend: {
    //             display: false,
    //         },
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     display: false //this will remove only the label
    //                 },
    //                 gridLines: {
    //                     drawBorder: false,
    //                     display: false
    //                 }
    //             }],
    //             xAxes: [{
    //                 gridLines: {
    //                     drawBorder: false,
    //                     display: false
    //                 }
    //             }]
    //         },
    //     }
    // });

    // $('#lineChart').sparkline([105, 103, 123, 100, 95, 105, 115], {
    //     type: 'line',
    //     height: '70',
    //     width: '100%',
    //     lineWidth: '2',
    //     lineColor: '#ffa534',
    //     fillColor: 'rgba(255, 165, 52, .14)'
    // });
</script>
@if(isset($pagejs))
<script src="../assets/js/pages/{{$pagejs}}"></script>
@endif
</body>

</html>