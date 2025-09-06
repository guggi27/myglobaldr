@extends ("admin/layouts/app")
@section ("title", "Change password")

@section ("main")

  <div class="pagetitle">
    <h1>Change password</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
        <li class="breadcrumb-item active">Change password</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">

    <div class="row">

      <!-- Left side columns -->
      <div class="col-md-12">
        <!-- Change Password Form -->
        <form onsubmit="changePassword(event);" id="form-change-password">
            <div class="form-group">
                <label class="form-label">Old Password</label>
                <input type="password" name="password" autocomplete="off" class="form-control" />
            </div>
            <div class="form-group mt-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" autocomplete="off" class="form-control" />
            </div>
            <div class="form-group mt-3 mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" autocomplete="off" class="form-control" />
            </div>
            <div class="submit-section">
                <button type="submit" name="submit" class="btn btn-primary submit-btn">Change password</button>
            </div>
        </form>
        <!-- /Change Password Form -->
      </div>
    </div>
  </section>

  <script>
    async function changePassword(event) {
        event.preventDefault();
        const form = document.getElementById("form-change-password");
        form.submit.setAttribute("disabled", "disabled");

        try {
            const formData = new FormData(form);

            const response = await axios.post(
              baseUrl + "/admin/change-password",
              formData
            );

            if (response.data.status == "success") {
                swal.fire("Change password", response.data.message, "success");
            } else {
                swal.fire("Error", response.data.message, "error");
            }
        } catch (exp) {
            console.log(exp.message);
        } finally {
            form.submit.removeAttribute("disabled");
        }
    }
</script>

@endsection