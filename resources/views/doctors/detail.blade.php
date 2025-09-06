@extends ("layouts/app")
@section ("title", $user->name)

@section ("main")

    <section class="bg-white py-10">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Doctor Profile Header -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
          
          <!-- Profile Image -->
          <img src="{{ $user->profile_image }}"
               alt="{{ $user->name ?? '' }}"
               class="w-36 h-36 rounded-full object-cover shadow-md"
               onerror="this.src = baseUrl + '/img/user-placeholder.png'"
               style="width: 100%;" />

          <!-- Doctor Info -->
          <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $user->name ?? "" }}</h1>

            <!-- Tags / Services -->
            <div class="mt-4 flex flex-wrap gap-2 text-sm">
              @foreach ($user->specialities as $speciality)
                <span class="px-3 py-1 rounded-full"
                  style="background-color: {{ random_color() }};
                    color: white;">{{ $speciality }}</span>
              @endforeach
            </div>

            <div class="mt-8 grid md:grid-cols-1 gap-4">
              <p>Fee {{ strtoupper(config("config.currency")) . " " . ($user->fee ?? 0) }}</p>

              <p>
                <a href="javascript:void(0);" onclick="doVideoCall(event);">
                  <i class="fa-solid fa-video text-green-600 text-3xl"></i>
                </a>
              </p>
            </div>
          </div>
        </div>

        <!-- Optional: Contact / Appointment Section -->
        {{--<div class="mt-10 border-t pt-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Book an Appointment</h2>

          <form class="space-y-4 max-w-md">
            <input type="text" placeholder="Your Name" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <input type="email" placeholder="Your Email" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <input type="date" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Submit</button>
          </form>
        </div>--}}
      </div>
    </section>

    <input type="hidden" id="user-id" value="{{ $user->id }}" />

    <script>

      const userId = document.getElementById("user-id").value || "";

      async function doVideoCall(event) {
        const node = event.currentTarget;
        node.querySelector(".fa-video").remove();

        let i = document.createElement("i");
        i.setAttribute("class", "fa-solid fa-spinner text-gray-600 text-3xl");
        node.appendChild(i);

        try {
            const formData = new FormData()
            formData.append("id", userId);
            formData.append("type", "video");

            const response = await axios.post(
                baseUrl + "/calls/start",
                formData
            )

            if (response.data.status == "success") {
                const callId = response.data.call_id;

                ref = db.ref("calls/" + userId);
                await ref.set({ callId: callId });

                window.location.href = baseUrl + "/calls/" + callId + "/detail";
            } else {
                swal.fire("Error", response.data.message, "error")
            }
        } catch (exp) {
            if (exp?.response?.status == 401) {
              swal.fire("Un-authenticated", "Please login first.", "error")
            } else {
              swal.fire("Error", exp.message, "error")
            }
        }
      }
    </script>

  @endsection