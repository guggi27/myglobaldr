@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Register")

@section ("main")

  <section class="bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-8">Register</h2>

      <form onsubmit="doRegister(event)" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" id="name" name="name" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" name="password" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <button type="submit" name="submit" 
              class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
            Register
          </button>
        </div>
      </form>
    </div>
  </section>

  <script>
      async function doRegister(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/register",
                  formData
              )

              if (response.data.status == "success") {
                  swal.fire("Register", response.data.message, "success")
                    .then(function () {
                      window.location.href = baseUrl + "/login";
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