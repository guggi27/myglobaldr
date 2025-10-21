@extends('layouts/base')
@section('title', config('config.app_name') . ' | Login')

@section('body')

    <style>
        body {
            /* background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); */
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
            margin-bottom: 1.5rem;
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
            /* background: linear-gradient(90deg, #007bff, #00c6ff); */
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            /* background: linear-gradient(90deg, #0062cc, #0099cc); */
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
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-md-5">
            <div class="login-card">
                <div class="text-center">
                    <h2 class="login-title">Welcome Back</h2>
                    <p class="text-muted">Sign in to continue to {{ config('config.app_name') }}</p>
                </div>
                <form class="mt-4">
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" required class="form-control" placeholder="Enter your email" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-control"
                            placeholder="Enter your password" />
                    </div>

                    <button type="submit" name="submit" class="bg-primary-gradient btn-gradient">Sign
                        In</button>

                    <a href="{{ url('/forgot-password') }}" class="forgot-link">Forgot your password?</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.querySelector("form");
            async function doLogin(event) {
                event.preventDefault()
                const form = event.target

                try {
                    const formData = new FormData(form)
                    form.submit.setAttribute("disabled", "disabled")

                    const response = await axios.post(
                        "{{ env('API_HOST') }}/auth/v1/doctors/login", {
                            email: formData.get('email'),
                            password: formData.get('password')
                        }, {
                            withCredentials: true
                        }
                    )

                    if (response.data.success) {
                        const urlSearchParams = new URLSearchParams(window.location.search)
                        const redirect = urlSearchParams.get("redirect") || ""
                        window.location.href = redirect || baseUrl
                    } else {
                        swal.fire("Error", response.data.message, "error")
                    }
                } catch (exp) {
                    swal.fire("Error", exp?.response.data.message ?? exp.message, "error")
                } finally {
                    form.submit.removeAttribute("disabled")
                }
            }
            form.addEventListener("submit", doLogin)
        })()
    </script>

@endsection
