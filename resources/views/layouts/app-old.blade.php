@php
  $group_calls = fetch_group_calls();
@endphp

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>@yield("title", config("config.app_name"))</title>
        <script src="{{ asset('/js/browser@4.js') }}"></script>

        <link rel="stylesheet" type="text/css" href="{{ asset('/css/fontawesome.css') }}" />
        <script src="{{ asset('/js/fontawesome.js') }}"></script>

        <!-- <script src="{{ asset('/js/react.development.js') }}"></script> -->
        <!-- <script src="{{ asset('/js/react-dom.development.js') }}"></script> -->
        <!-- <script src="{{ asset('/js/babel.min.js') }}"></script> -->
        <!-- <script src="{{ asset('/js/html-react-parser.min.js') }}"></script> -->
        <script src="{{ asset('/js/axios.min.js') }}"></script>
        <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
        <!-- <script src="{{ asset('/js/twilio.min.js') }}"></script> -->

        <!-- Firebase UMD SDKs -->
        <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>

        <script src="{{ asset('/js/script.js?v=' . time()) }}"></script>
    </head>

    <body>

          <input type="hidden" id="baseUrl" value="{{ url('/') }}" />

          <script>
            const baseUrl = document.getElementById("baseUrl").value;

            let user = null;
          </script>

          @php
            $me = null;
          @endphp

          @if (auth()->check())
            @php
              $me = auth()->user();

              if ($me->profile_image && \Storage::exists("public/" . $me->profile_image))
              {
                $me->profile_image = url("/storage/" . $me->profile_image);
              }
            @endphp

            <input type="hidden" id="user" value="{{ json_encode([
              'id' => $me->id ?? 0,
              'name' => $me->name ?? '',
              'email' => $me->email ?? '',
              'profile_image' => $me->profile_image ?? '',
              'type' => $me->type ?? ''
            ]) }}" />

            <script>
              user = JSON.parse(document.getElementById("user").value);
            </script>

            @if ($me->type == "doctor")
              <script>

                let incomingCallId = "";

                function listenForCalls() {

                  ref = db.ref("calls/" + user.id);

                  // Listen for answer
                  ref.on("value", async snapshot => {

                    if (snapshot.exists()) {
                      const key = snapshot.key;
                      const value = snapshot.val();

                      try {
                        const formData = new FormData();
                        formData.append("id", value.callId);

                        const response = await axios.post(
                          baseUrl + "/calls/is-incoming",
                          formData
                        )

                        if (response.data.status == "success") {
                          const call = response.data.call;
                          const patient = response.data.patient;
                          incomingCallId = call.id;

                          document.getElementById('incomingCallModal').classList.remove('hidden');
                        } else {
                          // swal.fire("Error", response.data.message, "error")
                          ref.remove();
                        }
                      } catch (exp) {
                        // swal.fire("Error", exp.message, "error")
                      }
                    }
                  });
                }

                async function acceptCall() {
                  try {
                    document.getElementById("acceptCallBtn").setAttribute("disabled", "disabled");
                    document.getElementById("rejectCallBtn").setAttribute("disabled", "disabled");

                    const formData = new FormData();
                    formData.append("id", incomingCallId);

                    const response = await axios.post(
                      baseUrl + "/calls/accept",
                      formData
                    )

                    if (response.data.status == "success") {
                      window.location.href = baseUrl + "/calls/" + incomingCallId + "/detail";
                    } else {
                      swal.fire("Error", response.data.message, "error")
                    }
                  } catch (exp) {
                    swal.fire("Error", exp.message, "error")
                  } finally {
                    document.getElementById("acceptCallBtn").removeAttribute("disabled");
                    document.getElementById("rejectCallBtn").removeAttribute("disabled");
                  }
                }

                async function rejectCall() {
                  try {
                    document.getElementById("acceptCallBtn").setAttribute("disabled", "disabled");
                    document.getElementById("rejectCallBtn").setAttribute("disabled", "disabled");

                    const formData = new FormData();
                    formData.append("id", incomingCallId);

                    const response = await axios.post(
                      baseUrl + "/calls/reject",
                      formData
                    )

                    if (response.data.status == "success") {
                      db.ref("calls/" + user.id).remove();
                      document.getElementById('incomingCallModal').classList.add('hidden');
                    } else {
                      swal.fire("Error", response.data.message, "error")
                    }
                  } catch (exp) {
                    swal.fire("Error", exp.message, "error")
                  } finally {
                    document.getElementById("acceptCallBtn").removeAttribute("disabled");
                    document.getElementById("rejectCallBtn").removeAttribute("disabled");
                  }
                }

                async function acceptGroupCall(event, id) {
                  const node = event.currentTarget;

                  swal.fire({
                    title: "Accept call",
                    text: "Please be available at the mentioned time.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Accept"
                  }).then(async function (result) {
                    if (result.isConfirmed) {
                      try {
                        node.setAttribute("disabled", "disabled");

                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                          baseUrl + "/group-calls/accept",
                          formData
                        )

                        if (response.data.status == "success") {
                          window.location.href = baseUrl + "/group-calls/" + id + "/detail";
                        } else {
                          swal.fire("Error", response.data.message, "error")
                        }
                      } catch (exp) {
                        console.log(exp.message);
                        // swal.fire("Error", exp.message, "error")
                      } finally {
                        node.removeAttribute("disabled");
                      }
                    }
                  });
                }

                function rejectGroupCall(event, id) {
                  const node = event.currentTarget;

                  swal.fire({
                    title: "Reject call",
                    text: "You won't be able to join it later!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Reject"
                  }).then(async function (result) {
                    if (result.isConfirmed) {
                      try {
                        node.setAttribute("disabled", "disabled");

                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                          baseUrl + "/group-calls/reject",
                          formData
                        )

                        if (response.data.status == "success") {
                          swal.fire("Reject", response.data.message, "success")
                            .then(function () {
                              window.location.reload();
                            });
                        } else {
                          swal.fire("Error", response.data.message, "error");
                        }
                      } catch (exp) {
                        console.log(exp.message);
                        // swal.fire("Error", exp.message, "error")
                      } finally {
                        node.removeAttribute("disabled");
                      }
                    }
                  });
                }

                listenForCalls();
              </script>
            @endif
          @endif

          <script>
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            fetch(baseUrl + "/set-timezone", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                "_token": document.querySelector("meta[name='_token']").content,
                "timezone": timezone
              })
            });
          </script>
        
        <header class="bg-white shadow-md">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            
            <!-- Logo -->
            <div class="flex-shrink-0 text-xl font-bold text-blue-600">
              <a href="{{ url('/') }}">
                {{ config("config.app_name") }}
              </a>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex space-x-6 relative">
              <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600">Home</a>

              @if ($me != null)
                <div class="relative group">
                  <!-- Dropdown Trigger -->
                  <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                    <!-- Profile image -->
                    <img src="{{ $me->profile_image }}"
                         alt="{{ $me->name }}"
                         class="w-8 h-8 rounded-full object-cover border border-gray-300"
                         onerror="this.remove();" />
                         
                    <!-- Name -->
                    <span class="hidden sm:inline">{{ $me->name }}</span>
                  </button>

                  <!-- Dropdown Menu -->
                  <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 z-10">
                    <a href="{{ url('/profile-settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile settings</a>
                    <a href="{{ url('/balance') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Balance ({{ config("config.currency") . " " . ($me->balance ?? 0) }})</a>
                    <a href="{{ url('/calls') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Call logs</a>
                    <a href="{{ url('/group-calls') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Group call logs</a>
                    <a href="{{ url('/logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                  </div>
                </div>
              @else
                <a href="{{ url('/login') }}" class="text-gray-600 hover:text-blue-600">Login</a>
                <a href="{{ url('/register') }}" class="text-gray-600 hover:text-blue-600">Register</a>
              @endif

              <a href="#" class="text-gray-600 hover:text-blue-600">About</a>
              <a href="#" class="text-gray-600 hover:text-blue-600">Services</a>
              <a href="#" class="text-gray-600 hover:text-blue-600">Contact</a>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-gray-600 hover:text-blue-600 focus:outline-none"
                  id="menu-toggle">
                  <!-- Hamburger Icon -->
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" 
                      viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </button>
            </div>

          </div>

          <!-- Mobile Menu (hidden by default) -->
          <div 
            id="mobile-menu"
            class="hidden flex-col space-y-2 mt-2 md:hidden">
            <a href="{{ url('/') }}" class="block px-4 py-2 text-gray-600 hover:text-blue-600">Home</a>

            @if ($me != null)

              <!-- Dropdown Trigger -->
              <div>
                <button id="dropdown-toggle" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex justify-between items-center">
                  <img src="{{ $me->profile_image }}"
                     alt="{{ $me->name }}"
                     class="w-8 h-8 rounded-full object-cover border border-gray-300"
                     onerror="this.remove();" />

                  {{ $me->name }}

                  <svg class="h-4 w-4 ml-2 transform transition-transform" id="dropdown-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <!-- Dropdown Items -->
                <div id="dropdown-menu" class="hidden flex-col pl-6">
                  <a href="{{ url('/profile-settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile settings</a>
                  <a href="{{ url('/balance') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Balance ({{ config("config.currency") . " " . ($me->balance ?? 0) }})</a>
                  <a href="{{ url('/calls') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Call logs</a>
                  <a href="{{ url('/group-calls') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Group call logs</a>
                  <a href="{{ url('/logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
              </div>
            @else
              <a href="{{ url('/login') }}" class="block px-4 py-2 text-gray-600 hover:text-blue-600">Login</a>
              <a href="{{ url('/register') }}" class="block px-4 py-2 text-gray-600 hover:text-blue-600">Register</a>
            @endif

            <a href="#" class="block px-4 py-2 text-gray-600 hover:text-blue-600">About</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:text-blue-600">Services</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:text-blue-600">Contact</a>
          </div>
        </header>

        <script>
          const menuToggle = document.getElementById('menu-toggle');
          const mobileMenu = document.getElementById('mobile-menu');
          const dropdownToggle = document.getElementById('dropdown-toggle');
          const dropdownMenu = document.getElementById('dropdown-menu');
          const dropdownIcon = document.getElementById('dropdown-icon');

          if (menuToggle != null) {
            menuToggle.addEventListener('click', () => {
              mobileMenu.classList.toggle('hidden');
            });
          }

          if (dropdownToggle != null) {
            dropdownToggle.addEventListener('click', () => {
              dropdownMenu.classList.toggle('hidden');
              dropdownIcon.classList.toggle('rotate-180'); // Animate arrow
            });
          }
        </script>

        @if (count($group_calls) > 0)
          @foreach ($group_calls as $group_call)
            <div class="flex items-center space-x-4 block max-w-md mx-auto bg-white mt-10 border border-blue-500 rounded-xl shadow-md p-4 hover:bg-blue-50 transition">
                <!-- User Image -->
                <img src="{{ $group_call->p_profile_image }}" alt="{{ $group_call->p_name }}"
                  class="w-14 h-14 rounded-full border border-gray-300"
                  onerror="this.remove();" />

                <div class="flex-1">
                    <!-- Notification Text -->
                    <p class="text-blue-700 font-semibold text-lg">📞 You have a new call</p>

                    <!-- User Name -->
                    <p class="text-gray-800 font-medium">{{ $group_call->p_name }}</p>

                    <!-- Diseases List -->
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($group_call->diseases as $disease)
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $disease }}
                            </span>
                        @endforeach
                    </div>

                    @if (!empty($group_call->start))
                      <p class="flex flex-wrap mt-1">Time: {{ $group_call->start }}</p>
                    @endif

                    <div class="flex gap-3 mt-3">
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-sm rounded cursor-pointer transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                          onclick="acceptGroupCall(event, '{{ $group_call->call_id ?? '' }}');">
                            &check; Accept
                        </button>

                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 text-sm rounded cursor-pointer transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                          onclick="rejectGroupCall(event, '{{ $group_call->call_id ?? '' }}');">
                            X Reject
                        </button>
                    </div>
                </div>
            </div>
          @endforeach
        @endif

        @yield ("main")

        <footer class="bg-gray-100 text-gray-600">
          <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            
            <!-- Grid layout for sections -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">

              <!-- Column 1: About -->
              <div>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">About</h5>
                <p class="text-gray-500">
                  We build modern web experiences using Laravel and Tailwind CSS.
                </p>
              </div>

              <!-- Column 2: Links -->
              <div>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">Links</h5>
                <ul class="space-y-1">
                  <li><a href="{{ url('/') }}" class="hover:text-blue-600">Home</a></li>
                  <li><a href="#" class="hover:text-blue-600">Services</a></li>
                  <li><a href="#" class="hover:text-blue-600">Contact</a></li>
                </ul>
              </div>

              <!-- Column 3: Social -->
              <div>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">Follow Us</h5>
                <div class="flex space-x-4">
                  <a href="#" class="hover:text-blue-600">Facebook</a>
                  <a href="#" class="hover:text-blue-600">Twitter</a>
                  <a href="#" class="hover:text-blue-600">GitHub</a>
                </div>
              </div>

            </div>

            <!-- Bottom Bar -->
            <div class="mt-8 border-t pt-4 text-center text-sm text-gray-500">
              &copy; {{ date('Y') }} {{ config("config.app_name") }}. All rights reserved.
            </div>

          </div>
        </footer>

        @if ($me != null && $me->type == "doctor")
          <!-- Incoming Call Modal -->
          <div id="incomingCallModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
            <div class="bg-white text-black rounded-lg p-6 w-80 text-center shadow-xl">
              <h2 class="text-xl font-bold mb-4">📞 Incoming Call</h2>
              <div class="flex justify-between space-x-4">
                <button id="acceptCallBtn" class="w-1/2 bg-green-600 text-white py-2 rounded hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                  onclick="acceptCall();">Accept</button>
                <button id="rejectCallBtn" class="w-1/2 bg-red-600 text-white py-2 rounded hover:bg-red-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                  onclick="rejectCall();">Reject</button>
              </div>
            </div>
          </div>
        @endif

        <!-- Permission Request Modal -->
        <!-- <div id="permissionModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
          <div class="bg-white text-black rounded-lg p-6 w-96 text-center shadow-xl">
            <h2 class="text-xl font-bold mb-4">🎥 Allow Access</h2>
            <p class="mb-6">This app needs access to your camera and microphone for the video call.</p>
            <button id="grantPermissionBtn" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded">
              Allow Access
            </button>
          </div>
        </div> -->

        <script>
          /*const permissionModal = document.getElementById("permissionModal");
          const grantPermissionBtn = document.getElementById("grantPermissionBtn");

          let permissionGranted = false;

          grantPermissionBtn.onclick = async () => {
            try {
              const camPerm = await navigator.permissions.query({ name: 'camera' });
              const micPerm = await navigator.permissions.query({ name: 'microphone' });

              if (camPerm.state === 'denied' || micPerm.state === 'denied') {
                swal.fire("Permission blocked", "Camera or microphone permission is blocked. Please enable it in your browser settings.", "error");
                return;
              }

              localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
              permissionGranted = true;

              // Hide the permission modal
              permissionModal.classList.add("hidden");
            } catch (err) {
              swal.fire("Permission rejected", "Camera/Microphone access denied. Please enable to continue.", "error");
              console.error("Permission error:", err);
            }
          };*/
        </script>

        @if ($me != null)
          <script>
            async function initListening() {

              // localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });

              // if (localVideo != null) {
              //   localVideo.srcObject = localStream;
              // }

              /*roomRef = db.ref("rooms/" + user.id);
              peerConnection = new RTCPeerConnection(servers);

              // Listen for answer
              roomRef.once("value", async snapshot => {
                
                if (snapshot.exists()) {
                  const key = snapshot.key;
                  if (key == user.id) {

                    // Reference this when someone "joins" or signals
                    document.getElementById('incomingCallModal').classList.remove('hidden');

                    // Accept/Reject button logic
                    document.getElementById('acceptCallBtn').onclick = async () => {
                      document.getElementById('incomingCallModal').classList.add('hidden');
                      // Proceed with accepting the call (do nothing, just continue)
                    };

                    document.getElementById('rejectCallBtn').onclick = async () => {
                      document.getElementById('incomingCallModal').classList.add('hidden');

                      // Optional: Clear the room
                      if (roomRef) {
                        await roomRef.remove();
                        // window.location.reload(); // or redirect
                      }
                    };
                    
                    // const data = snapshot.val();
                    // if (!peerConnection.currentRemoteDescription && data?.answer) {
                    //   const answer = JSON.parse(data.answer);
                    //   await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
                    // }
                  }
                }
              });*/

              /*// Listen for callee ICE
              roomRef.child("calleeCandidates").on("child_added", snapshot => {
                const candidate = JSON.parse(snapshot.val());
                peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
              });

              localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
              localVideo.srcObject = localStream;

              const snapshot = await roomRef.get();
              const roomData = snapshot.val();

              if (!roomData?.offer) {
                alert("Room does not exist or offer not found.");
                return;
              }

              peerConnection.onicecandidate = event => {
                if (event.candidate) {
                  roomRef.child("calleeCandidates").push(JSON.stringify(event.candidate));
                }
              };

              peerConnection.ontrack = event => {
                if (!remoteStream) {
                  remoteStream = new MediaStream();
                  remoteVideo.srcObject = remoteStream;
                }
                remoteStream.addTrack(event.track);
              };

              localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
              });

              offer = JSON.parse(roomData.offer);
              await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));

              const answer = await peerConnection.createAnswer();
              await peerConnection.setLocalDescription(answer);
              await roomRef.update({ answer: JSON.stringify(answer) });

              roomRef.child("callerCandidates").on("child_added", snapshot => {
                const candidate = JSON.parse(snapshot.val());
                peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
              });*/
            }

            initListening();
          </script>
        @endif

    </body>
</html>