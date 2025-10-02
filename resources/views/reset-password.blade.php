@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Reset password")

@section ("main")

  <section class="bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-8">Reset password</h2>

      <form onsubmit="resetPassword(event)" class="space-y-4">
        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="token" value="{{ $token }}" />

        <div>
          <label class="block text-sm font-medium text-gray-700">New password</label>
          <input type="password" name="password" required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Confirm new password</label>
          <input type="password" name="confirm_password" required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
        </div>

        <div>
          <button type="submit" name="submit" 
              class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
            Reset password
          </button>
        </div>
      </form>
    </div>
  </section>

  <script>
      async function resetPassword(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/reset-password",
                  formData
              )

              if (response.data.status == "success") {
                  swal.fire("Reset Password", response.data.message, "success")
                      .then(function () {
                          window.location.href = baseUrl + "/login"
                      })
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