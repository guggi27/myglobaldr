@extends ("layouts/app")
@section ("title", "Group call")

@section ("main")

  <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.18.2/twilio-video.min.js"></script>

  <section class="bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-8">Group call with '{{ $call->p_name ?? "" }}'</h2>

      <div id="controls" class="mb-4 flex flex-col sm:flex-row gap-2">

        @if (in_array($call->status, ["created"]))
          <button id="join-btn" class="bg-green-600 hover:bg-green-700 cursor-pointer px-4 py-2 text-white rounded transition disabled:bg-gray-400 disabled:cursor-not-allowed">Join meeting</button>
          <button id="leave-btn" class="bg-red-500 hover:bg-red-600 px-4 py-2 cursor-pointer rounded text-white hidden transition disabled:bg-gray-400 disabled:cursor-not-allowed">Leave meeting</button>

          @if (auth()->user()->type == "patient")
            <button type="button" class="bg-red-600 hover:bg-red-700 cursor-pointer text-white px-4 py-2 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
              onclick="endVideo();"
              id="btn-end">
              End meeting
            </button>
          @endif
        @elseif ($call->status == "completed")
          <p class="text-green-500">Call has been completed</p>
        @endif
      </div>
      
      <div id="video-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <!-- Video tracks will be attached here -->
      </div>
    </div>
  </section>

  <input type="hidden" id="call-id" value="{{ $call->call_id ?? '' }}" />

  <script>

    const joinBtn = document.getElementById("join-btn");
    const leaveBtn = document.getElementById("leave-btn");
    const roomInput = document.getElementById("room-name");
    const videoContainer = document.getElementById("video-container");
    const callId = document.getElementById("call-id").value || "";

    let room = null;

    joinBtn.onclick = async function (event) {
      const node = event.currentTarget;
      node.setAttribute("disabled", "disabled");

      try {
        const formData = new FormData()
        formData.append("id", callId);

        const response = await axios.post(
            baseUrl + "/group-calls/verify",
            formData
        )

        if (response.data.status == "success") {
          const token = response.data.token;
          const roomName = response.data.room;

          room = await Twilio.Video.connect(token, {
            name: roomName,
            audio: true,
            video: { width: 640 }
          });

          console.log(`Connected to Room: ${roomName}`);

          joinBtn.classList.add("hidden");
          leaveBtn.classList.remove("hidden");
          roomInput.disabled = true;

          room.participants.forEach(participant => {
            attachParticipantTracks(participant);
          });

          room.on("participantConnected", participant => {
            console.log(`Participant "${participant.identity}" connected`);
            attachParticipantTracks(participant);
          });

          room.on("participantDisconnected", participant => {
            console.log(`Participant "${participant.identity}" disconnected`);
            detachParticipantTracks(participant);
          });

          const localTracks = Array.from(room.localParticipant.tracks.values());
          localTracks.forEach(publication => {
            attachTrack(publication.track);
          });
        } else {
          swal.fire("Error", response.data.message, "error");
        }
      } catch (exp) {
        console.log(exp)
        swal.fire("Error", exp.message, "error")
      } finally {
        node.removeAttribute("disabled");
      }
    };

    leaveBtn.onclick = () => {
      if (room) {
        room.disconnect();
        console.log("Disconnected");
        room = null;
        videoContainer.innerHTML = "";
        joinBtn.classList.remove("hidden");
        leaveBtn.classList.add("hidden");
        roomInput.disabled = false;
      }
    };

    function attachParticipantTracks(participant) {
      participant.tracks.forEach(publication => {
        if (publication.isSubscribed) {
          attachTrack(publication.track);
        }
      });

      participant.on("trackSubscribed", track => {
        attachTrack(track);
      });

      participant.on("trackUnsubscribed", track => {
        detachTrack(track);
      });
    }

    function attachTrack(track) {
      const element = track.attach();
      element.className = "rounded shadow";
      videoContainer.appendChild(element);
    }

    function detachParticipantTracks(participant) {
      participant.tracks.forEach(publication => {
        if (publication.track) {
          detachTrack(publication.track);
        }
      });
    }

    function detachTrack(track) {
      track.detach().forEach(element => element.remove());
    }

    async function endVideo() {
      const node = document.getElementById("btn-end");
      node.setAttribute("disabled", "disabled");

      try {
        const formData = new FormData()
        formData.append("id", callId);

        const response = await axios.post(
            baseUrl + "/group-calls/end",
            formData
        )

        if (response.data.status == "success") {
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

@endsection