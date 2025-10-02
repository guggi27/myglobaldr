@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Forgot password")

@section ("main")

  <section class="bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-8">Forgot password</h2>

      <form onsubmit="sendResetLink(event)" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <button type="submit" name="submit" 
              class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
            Send reset password link
          </button>
        </div>
      </form>

      <div class="mt-2">
        <a href="{{ url('/login') }}" class="text-sm text-blue-600 hover:underline">
          Login
        </a>
      </div>
    </div>
  </section>

  <script>
      async function sendResetLink(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/send-password-reset-link",
                  formData
              )

              if (response.data.status == "success") {
                  swal.fire("Reset password", response.data.message, "success");
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