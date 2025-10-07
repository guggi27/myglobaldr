@extends ("admin/layouts/app")
@section ("title", "Edit speciality")

@section ("main")

  <div class="pagetitle">
    <h1>Edit speciality</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin/specialities') }}">Specialities</a></li>
        <li class="breadcrumb-item">{{ $speciality->name }}</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <form onsubmit="editData(event);" id="form-edit-data">
              <input type="hidden" name="id" value="{{ $speciality->id ?? 0 }}" />

              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" value="{{ $speciality->name ?? '' }}" required />
                </div>
              </div>

              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Icon</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="icon" value="{{ $speciality->icon ?? '' }}" />
                </div>
              </div>

              <input type="submit" name="submit" class="btn btn-warning" value="Update" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>

    async function editData(event) {
      event.preventDefault()

      const form = document.getElementById("form-edit-data");
      const formData = new FormData(form)
      form.submit.setAttribute("disabled", "disabled")

      try {
        const response = await axios.post(
          baseUrl + "/admin/specialities/update",
          formData
        )

        if (response.data.status == "success") {
          swal.fire("Update speciality", response.data.message, "success");
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