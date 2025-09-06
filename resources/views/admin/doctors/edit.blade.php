@extends ("admin/layouts/app")
@section ("title", "Edit doctor")

@section ("main")

  <div class="pagetitle">
    <h1>Edit doctor</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin/doctors') }}">Doctors</a></li>
        <li class="breadcrumb-item">{{ $user->name }}</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <form onsubmit="editDoctor(event);" id="form-edit-doctor">
              <input type="hidden" name="id" value="{{ $user->id ?? 0 }}" />
              <div class="row mt-5 mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" value="{{ $user->name ?? '' }}" required />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" disabled />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Services</label>
                <div class="col-sm-10">
                  @foreach ($services as $service)
                    <p>
                      <label>
                        {{ $service->name ?? "" }}&nbsp;
                        <input type="checkbox" class="services" value="{{ $service->name ?? '' }}"
                          {{ in_array($service->name ?? '', $doctor?->services ?? []) ? "checked" : "" }} />
                      </label>
                    </p>
                  @endforeach
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Specialities</label>
                <div class="col-sm-10">
                  @foreach ($specialities as $speciality)
                    <p>
                      <label>
                        {{ $speciality->name ?? "" }}&nbsp;
                        <input type="checkbox" class="specialities" value="{{ $speciality->name ?? '' }}"
                          {{ in_array($speciality->name ?? '', $doctor?->specialities ?? []) ? "checked" : "" }} />
                      </label>
                    </p>
                  @endforeach
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Diseases</label>
                <div class="col-sm-10">
                  @foreach ($diseases as $disease)
                    <p>
                      <label>
                        {{ $disease->name ?? "" }}&nbsp;
                        <input type="checkbox" class="diseases" value="{{ $disease->name ?? '' }}"
                          {{ in_array($disease->name ?? '', $doctor?->diseases ?? []) ? "checked" : "" }} />
                      </label>
                    </p>
                  @endforeach
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fee ({{ strtoupper(config("config.currency")) }})</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="fee" value="{{ $doctor?->fee ?? 0 }}" />
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

    async function editDoctor(event) {
      event.preventDefault()

      const form = document.getElementById("form-edit-doctor")
      const formData = new FormData(form)
      form.submit.setAttribute("disabled", "disabled")

      const services = document.querySelectorAll(".services:checked");
      const servicesArr = [];
      for (let a = 0; a < services.length; a++) {
        servicesArr.push(services[a].value || "");
      }
      formData.append("services", JSON.stringify(servicesArr));

      const specialities = document.querySelectorAll(".specialities:checked");
      const specialitiesArr = [];
      for (let a = 0; a < specialities.length; a++) {
        specialitiesArr.push(specialities[a].value || "");
      }
      formData.append("specialities", JSON.stringify(specialitiesArr));

      const diseases = document.querySelectorAll(".diseases:checked");
      const diseasesArr = [];
      for (let a = 0; a < diseases.length; a++) {
        diseasesArr.push(diseases[a].value || "");
      }
      formData.append("diseases", JSON.stringify(diseasesArr));

      try {
        const response = await axios.post(
          baseUrl + "/admin/doctors/update",
          formData
        )

        if (response.data.status == "success") {
          swal.fire("Update doctor", response.data.message, "success");
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