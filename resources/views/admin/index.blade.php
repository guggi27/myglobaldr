@extends ("admin/layouts/app")
@section ("title", "Dashboard")

@section ("main")

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <div class="row">

        <!-- Left side columns -->
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Doctors</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="fa fa-user-doctor"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $doctors }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Patients</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="fa fa-hospital-user" style="color: mediumseagreen;"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $patients }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Calls</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="fa fa-phone" style="color: skyblue;"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $calls }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Payments</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="fa fa-money-bill" style="color: darkorange;"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $payments }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Left side columns -->

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