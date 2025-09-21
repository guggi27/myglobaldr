<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin panel login</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/administrator/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('/administrator/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('/administrator/css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Apr 7 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>

    <input type="hidden" id="baseUrl" value="{{ url('/') }}" />

    <script>
        const baseUrl = document.getElementById("baseUrl").value
    </script>

    <main class="auth-layout">
        <div class="auth-card">
            <section class="auth-form-container">
                <div class="auth-form-inner-container">

                    <div class="auth-logo">
                        <img src="{{ asset('/administrator/img/admin/logo.png') }}" alt="Admin" />
                    </div>
                    <div class="text">

                        <h3>
                            <strong>
                                Sign In
                            </strong>
                        </h3>
                        <p>
                            Access the My Global Dr using your email and passcode.
                        </p>
                    </div>
                    <form id="login-form" class="auth-form">
                        {{ csrf_field() }}
                        <div class="auth-form-field-container">
                            <label for="email-or-username">Email / Username</label>
                            <input type="text" placeholder="example@xyz.com" name="email" id="email-or-username">
                        </div>
                        <div class="auth-form-field-container">
                            <label for="password">Password</label>
                            <input type="password" name="password" placeholder="********" id="password">
                        </div>
                        <div class="auth-form-other-fields-container">
                            <div class="remember-box">
                                <input type="checkbox" name="remember" class="auth-checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                            <a href="/forgot-password" class="forgot-password">Forgot Password?</a>
                        </div>
                        <button class="submit-button" type="submit">
                            Sign in
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </form>
                    <div class="auth-form-other-fields-container" style="padding: 0; margin: 10px 0;">
                        <p class="create-account">
                            New on our platform? <a href="">Create Account</a>
                        </p>
                    </div>
                    <div class="divider"><strong>OR</strong></div>

                    <div class="other-login-methods" style="padding: 0; margin: 10px 0;">
                        <button class="facebook">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_8998_3660)">
                                    <path
                                        d="M23.5 12.2935C23.5 5.94223 18.3513 0.793512 12 0.793512C5.64872 0.793512 0.5 5.94223 0.5 12.2935C0.5 18.0335 4.70538 22.7911 10.2031 23.6538V15.6177H7.2832V12.2935H10.2031V9.75992C10.2031 6.87773 11.92 5.2857 14.5468 5.2857C15.805 5.2857 17.1211 5.51031 17.1211 5.51031V8.34039H15.671C14.2424 8.34039 13.7969 9.22685 13.7969 10.1363V12.2935H16.9863L16.4765 15.6177H13.7969V23.6538C19.2946 22.7911 23.5 18.0335 23.5 12.2935Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_8998_3660">
                                        <rect width="24" height="24" fill="white"
                                            transform="translate(0 0.223633)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <button class="google">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="24" height="24" transform="translate(0 0.223633)"
                                    fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M23.04 12.4851C23.04 11.6696 22.9668 10.8855 22.8309 10.1328H12V14.5812H18.1891C17.9225 16.0187 17.1123 17.2367 15.8943 18.0521V20.9376H19.6109C21.7855 18.9355 23.04 15.9874 23.04 12.4851Z"
                                    fill="#0092E4" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.9995 23.7232C15.1045 23.7232 17.7077 22.6934 19.6104 20.9371L15.8938 18.0516C14.864 18.7416 13.5467 19.1493 11.9995 19.1493C9.00425 19.1493 6.46902 17.1264 5.5647 14.4082H1.72266V17.3877C3.61493 21.1462 7.50402 23.7232 11.9995 23.7232Z"
                                    fill="#5CB85C" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.56523 14.4086C5.33523 13.7186 5.20455 12.9816 5.20455 12.2236C5.20455 11.4657 5.33523 10.7286 5.56523 10.0386V7.05908H1.72318C0.944318 8.61158 0.5 10.3679 0.5 12.2236C0.5 14.0793 0.944318 15.8357 1.72318 17.3882L5.56523 14.4086Z"
                                    fill="#FDA700" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.9995 5.2975C13.6879 5.2975 15.2038 5.87772 16.3956 7.01727L19.694 3.71886C17.7024 1.86318 15.0992 0.723633 11.9995 0.723633C7.50402 0.723633 3.61493 3.30068 1.72266 7.05909L5.5647 10.0386C6.46902 7.32045 9.00425 5.2975 11.9995 5.2975Z"
                                    fill="#E41F07" />
                            </svg>
                        </button>
                        <button class="apple">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_8998_3671)">
                                    <rect width="24" height="24" transform="translate(0 0.223633)"
                                        fill="#050407" />
                                    <path
                                        d="M21.2798 18.6476C20.932 19.4512 20.5203 20.1908 20.0433 20.8709C19.393 21.7979 18.8606 22.4396 18.4503 22.796C17.8143 23.3809 17.1329 23.6805 16.4031 23.6975C15.8792 23.6975 15.2475 23.5484 14.5121 23.246C13.7742 22.9451 13.0962 22.796 12.4762 22.796C11.826 22.796 11.1286 22.9451 10.3827 23.246C9.63565 23.5484 9.03383 23.706 8.5737 23.7216C7.87393 23.7515 7.17643 23.4434 6.4802 22.796C6.03583 22.4084 5.48002 21.744 4.81417 20.8027C4.09977 19.7976 3.51244 18.632 3.05231 17.3031C2.55953 15.8678 2.3125 14.4779 2.3125 13.1323C2.3125 11.5909 2.64556 10.2615 3.31269 9.14748C3.83698 8.25264 4.53449 7.54676 5.40747 7.02856C6.28045 6.51037 7.2237 6.2463 8.23951 6.22941C8.79532 6.22941 9.5242 6.40134 10.43 6.73923C11.3332 7.07825 11.9131 7.25018 12.1674 7.25018C12.3575 7.25018 13.0018 7.04915 14.094 6.64837C15.1268 6.27669 15.9985 6.12279 16.7126 6.18341C18.6477 6.33958 20.1015 7.10239 21.0683 8.47667C19.3377 9.52526 18.4816 10.994 18.4986 12.878C18.5142 14.3456 19.0466 15.5668 20.0929 16.5365C20.5671 16.9865 21.0967 17.3344 21.6859 17.5814C21.5581 17.9519 21.4232 18.3069 21.2798 18.6476ZM16.8418 1.18376C16.8418 2.33403 16.4216 3.40802 15.5839 4.4021C14.5731 5.58387 13.3505 6.26675 12.0246 6.15899C12.0077 6.021 11.9979 5.87576 11.9979 5.72314C11.9979 4.61889 12.4786 3.43713 13.3323 2.47087C13.7585 1.98164 14.3005 1.57485 14.9579 1.25035C15.6138 0.930685 16.2342 0.753906 16.8177 0.723633C16.8347 0.877405 16.8418 1.03119 16.8418 1.18375V1.18376Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_8998_3671">
                                        <rect width="24" height="24" fill="white"
                                            transform="translate(0 0.223633)" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </button>
                    </div>
                    <p style="align-self: baseline; text-align: center; color: black; margin: 100px auto 0 auto;">
                        Copyright © 2025 - My Global Dr
                    </p>
            </section>
            <section class="auth-img-container">
                <img src="{{ asset('/administrator/img/admin/doctors-admin-login.png') }}" alt="Doctor" />
            </section>
        </div>
        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('/administrator/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('/administrator/js/main.js') }}"></script>
    <script src="{{ asset('/js/axios.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/administrator/js/script.js?v=' . time()) }}"></script>

    <script>
        async function doLogin(event) {
            event.preventDefault()
            const form = event.target

            try {
                const formData = new FormData(form)
                const submitButton = form.querySelector('button[type="submit"]')
                submitButton.setAttribute("disabled", "disabled")

                const response = await axios.post(
                    baseUrl + "/admin/login",
                    formData
                )

                if (response.data.status == "success") {
                    window.location.href = baseUrl + "/admin"
                } else {
                    swal.fire("Error", response.data.message, "error")
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            } finally {
                const submitButton = form.querySelector('button[type="submit"]')
                submitButton.removeAttribute("disabled")
            }
        }

        const form = document.getElementById('login-form')
        form.addEventListener('submit', doLogin) // ✅ no "on"
    </script>

</body>

</html>
