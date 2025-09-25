@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Home")

@section ("main")

    <section class="bg-white py-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Specialities</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($specialities as $speciality)
                <!-- Single Service Card -->
                <a href="{{ url('/doctors?speciality=' . ($speciality->name ?? '')) }}" class="block">
                  <div class="flex flex-col items-center bg-gray-50 p-6 rounded-xl shadow hover:shadow-md transition">
                    <div class="text-blue-500 mb-4">
                      <i class="{{ $speciality->icon ?? '' }}"
                        style="font-size: 30px;"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">{{ $speciality->name ?? "" }}</h3>
                  </div>
                </a>
            @endforeach
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-8 mt-8">Select disease(s)</h2>

        <form onsubmit="findDoctorForDiseases(event);" id="form-diseases">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              @foreach ($diseases as $disease)
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition">
                  <input type="checkbox" name="diseases[]" value="{{ $disease->name ?? '' }}" class="accent-blue-600">
                  <span class="text-gray-800">{{ $disease->name ?? '' }}</span>
                </label>
              @endforeach
          </div>

          <button type="submit" name="submit" 
              class="w-full bg-blue-600 text-white mt-5 px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
            Find doctor
          </button>
        </form>
      </div>
    </section>

    <script>
      async function findDoctorForDiseases(event) {
        event.preventDefault();
        const form = document.getElementById("form-diseases");

        try {
          const formData = new FormData(form)
          form.submit.setAttribute("disabled", "disabled")

          const response = await axios.post(
            baseUrl + "/doctors/find-for-diseases",
            formData
          )

          if (response.data.status == "success") {
            swal.fire("Find doctor", response.data.message, "success")
              .then(function () {
                window.location.reload();
              });
          } else {
            swal.fire("Error", response.data.message, "error")
          }
        } catch (exp) {
          console.log(exp.message);
          // swal.fire("Error", exp.message, "error")
        } finally {
          form.submit.removeAttribute("disabled")
        }
      }
    </script>

@endsection