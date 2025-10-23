@extends('layouts/base')
@section('title', config('config.app_name') . ' | Login')

@section('body')


    <style>
        body {
            min-height: 100vh;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem 2.5rem;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .login-title {
            font-weight: 700;
            color: #203a43;
            /* margin-bottom: 1.5rem; */
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ccc;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25);
        }

        .btn-gradient {
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <input type="hidden" id="baseUrl" value="{{ url('/') }}">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-md-5">
            <div class="login-card">
                <div class="auth-logo mb-2">
                    <img src="{{ asset('/administrator/img/admin/logo.png') }}" alt="" />
                </div>
                <div class="text-start">
                    <h2 class="login-title">Welcome Back</h2>
                    <p class="text-muted">Sign in to continue to {{ config('config.app_name') }}</p>
                </div>
                <form id="login-form" class="mt-4">
                    {{ csrf_field() }}
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" name="email" required class="form-control"
                            placeholder="Enter your email" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" id="password" name="password" required class="form-control"
                            placeholder="Enter your password" />
                    </div>

                    <button type="submit" name="submit" class="bg-primary-gradient btn-gradient">Sign In</button>

                    <a href="{{ url('/forgot-password') }}" class="forgot-link">Forgot your password?</a>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/axios.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>

    <script>
        (function() {
            const form = document.getElementById("login-form");
            if (!form) return;
            const baseUrl = document.getElementById("baseUrl").value;
            let isSubmitting = false;

            async function doLogin(event) {
                event.preventDefault();
                if (isSubmitting) return;
                isSubmitting = true;

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.setAttribute("disabled", "disabled");

                const email = document.getElementById("email").value;
                const password = document.getElementById("password").value;

                // Show loading SweetAlert
                Swal.fire({
                    title: "Signing you in...",
                    html: '<div style="display:flex;justify-content:center;align-items:center;gap:10px;"><div class="spinner" style="width:24px;height:24px;border:3px solid #ccc;border-top:3px solid #3085d6;border-radius:50%;animation:spin 1s linear infinite;"></div><span>Please wait</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                });

                try {
                    const response = await axios.post(
                        "{{ env('API_HOST') }}/auth/v1/doctors/login", {
                            email,
                            password
                        }, {
                            withCredentials: true
                        }
                    );

                    Swal.close();

                    if (response.data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Login Successful",
                            text: "Redirecting to dashboard...",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        const redirect = new URLSearchParams(window.location.search).get("redirect");
                        setTimeout(() => {
                            window.location.href = redirect || baseUrl;
                        }, 1500);
                    } else {
                        Swal.fire("Error", response.data.message || "Invalid login details", "error");
                    }
                } catch (err) {
                    Swal.close();
                    Swal.fire("Error", err?.response?.data?.message ?? err?.message ?? "Something went wrong",
                        "error");
                } finally {
                    submitButton.removeAttribute("disabled");
                    isSubmitting = false;
                }
            }

            form.addEventListener("submit", doLogin);
        })();
    </script>


@endsection
