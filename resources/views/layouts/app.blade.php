<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title', config('config.app_name'))</title>

    <!-- <link rel="preload" as="font" href="{{ asset('/fonts/Manrope-Regular.ttf') }}" type="font/ttf" crossorigin /> -->
    <!-- <link rel="preload" as="font" href="{{ asset('/fonts/Manrope-SemiBold.ttf') }}" type="font/ttf" crossorigin /> -->

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('/owl-carousel/assets/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/styles.css?v=' . time()) }}" />
</head>

<body>
    <input type="hidden" id="baseUrl" value="{{ url('/') }}" />

    <script>
        const baseUrl = document.getElementById("baseUrl").value;

        let user = null;
    </script>

    @php
        $me = null;
    @endphp

    @if (auth()->check())
        @php
            $me = auth()->user();

            if ($me->profile_image && \Storage::exists('public/' . $me->profile_image)) {
                $me->profile_image = url('/storage/' . $me->profile_image);
            }
        @endphp

        <input type="hidden" id="user"
            value="{{ json_encode([
                'id' => $me->id ?? 0,
                'name' => $me->name ?? '',
                'email' => $me->email ?? '',
                'profile_image' => $me->profile_image ?? '',
                'type' => $me->type ?? '',
            ]) }}" />

        <script>
            user = JSON.parse(document.getElementById("user").value);
        </script>

        @if ($me->type == 'doctor')
            <script>
                let incomingCallId = "";

                function listenForCalls() {

                    ref = db.ref("calls/" + user);

                    // Listen for answer
                    ref.on("value", async snapshot => {

                        if (snapshot.exists()) {
                            const key = snapshot.key;
                            const value = snapshot.val();

                            try {
                                const formData = new FormData();
                                formData.append("id", value.callId);

                                const response = await axios.post(
                                    baseUrl + "/calls/is-incoming",
                                    formData
                                )

                                if (response.data.status == "success") {
                                    const call = response.data.call;
                                    const patient = response.data.patient;
                                    incomingCallId = call.id;

                                    document.getElementById('incomingCallModal').classList.remove('hidden');
                                } else {
                                    // swal.fire("Error", response.data.message, "error")
                                    ref.remove();
                                }
                            } catch (exp) {
                                // swal.fire("Error", exp.message, "error")
                            }
                        }
                    });
                }

                async function acceptCall() {
                    try {
                        document.getElementById("acceptCallBtn").setAttribute("disabled", "disabled");
                        document.getElementById("rejectCallBtn").setAttribute("disabled", "disabled");

                        const formData = new FormData();
                        formData.append("id", incomingCallId);

                        const response = await axios.post(
                            baseUrl + "/calls/accept",
                            formData
                        )

                        if (response.data.status == "success") {
                            window.location.href = baseUrl + "/calls/" + incomingCallId + "/detail";
                        } else {
                            swal.fire("Error", response.data.message, "error")
                        }
                    } catch (exp) {
                        swal.fire("Error", exp.message, "error")
                    } finally {
                        document.getElementById("acceptCallBtn").removeAttribute("disabled");
                        document.getElementById("rejectCallBtn").removeAttribute("disabled");
                    }
                }

                async function rejectCall() {
                    try {
                        document.getElementById("acceptCallBtn").setAttribute("disabled", "disabled");
                        document.getElementById("rejectCallBtn").setAttribute("disabled", "disabled");

                        const formData = new FormData();
                        formData.append("id", incomingCallId);

                        const response = await axios.post(
                            baseUrl + "/calls/reject",
                            formData
                        )

                        if (response.data.status == "success") {
                            db.ref("calls/" + user).remove();
                            document.getElementById('incomingCallModal').classList.add('hidden');
                        } else {
                            swal.fire("Error", response.data.message, "error")
                        }
                    } catch (exp) {
                        swal.fire("Error", exp.message, "error")
                    } finally {
                        document.getElementById("acceptCallBtn").removeAttribute("disabled");
                        document.getElementById("rejectCallBtn").removeAttribute("disabled");
                    }
                }

                async function acceptGroupCall(event, id) {
                    const node = event.currentTarget;

                    swal.fire({
                        title: "Accept call",
                        text: "Please be available at the mentioned time.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Accept"
                    }).then(async function(result) {
                        if (result.isConfirmed) {
                            try {
                                node.setAttribute("disabled", "disabled");

                                const formData = new FormData();
                                formData.append("id", id);

                                const response = await axios.post(
                                    baseUrl + "/group-calls/accept",
                                    formData
                                )

                                if (response.data.status == "success") {
                                    window.location.href = baseUrl + "/group-calls/" + id + "/detail";
                                } else {
                                    swal.fire("Error", response.data.message, "error")
                                }
                            } catch (exp) {
                                console.log(exp.message);
                                // swal.fire("Error", exp.message, "error")
                            } finally {
                                node.removeAttribute("disabled");
                            }
                        }
                    });
                }

                function rejectGroupCall(event, id) {
                    const node = event.currentTarget;

                    swal.fire({
                        title: "Reject call",
                        text: "You won't be able to join it later!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Reject"
                    }).then(async function(result) {
                        if (result.isConfirmed) {
                            try {
                                node.setAttribute("disabled", "disabled");

                                const formData = new FormData();
                                formData.append("id", id);

                                const response = await axios.post(
                                    baseUrl + "/group-calls/reject",
                                    formData
                                )

                                if (response.data.status == "success") {
                                    swal.fire("Reject", response.data.message, "success")
                                        .then(function() {
                                            window.location.reload();
                                        });
                                } else {
                                    swal.fire("Error", response.data.message, "error");
                                }
                            } catch (exp) {
                                console.log(exp.message);
                                // swal.fire("Error", exp.message, "error")
                            } finally {
                                node.removeAttribute("disabled");
                            }
                        }
                    });
                }

                listenForCalls();
            </script>
        @endif
    @endif

    <script>
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        fetch(baseUrl + "/set-timezone", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                "_token": document.querySelector("meta[name='_token']").content,
                "timezone": timezone
            })
        });
    </script>

    <nav class="navbar navbar-expand-lg"
        style="position: sticky;
            top: 0;
            width: 100%;
            background: white;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="me-2" style="width: 100px;" />
            </a>

            <!-- Mobile Menu Button (only visible on mobile) -->
            <div class="d-lg-none d-flex flex-column align-items-end gap-1">
                <!-- Hamburger -->
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"
                    style="border: 1px solid lightgray; border-radius: 5px; background-color: white;">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Phone Number -->
                <a href="tel:{{ fetch_setting('emergency_number') }}" class="btn no-border fw-bold"
                    style="background-color: #feb4cf;">
                    {{ fetch_setting('emergency_number') }}
                </a>
            </div>

            <!-- Nav Links + CTA -->
            <div class="collapse navbar-collapse" id="navbarContent">

                <!-- Center Links -->
                <ul class="navbar-nav gap-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->url() == url('/') ? 'active color-primary' : '' }}"
                            href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->url() == url('/services') ? 'active color-primary' : '' }}"
                            href="{{ url('/services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->url() == url('/doctors') ? 'active color-primary' : '' }}"
                            href="{{ url('/doctors') }}">Find Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about-us') }}">About us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact us</a>
                    </li>
                </ul>

                <!-- CTA on Right -->
                <div class="d-flex">
                    <a href="tel:{{ fetch_setting('emergency_number') }}" class="btn no-border bold me-2 hide-mobile"
                        style="background-color: #feb4cf;">{{ fetch_setting('emergency_number') }}</a>

                    <div id="auth-container"></div>

                </div>

            </div>
        </div>
    </nav>

    @yield('main')

    <footer class="bg-color-secondary">
        <div class="container pt-5 pb-5">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ asset('/img/logo.png') }}" style="width: 100px;" />

                    <span
                        style="color: #FF005F;
                            height: 130px;
                            display: flex;
                            align-items: flex-end;">Experience
                        personalized medical care from the comfort of your home.</span>
                </div>

                <div class="col-md-3 links">
                    <p class="color-primary font-600">Support</p>

                    <ul>
                        <li>
                            <a href="#">Getting Started</a>
                        </li>

                        <li>
                            <a href="#">FAQs</a>
                        </li>

                        <li>
                            <a href="#">Help Articles</a>
                        </li>

                        <li>
                            <a href="#">Report an issue</a>
                        </li>

                        <li>
                            <a href="#">Contact Help Desk</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 links">
                    <p class="color-primary font-600">Services</p>

                    <ul>
                        <li>
                            <a href="#">Booking appointments</a>
                        </li>

                        <li>
                            <a href="#">Online consultations</a>
                        </li>

                        <li>
                            <a href="#">Prescriptions</a>
                        </li>

                        <li>
                            <a href="#">Medicine Refills</a>
                        </li>

                        <li>
                            <a href="#">Medical Notes</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 links">
                    <p class="color-primary font-600">Legal</p>

                    <ul>
                        <li>
                            <a href="#">Terms & Conditions</a>
                        </li>

                        <li>
                            <a href="#">Privacy Policy</a>
                        </li>

                        <li>
                            <a href="#">Cookie Notice</a>
                        </li>

                        <li>
                            <a href="#">Cookie Preference</a>
                        </li>

                        <li>
                            <a href="#">Test Center</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="divider"
                        style="height: 2px;
                            background-color: #FF005F;
                            margin: 20px 0;">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <ul class="social">
                        <li>
                            <a href="#" target="_blank">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" target="_blank">
                                <i class="fa-brands fa-linkedin"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" target="_blank">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 copyright">
                    {{ config('config.app_name') }} {{ now()->year }} &copy; All Rights Reserved
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/{{ fetch_setting('whatsapp_number') }}" class="whatsapp-float" target="_blank"
        aria-label="Chat on WhatsApp">
        <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp Chat" />
    </a>

    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script src="{{ asset('/js/popper.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/owl-carousel/owl.carousel.js') }}"></script>

    <script src="{{ asset('/js/react.development.js') }}"></script>
    <script src="{{ asset('/js/react-dom.development.js') }}"></script>
    <script src="{{ asset('/js/babel.min.js') }}"></script>
    <script src="{{ asset('/js/axios.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/fontawesome.js') }}"></script>
    <!-- Firebase UMD SDKs -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>

    <script type="text/babel" src="{{ asset('/js/components/BestDoctors.js?v=' . time()) }}"></script>
    <script src="{{ asset('/js/script.js?v=' . time()) }}"></script>

    @yield('script')
    <script>
        // Fetch authenticated user info from backend
        async function fetchUser() {
            try {
                const response = await axios.get('{{ env('API_HOST') }}/doctors/info', {
                    withCredentials: true, // Important for sending cookies
                });

                if (response.data && response.data.success && response.data.data.username) {
                    user = response.data.data.username;
                    console.log(user, response.data.data.username)
                    renderUserUI(user);
                } else {
                    renderGuestUI();
                }
            } catch (err) {
                console.error("Error fetching user:", err);
                renderGuestUI();
            }
        }

        // Render UI for logged-in users
        function renderUserUI(user) {
            const authContainer = document.getElementById("auth-container");
            authContainer.innerHTML = `
      <div class="dropdown">
        <button class="btn btn-primary bg-primary-gradient dropdown-toggle no-border" 
                type="button" id="dropText1" data-bs-toggle="dropdown" aria-expanded="false">
          ${user || "User"}
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropText1">
          <li><a class="dropdown-item" href="${baseUrl}/appointments">Appointments</a></li>
          <li><a class="dropdown-item" href="${baseUrl}/profile-settings">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><button class="dropdown-item-text px-3 btn" onclick="logout()">Logout</button></li>
        </ul>
      </div>
    `;
        }

        // Render UI for guests (not logged in)
        function renderGuestUI() {
            const authContainer = document.getElementById("auth-container");
            authContainer.innerHTML = `
      <a href="${baseUrl}/login" class="btn bg-primary-gradient no-border white bold">
        Join us
      </a>
    `;
        }

        // Logout function — calls backend logout route
        async function logout() {
            try {
                await axios.post('{{ env('API_HOST') }}/auth/v1/doctors/logout', {}, {
                    withCredentials: true
                });
                user = null;
                renderGuestUI();
            } catch (err) {
                console.error("Logout failed:", err);
            }
        }

        // Run immediately when page loads
        fetchUser();
    </script>
</body>

</html>
