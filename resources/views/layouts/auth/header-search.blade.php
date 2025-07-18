<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{$title}} | St Pamdes</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="../assets/img/logo-2.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/auth/css/my-style.css">

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['../assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/atlantis.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link href="../assets/auth/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper overlay-sidebar">
        <div class='loading-container-1'>
            <div class="loading-overlay-1"></div>
            <div class='loader-1'>
                <div class='loader--dot-1'></div>
                <div class='loader--dot-1'></div>
                <div class='loader--dot-1'></div>
                <div class='loader--dot-1'></div>
                <div class='loader--dot-1'></div>
                <div class='loader--dot-1'></div>
                <div class='loader--text-1'></div>
            </div>
        </div>
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="blue2">
                <a href="/" class="logo text-white font-weight-bolder">
                    <img src="../assets/img/logo.png" alt="navbar brand" class="navbar-brand"> St Pamdes
                </a>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
                <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                    <a href="/" class="btn btn-white btn-sm btn-round mr-2">Login</a>
                </ul>
            </nav>
            <!-- End Navbar -->
        </div>