<!-- Required Js -->
<script src="../assets/auth/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="../assets/auth/js/vendor-all.min.js"></script>
<script src="../assets/auth/plugins/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(window).on("load", function() {
        $(".loading-container-1").fadeOut(500);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function checkValidateForm() {
        let result = true;
        // validate required
        $('.required').each(function() {
            // $(this).removeClass('is-invalid');
            let val = $(this).val();
            let name = $(this).attr('name');
            $('.' + name + '-invalid-message').remove();

            if (val == '') {
                // $(this).addClass('is-invalid');
                let message_name = name.replaceAll("_", " ");
                let invalid_message = `<span class="text-sm text-danger ${name}-invalid-message">${message_name} wajib diisi</span>`;
                let div = $(this).closest('div');
                div.after(invalid_message);
                result = false;
            }
        });
        return result;
    }

    $(document).on('keyup', '.required', function(e) {
        e.preventDefault();
        $(this).removeClass('is-invalid');
        let name = $(this).attr('name');
        $('.' + name + '-invalid-message').remove();
    });

    $(document).on('change', '.required', function(e) {
        e.preventDefault();
        $(this).removeClass('is-invalid');
        let name = $(this).attr('name');
        $('.' + name + '-invalid-message').remove();
    });
</script>

@if(isset($pagejs))
<script src="../assets/js/pages/{{$pagejs}}"></script>
@endif

</body>

</html>