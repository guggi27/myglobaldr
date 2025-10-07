@extends ("admin/layouts/app")
@section ("title", "Add speciality")

@section ("main")

  <div class="pagetitle">
    <h1>Add speciality</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin/specialities') }}">Specialities</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <form onsubmit="addData(event);" id="form-add-data">
              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" required />
                </div>
              </div>

              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Icon</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="icon" />
                </div>
              </div>

              <input type="submit" name="submit" class="btn btn-outline-primary" value="Add" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>

    async function addData(event) {
      event.preventDefault()

      const form = document.getElementById("form-add-data")
      const formData = new FormData(form)
      form.submit.setAttribute("disabled", "disabled")

      try {
        const response = await axios.post(
          baseUrl + "/admin/specialities/add",
          formData
        )

        if (response.data.status == "success") {
          swal.fire("Add speciality", response.data.message, "success")
            .then(function () {
              window.location.href = baseUrl + "/admin/specialities";
            });
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