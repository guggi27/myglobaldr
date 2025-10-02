@extends ("layouts/app")
@section ("title", config("config.app_name") . " | Login")

@section ("main")

  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12 text-center">
        <h2>Login</h2>
      </div>
    </div>

    <div class="row">
      <div class="offset-md-4 col-md-4">
        <form onsubmit="doLogin(event)" class="space-y-4">
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" required class="form-control" />
          </div>

          <div class="form-group mt-3 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" required class="form-control" />
          </div>

          <button type="submit" name="submit" 
              class="btn btn-primary bg-primary-gradient no-border" style="width: 100%;">
            Sign In
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- <section class="bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-8">Login</h2>

      <form onsubmit="doLogin(event)" class="space-y-4">
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
            Sign In
          </button>
        </div>
      </form>

      <div class="mt-2">
        <a href="{{ url('/forgot-password') }}" class="text-sm text-blue-600 hover:underline">
          Forgot your password?
        </a>
      </div>
    </div>
  </section> -->

  <script>
      async function doLogin(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/login",
                  formData
              )

              if (response.data.status == "success") {
                  // const accessToken = response.data.access_token
                  // localStorage.setItem(accessTokenKey, accessToken)

                  const urlSearchParams = new URLSearchParams(window.location.search)
                  const redirect = urlSearchParams.get("redirect") || ""
                  if (redirect == "") {
                      window.location.href = baseUrl
                  } else {
                      window.location.href = redirect
                  }
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