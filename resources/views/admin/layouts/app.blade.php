<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="robots" content="noindex, nofollow" />

  <title>@yield("title", "Admin Panel")</title>

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
</head>

<body>

  @php
    $user = null;
  @endphp

  @if (auth()->check())
    @php
      $user = auth()->user();
    @endphp

    <input type="hidden" id="user" value="{{ json_encode([
      'id' => $user->id ?? 0,
      'name' => $user->name ?? '',
      'email' => $user->email ?? '',
      'type' => $user->type ?? ''
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
          null,
          {
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
  </script>

  <!-- ======= Header ======= -->
  <header id="header-app" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ url('/admin') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('/administrator/img/logo.png') }}" alt="" />
        <span class="d-none d-lg-block">Admin panel</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword" />
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li>

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('/administrator/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle" />
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name ?? "" }}</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ auth()->user()->name ?? "" }}</h6>
              <span>{{ auth()->user()->email ?? "" }}</span>
            </li>

            <li>
              <hr class="dropdown-divider" />
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="logout();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </nav>
  </header><!-- End Header -->

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
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->url() == url('/admin') ? '' : 'collapsed' }}" href="{{ url('/admin') }}">
          <i class="fa fa-chart-area"></i>&nbsp;
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/doctors') ? '' : 'collapsed' }}" href="{{ url('/admin/doctors') }}">
          <i class="fa fa-user-doctor"></i>&nbsp;
          <span>Doctors</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/patients') ? '' : 'collapsed' }}" href="{{ url('/admin/patients') }}">
          <i class="fa fa-hospital-user"></i>&nbsp;
          <span>Patients</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/calls') ? '' : 'collapsed' }}" href="{{ url('/admin/calls') }}">
          <i class="fa fa-phone"></i>&nbsp;
          <span>Calls</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/services') ? '' : 'collapsed' }}" href="{{ url('/admin/services') }}">
          <i class="fa fa-truck-medical"></i>&nbsp;
          <span>Services</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/specialities') ? '' : 'collapsed' }}" href="{{ url('/admin/specialities') }}">
          <i class="fa fa-suitcase-medical"></i>&nbsp;
          <span>Specialities</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/settings') ? '' : 'collapsed' }}" href="{{ url('/admin/settings') }}">
          <i class="fa fa-cog"></i>&nbsp;
          <span>Settings</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->url(), 'admin/change-password') ? '' : 'collapsed' }}" href="{{ url('/admin/change-password') }}">
          <i class="fa fa-lock"></i>&nbsp;
          <span>Change Password</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    @yield("main")

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <!-- Modal -->
  <div class="modal" id="example-modal" tabindex="-1">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header" style="display: inline-block;">
                  <h5 class="modal-title" style="display: contents;">Title</h5>

                  <button type="button" class="close btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"
                      style="float: right;">
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <style>
    .timezone {
      display: none;
    }
  </style>

</body>

</html>