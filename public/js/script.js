const accessTokenKey = "MyGlobalDrAccessToken";

const globalState = {
    state: {
        user: null
    },

    listeners: [],

    listen (callBack) {
        this.listeners.push(callBack)
    },

    setState (newState) {
        this.state = {
            ...this.state,
            ...newState
        }

        for (let a = 0; a < this.listeners.length; a++) {
            this.listeners[a](this.state, newState)
        }
    }
}

const firebaseConfig = {
    apiKey: "AIzaSyA6AZo-7qddHihyqWlzI2jmfn9-n6sHwXk",
    authDomain: "national-hospital-6738d.firebaseapp.com",
    databaseURL: "https://national-hospital-6738d-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "national-hospital-6738d",
    storageBucket: "national-hospital-6738d.firebasestorage.app",
    messagingSenderId: "128924769557",
    appId: "1:128924769557:web:aafc9236af2380dcd05d1c"
};

// Initialize Firebase
const app = firebase.initializeApp(firebaseConfig);
const db = firebase.database();

let localStream;
let remoteStream;
let peerConnection;
let roomRef;
let localVideo;
let remoteVideo;

const servers = {
    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

function generateTimeSlots(from, to, difference) {
  const slots = [];

  // Parse input into Date objects
  let [fromH, fromM] = from.split(":").map(Number);
  let [toH, toM] = to.split(":").map(Number);

  let start = new Date();
  start.setHours(fromH, fromM, 0, 0);

  let end = new Date();
  end.setHours(toH, toM, 0, 0);

  while (start < end) {
    let h = String(start.getHours()).padStart(2, "0");
    let m = String(start.getMinutes()).padStart(2, "0");
    slots.push(`${h}:${m}`);

    // Add difference (in minutes)
    start.setMinutes(start.getMinutes() + difference);
  }

  return slots;
}

async function initCall(callId) {

    localVideo = document.getElementById("localVideo");
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    
    if (localVideo != null) {
        localVideo.srcObject = localStream;
    }

    roomRef = db.ref("rooms/" + callId);
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
    await roomRef.set({ offer: JSON.stringify(offer) });
}

async function acceptCall(callId) {
  remoteVideo = document.getElementById("remoteVideo");
  localVideo = document.getElementById("localVideo");
  localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
  
  if (localVideo != null) {
      localVideo.srcObject = localStream;
  }

  roomRef = db.ref("rooms/" + callId);
  peerConnection = new RTCPeerConnection(servers);

  // Listen for answer
  roomRef.once("value", async snapshot => {
    
    if (snapshot.exists()) {
      const key = snapshot.key;
      console.log(key)
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
  });

  roomRef.child("calleeCandidates").on("child_added", snapshot => {
      const candidate = JSON.parse(snapshot.val());
      peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
  });

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
  });
}

function openBase64File(base64String, fileType) {
    // Decode base64 to binary data
    const byteCharacters = atob(base64String);
    const byteNumbers = new Array(byteCharacters.length);
    
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    
    const byteArray = new Uint8Array(byteNumbers);
    const blob = new Blob([byteArray], { type: fileType });

    // Create a link pointing to the Blob
    const blobURL = URL.createObjectURL(blob);
    window.open(blobURL, '_blank');
}