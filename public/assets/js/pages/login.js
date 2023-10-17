// Ketika form disubmit atau AJAX request dimulai
$("#form-login").on("submit", function (e) {
    e.preventDefault();

    let isValid = checkValidateForm();
    if (isValid == true) {
        $(".loading-container-1").fadeIn(100);
        let username = $("input[name=username]").val();
        let password = $("input[name=password]").val();
        $.ajax({
            type: 'POST',
            url: "/",
            data: {
                username: username,
                password: password
            },
            dataType: 'json',
            success: function (data, jqXHR) {
                $(".loading-container-1").fadeOut(100);
                if (data.status == 'success') {
                    swal("", data.message, "success")
                    window.location.href = "/dashboard";
                } else {
                    sweetAlert("", data.message, "error")
                }
            },
            error: function (data, jqXHR) {
                $(".loading-container-1").fadeOut(100);
                sweetAlert("", jqXHR.status, "error")
            }
        });

    }
});
