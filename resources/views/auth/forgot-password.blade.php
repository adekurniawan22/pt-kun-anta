<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= url('assets/onedash') ?>/images/icon.jpg" type="image/png" />
    <!-- Bootstrap CSS -->
    <link href="<?= url('assets/onedash') ?>/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= url('assets/onedash') ?>/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="<?= url('assets/onedash') ?>/css/style.css" rel="stylesheet" />
    <link href="<?= url('assets/onedash') ?>/plugins/notifications/css/lobibox.min.css" rel="stylesheet" />
    <link href="<?= url('assets/onedash') ?>/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- loader-->
    <link href="<?= url('assets/onedash') ?>/css/pace.min.css" rel="stylesheet" />
    <title>Lupa Password</title>
</head>

<body>
    <!--start wrapper-->
    <div class="wrapper d-flex align-items-center justify-content-center" style="padding-bottom: 0px !important">
        <!--start content-->
        <main class="authentication-content pt-0 w-100">
            <div class="authentication-card pt-0 w-100">
                <div class="container-fluid">
                    <div class="card shadow rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <!-- Form and forgot password content -->
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <div class="card-body">
                                    <h4 class="card-title mb-3 ms-0 ms-md-5 text-start ps-3">LUPA PASSWORD</h4>
                                    @if (session('success'))
                                        <div
                                            class="alert border-0 bg-light-success alert-dismissible fade show py-2 ms-md-5">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <div class="text-success">{{ session('success') }}</div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    @if (session('error'))
                                        <div
                                            class="alert border-0 bg-light-danger alert-dismissible fade show py-2 ms-md-5">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i></div>
                                                <div class="ms-3">
                                                    <div class="text-danger">{{ session('error') }}</div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form class="form-body" method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="row g-3 ms-0 ms-md-5">
                                            <div class="col-12">
                                                <div class="ms-auto position-relative">
                                                    <div
                                                        class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                        <i class="bi bi-envelope-fill"></i>
                                                    </div>
                                                    <input type="email"
                                                        class="form-control radius-30 ps-5 @error('email') is-invalid @enderror"
                                                        id="email" placeholder="Masukkan email" name="email"
                                                        value="{{ old('email') }}" required>
                                                </div>
                                                @error('email')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-dark radius-30">Kirim Tautan
                                                        Reset Password</button>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <p class="mb-0">Kembali ke <a href="{{ route('login') }}">Login</a>
                                                </p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Image banner -->
                            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                                <img src="<?= url('assets/onedash') ?>/images/banner.jpg" class="img-fluid pb-2 pe-5"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap bundle JS -->
    <script src="<?= url('assets/onedash') ?>/js/jquery.min.js"></script>

    <!--notification js -->
    <script src="<?= url('assets/onedash') ?>/plugins/notifications/js/lobibox.min.js"></script>
    <script src="<?= url('assets/onedash') ?>/plugins/notifications/js/notifications.min.js"></script>
    <script src="<?= url('assets/onedash') ?>/plugins/notifications/js/notification-custom-script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            //Tidak Aktif
            var userSelect = document.getElementById('userSelect');
            userSelect.addEventListener('change', function() {
                var emailInput = document.getElementById('email');
                var passwordInput = document.getElementById('password');

                if (this.value === "") {
                    emailInput.value = '';
                    passwordInput.value = '';
                } else {
                    emailInput.value = this.value;
                    passwordInput.value = 'password';
                }

                if (this.value !== '') {
                    this.form.submit();
                }
            });
            //Tidak Aktif

            // Notification Success
            @if (session()->has('success'))
                function notifSuccess() {
                    Lobibox.notify('success', {
                        title: 'Berhasil',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: '{{ session('success') }}'
                    });
                }
                notifSuccess();
            @endif

            // Notification Info
            @if (session()->has('info'))
                function notifSuccess() {
                    Lobibox.notify('info', {
                        title: 'Info',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: '{{ session('info') }}'
                    });
                }
                notifSuccess();
            @endif

            // Notification Error
            @if (session()->has('error'))
                function notifError() {
                    Lobibox.notify('error', {
                        title: 'Gagal',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-x-circle',
                        msg: '{{ session('error') }}'
                    });
                }
                notifError();
            @endif
        });

        document.addEventListener("DOMContentLoaded", function() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginAccountModal'));
            loginModal.show();
        });
    </script>
    <script src="<?= url('assets/onedash') ?>/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="<?= url('assets/onedash') ?>/js/jquery.min.js"></script>
</body>

</html>
