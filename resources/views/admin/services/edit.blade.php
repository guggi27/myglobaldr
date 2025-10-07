@extends ("admin/layouts/app")
@section ("title", "Edit service")

@section ("main")

  <div class="pagetitle">
    <h1>Edit service</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
        <li class="breadcrumb-item">{{ $service->name }}</li>
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
              <input type="hidden" name="id" value="{{ $service->id ?? 0 }}" />
              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" value="{{ $service->name ?? '' }}" required />
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
          baseUrl + "/admin/services/update",
          formData
        )

        if (response.data.status == "success") {
          swal.fire("Update service", response.data.message, "success");
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