@extends ("admin/layouts/app")
@section ("title", "Specialities")

@section ("main")

  <div class="pagetitle">
    <div style="display: flex;">
      <h1>Specialities</h1>

      <a href="{{ url('/admin/specialities/add') }}" class="btn btn-outline-primary btn-sm"
        style="margin-left: 15px;">Add speciality</a>
    </div>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Specialities</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">

        <form method="GET" action="{{ url('/admin/specialities') }}">
          <div class="input-group mb-3">
            <input type="text" name="search" class="form-control"
              placeholder="Search specialities"
              value="{{ $search }}" />

            <div class="input-group-append">
              <button class="btn btn-primary" type="submit">Search</button>
            </div>
          </div>
        </form>

        <table class="table table-bordered table-responsive">
          <thead>
            <tr>
              <th>Name</th>
              <th>Icon</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

            @if (count($specialities) <= 0)
              <tr>
                <td colspan="3">No speciality found.</td>
              </tr>
            @endif

            @foreach ($specialities as $speciality)
              <tr>
                <td>{{ $speciality->name ?? "" }}</td>
                
                <td>
                  @if ($speciality->icon)
                    <i class="{{ $speciality->icon ?? '' }}"></i>
                  @endif
                </td>

                <td>
                  <a href="{{ url('/admin/specialities/' . $speciality->id . '/edit') }}"
                    class="btn btn-outline-warning mb-2">Edit</a>&nbsp;
                  
                  <button type="button" class="btn btn-outline-danger" onclick="deleteData(event, '{{ $speciality->id }}', '{{ $speciality->name }}');">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {!! $pagination !!}
      </div>
    </div>
  </section>

  <script>
    function deleteData(event, id, name) {
      const node = event.currentTarget;

      swal.fire({
        title: name,
        text: "Are you sure you want to delete this speciality ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(async function (result) {
        if (result.isConfirmed) {
          node.setAttribute("disabled", "disabled");

          try {
            const formData = new FormData()
            formData.append("id", id)

            const response = await axios.post(
              baseUrl + "/admin/specialities/delete",
              formData
            )

            if (response.data.status == "success") {
              node.parentElement.parentElement.remove();
            } else {
              swal.fire("Error", response.data.message, "error")
            }
          } catch (exp) {
            swal.fire("Error", exp.message, "error")
          } finally {
            node.removeAttribute("disabled");
          }
        }
      })
    }
  </script>

@endsection