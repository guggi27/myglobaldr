@extends ("admin/layouts/app")
@section ("title", "Patients")

@section ("main")

  <div class="pagetitle">
    <div style="display: flex;">
      <h1>Patients</h1>
    </div>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Patients</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">

        <form method="GET" action="{{ url('/admin/patients') }}">
          <div class="input-group mb-3">
            <input type="text" name="search" class="form-control"
              placeholder="Search users"
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
              <th>Email</th>
              <th>Phone</th>
              <th>Image</th>
              <th>Registered at</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

            @if (count($users) <= 0)
              <tr>
                <td colspan="6">No patient found.</td>
              </tr>
            @endif

            @foreach ($users as $user)
              <tr>
                <td>{{ $user->name ?? "" }}</td>
                <td>{{ $user->email ?? "" }}</td>
                <td>{{ $user->phone ?? "" }}</td>
                <td>
                  <img src="{{ $user->profile_image }}"
                    style="width: 100px;
                      height: 100px;
                      object-fit: cover;
                      border-radius: 50%;"
                    onerror="event.target.remove();" />
                </td>
                <td>{{ date("d M, Y", strtotime($user->created_at . " UTC")) }}</td>
                <td>
                  @if (is_null($user->deleted_at))
                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(event, '{{ $user->id }}', '{{ $user->email }}');">Delete</button>
                  @else
                    <span class="text-danger">Patient has been deleted.</span>
                  @endif
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
    function deleteUser(event, id, email) {
      const node = event.currentTarget;

      swal.fire({
        title: email,
        text: "Are you sure you want to delete this doctor ?",
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
              baseUrl + "/admin/patients/delete",
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