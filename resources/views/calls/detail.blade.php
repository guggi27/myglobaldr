@extends ("layouts/app")
@section ("title", "Video call")

@section ("main")

  <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.18.2/twilio-video.min.js"></script>

  <div class="container mt-5 mb-5">
    <div class="row mb-3">
      <div class="col-md-12">
        <h2>Video call with '{{ $user->name }}'</h2>
      </div>
    </div>
  </div>

  <div class="container mb-5" style="border: 1px solid black;
      border-radius: 10px;">
    <div class="row">
        <!-- Video Area -->
        <div class="col-md-12">
          <div style="display: flex;" class="col-md-12 ps-5 pt-5 pb-0 mb-5">
            @if ($user->profile_image && Storage::exists("public/" . $user->profile_image))
              <img src="{{ url('/storage/' . $user->profile_image) }}"
                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;
                  margin-right: 10px;" />
            @endif

            <div>
              <span class="color-primary bold" style="display: block;
                font-size: 22px;
                position: relative;
                bottom: 5px;">{{ $user->name ?? "" }}</span>
              <span style="color: gray; font-size: 12px;
                position: relative;
                bottom: 5px;">Online</span>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12 ps-5 pe-5" style="position: relative;">
        <!-- <video id="localVideo" autoplay muted playsinline src="https://www.w3schools.com/tags/mov_bbb.mp4"
          style="width: 100%;"></video> -->

        <video id="localVideo" autoplay muted playsinline
          style="width: 100%;"></video>

        <video id="remoteVideo" autoplay playsinline class="w-full h-1/2 object-cover border-4 border-green-500 rounded"
          style="position: absolute;
            right: 5%;
            bottom: 5%;
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 1px solid black;"></video>
      </div>

      <div class="col-md-4 mt-5 mb-5 center-horizontal text-center">
        <div id="call-controls">
          <button type="button">
            <i class="fa fa-video"></i>
          </button>

          <button type="button" class="cancel">
            <i class="fa fa-phone"></i>
          </button>

          <button type="button">
            <i class="fa fa-microphone"></i>
          </button>

          <!-- <button type="button">
            <i class="fa fa-microphone-slash"></i>
          </button> -->
        </div>
      </div>

      {{--
      <div class="col-md-4">
        @if (in_array($call->status, ["calling", "accepted"]))
          <button type="button" class="bg-red-600 hover:bg-red-700 cursor-pointer text-white px-4 py-2 rounded w-full disabled:bg-gray-400 disabled:cursor-not-allowed"
            onclick="endVideo();"
            id="btn-end">
            End Video
          </button>

          @if ($call->status == "calling")
            <p class="text-green-500 mt-4" id="waiting-for-accept">Waiting for accept...</p>
          @endif

        @elseif ($call->status == "completed")
          <p class="text-green-500">Call has been completed</p>
        @elseif ($call->status == "rejected")
          <p class="text-red-500">Call has been rejected</p>
        @endif

        <!-- <h2 class="text-xl font-semibold mt-5 mb-4">Chat</h2> -->
        <div class="space-y-2">
          <!-- <div class="bg-gray-800 p-2 rounded">Hello 👋</div> -->
          <!-- <div class="bg-gray-800 p-2 rounded">Welcome to the call</div> -->
        </div>

        @if (in_array($call->status, ["calling", "accepted"]))
          <textarea
            id="input-message"
            name="message"
            placeholder="Type a prescription"
            rows="5"
            class="mt-4 w-full p-2 bg-gray-800 rounded focus:outline-none border-2 border-blue-500"
            {{ auth()->user()->type == "doctor" ? "" : "readonly" }}>{{ $call->message ?? "" }}</textarea>
          
          @if (auth()->user()->type == "doctor")
            <button type="button" class="bg-blue-600 hover:bg-blue-700 cursor-pointer text-white px-4 py-2 rounded w-full mt-4 mb-4 disabled:bg-gray-400 disabled:cursor-not-allowed"
              onclick="sendMessage();"
              id="btn-send">
              Send
            </button>
          @endif
        @endif
      </div>
      --}}
    </div>
  </div>

  <input type="hidden" id="user-id" value="{{ $user->id ?? 0 }}" />
  <input type="hidden" id="call-id" value="{{ $call->call_id ?? '' }}" />

@endsection

@section ("script")

  <script>
    const userId = parseInt(document.getElementById("user-id").value || "0");
    const callId = document.getElementById("call-id").value || "";
    localVideo = document.getElementById('localVideo');
    remoteVideo = document.getElementById('remoteVideo');

    async function sendMessage() {
      const node = document.getElementById("btn-send");
      node.setAttribute("disabled", "disabled");

      const message = document.getElementById("input-message").value || "";

      try {
        const formData = new FormData()
        formData.append("id", callId);
        formData.append("message", message);

        const response = await axios.post(
            baseUrl + "/calls/send-message",
            formData
        )

        if (response.data.status == "success") {
          db.ref("messages/" + callId).set({
            message: message
          });

          swal.fire("Message sent", response.data.message, "success");
        } else {
          swal.fire("Error", response.data.message, "error");
        }
      } catch (exp) {
        // swal.fire("Error", exp.message, "error")
      } finally {
        node.removeAttribute("disabled");
      }
    }

    async function endVideo() {
      const node = document.getElementById("btn-end");
      node.setAttribute("disabled", "disabled");

      try {
        const formData = new FormData()
        formData.append("id", callId);

        const response = await axios.post(
            baseUrl + "/calls/end",
            formData
        )

        if (response.data.status == "success") {
          db.ref("calls/" + userId).remove();

          swal.fire("Call ended", response.data.message, "success")
            .then(function () {
              window.location.reload();
            });
        } else {
          swal.fire("Error", response.data.message, "error");
        }
      } catch (exp) {
        // swal.fire("Error", exp.message, "error")
      } finally {
        node.removeAttribute("disabled");
      }
    }
  </script>

  @if (auth()->user()->type == "patient")
    <script>
      function listenForMessages() {
        ref = db.ref("messages/" + callId);

        // Listen for answer
        ref.on("value", async snapshot => {

          if (snapshot.exists()) {
            const key = snapshot.key;
            const value = snapshot.val();

            fetchMessage();
          }
        });
      }

      async function fetchMessage() {
        try {
          const formData = new FormData()
          formData.append("id", callId);

          const response = await axios.post(
              baseUrl + "/calls/fetch-message",
              formData
          )

          if (response.data.status == "success") {
            const messageContent = response.data.message_content;
            document.getElementById("input-message").value = messageContent
          } else {
            swal.fire("Error", response.data.message, "error");
          }
        } catch (exp) {
          // swal.fire("Error", exp.message, "error")
        }
      }

      window.addEventListener("load", function () {
        listenForMessages();
      });
    </script>
  @endif

  @if (in_array($call->status, ["calling", "accepted"]))
    <script>
      async function init() {
        const roomId = userId;

        try {
          const formData = new FormData()
          formData.append("id", callId);

          const response = await axios.post(
              baseUrl + "/calls/verify",
              formData
          )

          if (response.data.status == "success") {
            const token = response.data.token;
            const roomName = response.data.room;
            const room = await Twilio.Video.connect(token, {
              name: roomName,
              audio: true,
              video: true
            });

            Twilio.Video.createLocalVideoTrack().then(track => {
              const local = localVideo;
              local.srcObject = new MediaStream([track.mediaStreamTrack]);
            });

            /*room.participants.forEach(participant => {
              // console.log("Remote participant:", participant.identity);

              participant.on('trackSubscribed', track => {
                if (track.kind === 'video') {
                  const remote = document.getElementById('remoteVideo');
                  remote.srcObject = new MediaStream([track.mediaStreamTrack]);

                  document.getElementById("waiting-for-accept").remove();
                }
              });
            });*/

            room.on('participantConnected', participant => {
              participant.on('trackSubscribed', track => {
                if (track.kind === 'video') {
                  const remote = document.getElementById('remoteVideo');
                  remote.srcObject = new MediaStream([track.mediaStreamTrack]);

                  document.getElementById("waiting-for-accept").remove();
                }
              });
            });
          } else {
            swal.fire("Error", response.data.message, "error");
          }
        } catch (exp) {
            // console.log(exp)
            swal.fire("Error", exp.message, "error")
        }

        /*localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;

        roomRef = db.ref("rooms/" + roomId);
        peerConnection = new RTCPeerConnection(servers);

        // Add local tracks
        localStream.getTracks().forEach(track => {
          peerConnection.addTrack(track, localStream);
        });

        // Handle ICE candidates
        peerConnection.onicecandidate = event => {
          if (event.candidate) {
            roomRef.child("callerCandidates").push(JSON.stringify(event.candidate));
          }
        };

        // Handle remote stream
        peerConnection.ontrack = event => {
          if (!remoteStream) {
            remoteStream = new MediaStream();
            remoteVideo.srcObject = remoteStream;
          }
          remoteStream.addTrack(event.track);
        };

        // Create and set local offer
        let offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        // Save offer to Firebase
        await roomRef.set({ offer: JSON.stringify(offer) });*/
      }

      // init();
    </script>
  @endif

  <script>
    async function test() {
      localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      localVideo.srcObject = localStream;
      remoteVideo.srcObject = localStream;
    }

    test();
  </script>

@endsection