<footer class="footer">
    <div class="container-fluid">
        <div class="copyright ml-auto">
            StPamdes V.10 | Developed by <a href="https://www.instagram.com/qkoh_st" target="_blank">Qkoh St</a> | 2023
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

<!-- jQuery Vector Maps -->
<script src="../assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="../assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Sweet Alert -->
<script src="../assets/auth/plugins/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- Atlantis JS -->
<script src="../assets/js/atlantis.min.js"></script>
<!-- Datatables -->
<script src="../../assets/js/plugin/datatables/datatables.min.js"></script>
<script src="../../assets/plugins/select2/js/select2.full.min.js"></script>

<script src="../../assets/plugins/autoNumeric/autoNumeric.js"></script>


<script>
    $(window).on("load", function() {
        $(".loading-container-1").fadeOut(500);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var baseUrl = "{{ url('/') }}";


    $(document).ready(function() {
        setActiveSidebar();
        $('.select2').select2();
        setAutonumeric();
    });

    // $(".btn-toggle").click(function() {
    //     $('#table-data').find('thead').addClass('d-none');
    //     params = $('#form-filter').serialize();
    //     table.ajax.reload();
    // });

    function setAutonumeric() {
        $('.autonumeric').each(function() {
            $(this).autoNumeric('init', {
                mDec: 0,
                aSep: '.',
                aDec: ',',
                vMin: '0'
                // aPad: false
            });
        });

        $('.autonumeric-2').each(function() {
            $(this).autoNumeric('init', {
                mDec: 2,
                aSep: '.',
                aDec: ',',
                vMin: '0'
                // aPad: false
            });
        });
    }

    function setActiveSidebar() {
        var currentURL = window.location.href;

        // untuk main menu 
        $('.nav-item').each(function() {
            var href = $(this).find('a').attr('href');

            if (currentURL.indexOf(href) !== -1) {
                $(this).addClass('active');
            }
        });

        // untuk submenu
        $('.sub-menu').each(function() {
            var href = $(this).find('a').attr('href');

            if (currentURL.indexOf(href) !== -1) {
                $(this).addClass('active');
                $(this).closest('.nav-item').addClass('active');
                $(this).closest('.collapse').addClass('show');
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

    $('#modalAddData').on('hidden.bs.modal', function() {
        resetForm('form-add');
    });

    $('#modalEditData').on('hidden.bs.modal', function() {
        resetForm('form-edit');
    });

    $('#modalPrintAll').on('hidden.bs.modal', function() {
        resetForm('form-print-all');
    });

    $('.hanya-angka').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('.no-spasi').on('input', function() {
        this.value = this.value.replace(/\s/g, '');
    });

    $('.first-uppercase').on('input', function() {
        let entered = $(this).val();
        let firstLetter = entered.charAt(0).toUpperCase();
        let rest = entered.substring(1);
        $(this).val(firstLetter + rest);
    });

    $('.uppercase').on('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@if(isset($pagejs))
<script src="../assets/js/pages/{{$pagejs}}"></script>
@endif
</body>

</html>