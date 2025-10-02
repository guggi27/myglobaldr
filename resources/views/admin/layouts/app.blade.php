<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title', 'Admin Panel')</title>

    <meta name="_token" content="{{ csrf_token() }}" />

    <!-- Favicons -->
    <link href="{{ asset('/administrator/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/administrator/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

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
    <link href="{{ asset('/administrator/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('/administrator/css/custom.css?v=' . time()) }}" rel="stylesheet" />
    <link href="{{ asset('/css/fontawesome.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/fontawesome.js') }}"></script>

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 7 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

    <script src="{{ asset('/administrator/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('/administrator/js/bootstrap.min.js') }}"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <!-- Vendor JS Files -->
    <script src="{{ asset('/administrator/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/administrator/vendor/php-email-form/validate.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('/richtext/richtext.min.css') }}" />
    <script src="{{ asset('/richtext/jquery.richtext.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('/administrator/js/main.js') }}"></script>

    <script src="{{ asset('/js/react.development.js') }}"></script>
    <script src="{{ asset('/js/react-dom.development.js') }}"></script>
    <script src="{{ asset('/js/babel.min.js') }}"></script>
    <script src="{{ asset('/js/axios.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/html-react-parser.min.js') }}"></script>
    <script src="{{ asset('/administrator/js/script.js?v=' . time()) }}"></script>
    <style>
        /* Sidebar fixed on left */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100%;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
            transition: transform 0.3s ease-in-out;
        }

        /* Hidden in mobile */
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                position: absolute;
            }

            #sidebar.active {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            }

            header,
            #main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* Header beside sidebar (desktop) */
        header {
            margin-left: 240px;
            width: calc(100% - 240px);
            z-index: 1030;
            min-height: 64px;
        }

        #main {
            margin-left: 240px;
            padding: 20px;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        header.collapsed,
        #main.collapsed {
            margin-left: 60px;
            width: calc(100% - 60px);
        }

        .search-input {
            padding-left: 2.25rem;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

</head>

<body>

    @php
        $user = null;
    @endphp

    @if (auth()->check())
        @php
            $user = auth()->user();
        @endphp

        <input type="hidden" id="user"
            value="{{ json_encode([
                'id' => $user->id ?? 0,
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'type' => $user->type ?? '',
            ]) }}" />
    @endif

    <input type="hidden" id="baseUrl" value="{{ url('/') }}" />

    <script>
        const baseUrl = document.getElementById("baseUrl").value;

        let user = null;

        if (document.getElementById("user") != null) {
            user = JSON.parse(document.getElementById("user").value);
        }

        if (user != null) {
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
        }

        async function logout() {
            try {
                const response = await axios.post(
                    baseUrl + "/admin/logout",
                    null, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                )

                if (response.data.status == "success") {
                    globalState.setState({
                        user: null
                    })
                    localStorage.removeItem(accessTokenKey)
                    window.location.href = baseUrl + "/admin/login"
                } else {
                    swal.fire("Error", response.data.message, "error")
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            }
        }

        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>

    <!-- ======= Header ======= -->
    <header class="py-5 top-0 start-0 end-0 z-3 bg-admin-layout" style="min-height:64px;">
        <div class="d-flex flex-column flex-md-row align-items-start justify-content-between h-100 px-3 px-md-4 gap-3">
            <div class="text-start">
                <!-- Left section -->
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-lg-none p-1" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="text-muted small">
                        Pages
                        @php
                            $segments = request()->segments(); // breaks URL into array
                        @endphp

                        @if (count($segments) > 1)
                            /
                            @foreach (array_slice($segments, 1) as $index => $segment)
                                {{ ucfirst($segment) }}
                                @if ($index < count($segments) - 2)
                                    /
                                @endif
                            @endforeach
                        @else
                            / Dashboard
                        @endif
                    </div>
                </div>


                <!-- Center section -->
                <div class="" style="color: #2B3674;">
                    <h1 class="h3 fw-semibold mb-0">
                        {{ $segments[count($segments) - 1] === 'admin' ? 'Main Dashboard' : ucfirst($segments[count($segments) - 1]) }}
                    </h1>
                </div>
            </div>

            <!-- Right section -->
            <div class="d-flex align-items-center bg-white p-2 px-3 gap-1" style="border-radius: 9999px">

                <!-- Search -->
                <div class="position-relative me-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class=" position-absolute text-sm top-50 start-0 translate-middle-y ms-3 text-black"
                        width="11" height="12" viewBox="0 0 11 12" fill="none">
                        <circle cx="5" cy="5" r="4.3" stroke="#2B3674" stroke-width="1.4" />
                        <line x1="10.0101" y1="11" x2="8" y2="8.98995" stroke="#2B3674"
                            stroke-width="1.4" stroke-linecap="round" />
                    </svg>
                    <input type="text" class="form-control form-control-sm search-input bg-admin-layout"
                        name="search" placeholder="Search"
                        style="width:12rem; height:36px; border: none; border-radius: 9999px;">
                </div>

                <!-- Notification -->
                <button class="nav-icons bg-transparent btn-sm p-0 d-flex align-items-center justify-content-center"
                    style="width:36px; height:36px; border-radius: 100%;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_9014_17504)">
                            <path
                                d="M19.2896 17.29L17.9996 16V11C17.9996 7.93 16.3596 5.36 13.4996 4.68V4C13.4996 3.17 12.8296 2.5 11.9996 2.5C11.1696 2.5 10.4996 3.17 10.4996 4V4.68C7.62956 5.36 5.99956 7.92 5.99956 11V16L4.70956 17.29C4.07956 17.92 4.51956 19 5.40956 19H18.5796C19.4796 19 19.9196 17.92 19.2896 17.29ZM15.9996 17H7.99956V11C7.99956 8.52 9.50956 6.5 11.9996 6.5C14.4896 6.5 15.9996 8.52 15.9996 11V17ZM11.9996 22C13.0996 22 13.9996 21.1 13.9996 20H9.99956C9.99956 21.1 10.8896 22 11.9996 22Z"
                                fill="#A3AED0" />
                        </g>
                        <defs>
                            <clipPath id="clip0_9014_17504">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                </button>

                <!-- Dark mode -->
                <button class="nav-icons bg-transparent btn-sm p-0 d-flex align-items-center justify-content-center"
                    style="width:36px; height:36px; border-radius: 100%;">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_9014_17502)">
                            <path
                                d="M9.95703 18C12.733 18 15.2684 16.737 16.9481 14.6675C17.1966 14.3613 16.9256 13.9141 16.5416 13.9872C12.1751 14.8188 8.16522 11.4709 8.16522 7.06303C8.16522 4.52398 9.52444 2.18914 11.7335 0.931992C12.074 0.738211 11.9884 0.221941 11.6015 0.150469C11.059 0.0504468 10.5086 8.21369e-05 9.95703 0C4.98914 0 0.957031 4.02578 0.957031 9C0.957031 13.9679 4.98281 18 9.95703 18Z"
                                fill="#A3AED0" />
                        </g>
                        <defs>
                            <clipPath id="clip0_9014_17502">
                                <rect width="18" height="18" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                </button>


                <!-- Info Icon -->
                <button class="nav-icons bg-transparent btn-sm p-0 d-flex align-items-center justify-content-center"
                    style="width:36px; height:36px; border-radius: 100%;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_9014_17497)">
                            <path
                                d="M11 7H13V9H11V7ZM12 17C12.55 17 13 16.55 13 16V12C13 11.45 12.55 11 12 11C11.45 11 11 11.45 11 12V16C11 16.55 11.45 17 12 17ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z"
                                fill="#A3AED0" />
                        </g>
                        <defs>
                            <clipPath id="clip0_9014_17497">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>


                </button>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light p-0 rounded-circle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" style="width:36px; height:36px;">
                        <div class="avatar">
                            <img src="{{ asset('/administrator/img/profile-img.jpg') }}" alt="Profile"
                                class="rounded-circle" />
                            {{-- <img src="{{ asset('/administrator/img/logo.png') }}" alt="" /> --}}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="dropdown-header">
                            <div class="d-flex flex-column text-start">
                                <span class="fw-medium text-black">{{ auth()->user()->name ?? '' }}</span>
                                <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Profile Settings</a></li>
                        <li><a class="dropdown-item" href="#">Account Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><button class="dropdown-item text-danger" onclick="logout()">Logout</button></li>
                    </ul>
                </div>

            </div>
        </div>
    </header>

    <script type="text/babel">
    /*function Header() {

      const [state, setState] = React.useState(globalState.state)

      globalState.listen(function (newState) {
          setState(newState)
      })

      async function onInit() {
          window.user = null
          const accessToken = localStorage.getItem(accessTokenKey)
          
          try {
              const response = await axios.post(
                  baseUrl + "/admin/me",
                  null,
                  {
                      headers: {
                          Authorization: "Bearer " + accessToken
                      }
                  }
              )

              if (response.data.status == "success") {
                  const newMessages = response.data.new_messages;
                  window.user = response.data.user;
                  const unreadContactUs = response.data.unread_contact_us;

                  document.getElementById("sidebar-nav").style.display = ""

                  globalState.setState({
                      user: window.user
                  })

                  if (newMessages > 0) {
                    document.getElementById("message-notification-badge").innerHTML = newMessages
                  }

                  if (unreadContactUs > 0) {
                    document.getElementById("unread-contact-us").innerHTML = "(" + unreadContactUs + ")";
                  }

                  window.onInit()
              } else {
                  // swal.fire("Error", response.data.message, "error")
              }
          } catch (exp) {
              // swal.fire("Error", exp.message, "error")
          }

          if (window.user == null) {
            // window.location.href = baseUrl + "/admin/login"
          }
      }

      React.useEffect(function () {
        onInit()
      }, [])

      return (
          <>
              <div className="d-flex align-items-center justify-content-between">
                <a href={`${ baseUrl }/admin`} className="logo d-flex align-items-center">
                  <img src={`${ baseUrl }/administrator/img/logo.png`} alt="" />
                  <span className="d-none d-lg-block">Admin panel</span>
                </a>
                <i className="bi bi-list toggle-sidebar-btn"></i>
              </div>

              <div className="search-bar">
                <form className="search-form d-flex align-items-center" method="POST" action="#">
                  <input type="text" name="query" placeholder="Search" title="Enter search keyword" />
                  <button type="submit" title="Search"><i className="bi bi-search"></i></button>
                </form>
              </div>

              <nav className="header-nav ms-auto">
                <ul className="d-flex align-items-center">

                  <li className="nav-item d-block d-lg-none">
                    <a className="nav-link nav-icon search-bar-toggle " href="#">
                      <i className="bi bi-search"></i>
                    </a>
                  </li>

                  <li className="nav-item dropdown pe-3">

                    <a className="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                      <img src={`${ baseUrl }/administrator/img/profile-img.jpg`} alt="Profile" className="rounded-circle" />
                      <span className="d-none d-md-block dropdown-toggle ps-2">{ state.user?.name }</span>
                    </a>

                    <ul className="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                      <li className="dropdown-header">
                        <h6>{ state.user?.name }</h6>
                        <span>{ state.user?.email }</span>
                      </li>

                      <li>
                        <hr className="dropdown-divider" />
                      </li>

                      <li>
                        <a className="dropdown-item d-flex align-items-center" href="#" onClick={ function () {
                          event.preventDefault()
                          logout()
                        } }>
                          <i className="bi bi-box-arrow-right"></i>
                          <span>Sign Out</span>
                        </a>
                      </li>

                    </ul>
                  </li>

                </ul>
              </nav>
          </>
      )
  }

  ReactDOM.createRoot(
      document.getElementById("header-app")
  ).render(<Header />)*/
  </script>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar bg-white">
        <div class="d-flex mb-4 align-items-center justify-content-between px-2">
            <a href="{{ url('/admin') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('/administrator/img/admin/logo.png') }}" style="width: 100%; height: 100%;"
                    alt="Logo" />
            </a>
            <button class="btn d-lg-none p-1" style="border-radius: 100%; " onclick="toggleSidebar()">
                <i class="bi bi-x fs-5"></i>
            </button>
        </div>
        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->url() == url('/admin') ? '' : 'collapsed' }}"
                    href="{{ url('/admin') }}">
                    <i class="fa fa-chart-area"></i>&nbsp;
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/doctors') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/doctors') }}">
                    <i class="fa fa-user-doctor"></i>&nbsp;
                    <span>Doctors</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/patients') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/patients') }}">
                    <i class="fa fa-hospital-user"></i>&nbsp;
                    <span>Patients</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/calls') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/calls') }}">
                    <i class="fa fa-phone"></i>&nbsp;
                    <span>Calls</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/services') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/services') }}">
                    <i class="fa fa-truck-medical"></i>&nbsp;
                    <span>Services</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/specialities') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/specialities') }}">
                    <i class="fa fa-suitcase-medical"></i>&nbsp;
                    <span>Specialities</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/settings') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/settings') }}">
                    <i class="fa fa-cog"></i>&nbsp;
                    <span>Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/change-password') ? '' : 'collapsed' }}"
                    href="{{ url('/admin/change-password') }}">
                    <i class="fa fa-lock"></i>&nbsp;
                    <span>Change Password</span>
                </a>
            </li>

        </ul>
    </aside>

    <main id="main" class="main">
        @yield('main')
    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright. All Rights Reserved
        </div>
    </footer>

    <div class="modal" id="example-modal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display: inline-block;">
                    <h5 class="modal-title" style="display: contents;">Title</h5>

                    <button type="button" class="close btn btn-danger btn-sm" data-dismiss="modal"
                        aria-label="Close" style="float: right;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    Modal body
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" name="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <style>
        .timezone {
            display: none;
        }
    </style>

</body>

</html>
