@extends ("admin/layouts/app")
@section('title', 'Dashboard')

@section('main')

    {{-- <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
      <h1>Dashboard</h1>
    </div><!-- End Page Title --> --}}

    <section class="section dashboard">

        <div class="row g-4">

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-user-doctor" style="color:#4154f1;"></i>
                    </div>
                    <div>
                        <h5>Doctors</h5>
                        <h6>{{ $doctors }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-hospital-user" style="color: mediumseagreen;"></i>
                    </div>
                    <div>
                        <h5>Patients</h5>
                        <h6>{{ $patients }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-phone" style="color: skyblue;"></i>
                    </div>
                    <div>
                        <h5>Calls</h5>
                        <h6>{{ $calls }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-money-bill" style="color: darkorange;"></i>
                    </div>
                    <div>
                        <h5>Payments</h5>
                        <h6>{{ $payments }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="bi-solid bi-bag-dash-fill" style="color: magenta;"></i>
                    </div>
                    <div>
                        <h5>New Tasks</h5>
                        <h6>{{ 231 }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="bi-solid bi-archive-fill" style="color: darkgray;"></i>
                    </div>
                    <div>
                        <h5>Total Projects</h5>
                        <h6>{{ 24 }}</h6>
                    </div>
                </div>
            </div>
    </section>

    <script>
        /*async function onInit() {
                                                                                                                            try {
                                                                                                                              const response = await axios.post(
                                                                                                                                baseUrl + "/admin/stats",
                                                                                                                                null,
                                                                                                                                {
                                                                                                                                  headers: {
                                                                                                                                    Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                                                                                                                                  }
                                                                                                                                }
                                                                                                                              )

                                                                                                                              if (response.data.status == "success") {
                                                                                                                                const users = response.data.users || 0
                                                                                                                                const messages = response.data.messages || 0

                                                                                                                                document.getElementById("users-count").innerHTML = users
                                                                                                                                document.getElementById("messages-count").innerHTML = messages
                                                                                                                              } else {
                                                                                                                                // swal.fire("Error", response.data.message, "error")
                                                                                                                              }
                                                                                                                            } catch (exp) {
                                                                                                                              // swal.fire("Error", exp.message, "error")
                                                                                                                            }
                                                                                                                          }*/
    </script>

@endsection
