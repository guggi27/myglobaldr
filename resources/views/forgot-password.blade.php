@extends('layouts/app')
@section('title', config('config.app_name') . ' | Forgot Password')

@section('main')

    <style>
        body {
            /* background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); */
            min-height: 100vh;
        }

        .forgot-card {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }

        .forgot-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
        }

        .forgot-title {
            font-weight: 700;
            color: #203a43;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ccc;
            transition: all 0.2s ease;
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

        .back-link {
            display: inline-block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            background-clip: text;
            margin-left: auto
                /* color: purple; */
                text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-md-5">
            <div class="forgot-card">
                <div class="text-center">
                    <h2 class="forgot-title">Forgot Password?</h2>
                    <p class="text-muted mt-2 mb-4">Enter your registered email and we’ll send you a reset link.</p>
                </div>

                <form onsubmit="sendResetLink(event)" class="mt-3">
                    <div class="form-group mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" name="email" required class="form-control"
                            placeholder="Enter your email address">
                    </div>

                    <button type="submit" name="submit" class="btn-gradient bg-primary-gradient">Send Reset Password
                        Link</button>
                </form>

                <a href="{{ url('/login') }}" class="back-link bg-primary-gradient">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        async function sendResetLink(event) {
            event.preventDefault()
            const form = event.target

            try {
                const formData = new FormData(form)
                form.submit.setAttribute("disabled", "disabled")

                const response = await axios.post(baseUrl + "/send-password-reset-link", formData)

                if (response.data.status === "success") {
                    swal.fire("Reset Password", response.data.message, "success")
                } else {
                    swal.fire("Error", response.data.message, "error")
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            } finally {
                form.submit.removeAttribute("disabled")
            }
        }
    </script>

@endsection
