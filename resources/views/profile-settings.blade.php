@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Profile Settings")

@section ("main")

    <section class="bg-white py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Profile settings</h2>

            <form onsubmit="updateProfile(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" required
                        value="{{ auth()->user()->name ?? '' }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
                </div>

                <div class="mt-5 mb-5">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" disabled
                        value="{{ auth()->user()->email ?? '' }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2 disabled:bg-gray-200 disabled:cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Profile image</label>
                    <input type="file" name="profile_image"
                        accept="image/*" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
                </div>

                <div class="mt-5">
                    <button type="submit" name="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Update profile
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
      async function updateProfile(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/profile-settings",
                  formData
              )

              if (response.data.status == "success") {
                  window.location.reload();
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