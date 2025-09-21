<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>@yield('title', config('config.app_name'))</title>

    <!-- <link rel="preload" as="font" href="{{ asset('/fonts/Manrope-Regular.ttf') }}" type="font/ttf" crossorigin /> -->
    <!-- <link rel="preload" as="font" href="{{ asset('/fonts/Manrope-SemiBold.ttf') }}" type="font/ttf" crossorigin /> -->

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('/owl-carousel/assets/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/styles.css?v=' . time()) }}" />
</head>
<style>
    .custom-file-input {
        display: none;
        /* hide default */
    }

    .custom-file-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-file-label i {
        font-size: 1rem;
        color: #ccc;
        /* lighter tint */
        transition: color 0.3s;
    }

    .custom-file-label:hover i {
        color: #fff;
        /* highlight on hover */
    }

    .custom-file-label {
        width: 100%;
        padding: 16px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .custom-file-label:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Select box text + placeholder */
    select {
        background: rgba(255, 255, 255, 0.2);
        /* translucent tint */
        color: #fff;
        /* selected text white */
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        padding: 8px;
        backdrop-filter: blur(4px);
    }

    /* Placeholder text (first disabled option) */
    select option[disabled] {
        color: rgba(255, 255, 255, 0.6);
        /* softer white */
    }

    /* Dropdown options */
    select option {
        color: #000;
        /* black text inside dropdown */
        background: #fff;
        /* white background for options */
    }

    input::placeholder {
        color: lightgray !important;
    }



    label,
    small {
        color: rgb(236, 236, 236) !important;
    }

    /* Apply tint only to text-like inputs */
    input:not([type="radio"]):not([type="checkbox"]),
    select,
    textarea {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.3);
        /* padding: 8px; */
        border-radius: 6px;
        backdrop-filter: blur(4px);
    }

    h4 {
        color: rgba(255, 255, 255, 0.9);
        /* slightly tinted white */
    }

    body {
        color: rgba(255, 255, 255, 0.85);
        /* softer white tint */
    }

    @media (min-width: 768px) {

        /* md breakpoint */
        .mw-md-40 {
            max-width: 40% !important;
        }
    }
</style>

<body style="background: linear-gradient(
  80deg,
  rgba(4, 62, 84, 1.0) 0%,
  rgba(61, 213, 162, 1.0) 100%
);
">
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

            if ($me->profile_image && \Storage::exists('public/' . $me->profile_image)) {
                $me->profile_image = url('/storage/' . $me->profile_image);
            }
        @endphp

        <input type="hidden" id="user"
            value="{{ json_encode([
                'id' => $me->id ?? 0,
                'name' => $me->name ?? '',
                'email' => $me->email ?? '',
                'profile_image' => $me->profile_image ?? '',
                'type' => $me->type ?? '',
            ]) }}" />

        <script>
            user = JSON.parse(document.getElementById("user").value);
        </script>

        @if ($me->type == 'doctor')
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
                    }).then(async function(result) {
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
                    }).then(async function(result) {
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
                                        .then(function() {
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
    @section('title', 'Doctor Registration | MyGlobalDr')

    {{-- @section('main') --}}
    <div class="text" style="text-align: center; box-shadow: 1px 1px 10px white; padding:20px 0;">

        <h3 class="">
            <strong>
                MyGlobalDr Registration
            </strong>
        </h3>
        <p>
            Register as a doctor and start your work
        </p>
    </div>
    <div class="container mt-5 mb-4 mw-md-40" style="">

        <form action="{{ route('doctors.register') }}" method="POST" enctype="multipart/form-data">
            @csrf <!-- Add CSRF token -->
            <div class="form-group mb-3">
                <label class="form-label" for="salutation">Salutation</label>
                <select class="form-control" id="salutation">
                    <option>Mr</option>
                    <option>Mrs</option>
                    <option>Ms</option>
                    <option>Dr</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="first-name">Name</label>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input type="text" id="first-name" class="form-control" placeholder="First name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Last name">
                    </div>
                </div>
            </div>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                checked>
                            <label class="form-check-label" for="male">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                            <label class="form-check-label" for="female">
                                Female
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="prefer-not-to-say"
                                value="prefer-not-to-say">
                            <label class="form-check-label" for="prefer-not-to-say">
                                Prefer not to say
                            </label>
                        </div>

                    </div>
                </div>
            </fieldset>

            <div class="form-group mb-3">
                <label class="form-label" for="nationality">Nationality</label>
                <select class="form-control" id="nationality" name="nationality">
                    <option value="">-- Select Nationality --</option>
                    @foreach ($nationalities as $nationality)
                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="email">Email address</label>
                <div class="custom-input-group">
                    <input type="email" class="form-control" name="email" id="email" a
                        placeholder="Enter email">
                    <span class="input-group-text">
                        <i class="bi text-light bi-envelope-fill"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="phone">Phone</label>
                <div class="custom-input-group">
                    <input type="phone" class="form-control" name="phone" id="phone"
                        placeholder="Enter email">
                    <span class="input-group-text">
                        <i class="bi text-light bi-telephone-fill"></i>
                    </span>
                </div>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Address</h4>

            <div class="form-group mb-3">
                <label class="form-label" for="address">Street Address</label>
                <input type="text" name="address" class="form-control" id="address"
                    placeholder="Street #, House no 5">
            </div>

            <div class="form-group mb-2">
                <label class="form-label" for="address-line-2" class="text-light"> Address Line 2</label>
                <input type="text" class="form-control" name="address-2" id="address-line-2"
                    placeholder="Street 2#, House no 25">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="city" class="text-light">City</label>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="city" type="text" id="city" class="form-control"
                            placeholder="City">
                    </div>
                    <div class="col">
                        <input type="text" name="state" class="form-control"
                            placeholder="State/Reigon/Province">
                    </div>
                </div>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="postal" type="text" id="postal" class="form-control"
                            placeholder="Postal Code">
                    </div>
                    <div class="col">
                        <select class="form-control" id="medical-lisence-issuing-country"
                            name="medical-lisence-issuing-country">
                            <option value="" class="text-light">-- Select Country --</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Professional Information</h4>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-lisence-number">Medical License Number</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-lisence-number"
                        id="medical-lisence-number">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-lisence-number">Medical License Issuing Authority</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-lisence-issuing-authority"
                        id="medical-lisence-issuing-authority">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-lisence-issuing-country">Medical License Issuing
                    Country</label>
                <select class="form-control" id="medical-lisence-issuing-country"
                    name="medical-lisence-issuing-country">
                    <option value="">-- Select Country --</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="specialization">Specialization</label>
                <select class="form-control" id="specialization" name="specialization">
                    <option value="">-- Select Specialization --</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization }}">{{ $specialization }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="specialization">Sub specialization</label>
                <select class="form-control" id="sub-specialization" name="sub-specialization">
                    <option value="">-- Select Sub specialization --</option>
                    @foreach ($subSpecializations as $specialization)
                        <option value="{{ $specialization }}">{{ $specialization }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="experience">Years of experience</label>
                <div class="custom-input-group">
                    <input type="number" class="form-control" name="experience" id="experience">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Current Workspace Details
            </h4>

            <div class="form-group mb-3">
                <label class="form-label" for="clinic-name">Hospital/Clinic Name</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="clinic-name" id="clinic-name">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="clinic-address">Street Address</label>
                <input type="text" name="clinic-address" class="form-control" id="clinic-address"
                    placeholder="Street #, Jhon Clinic">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="clinic-address-line-2" class="text-light"> Address Line 2</label>
                <input type="text" class="form-control" name="clinic-address-line-2" id="clinic-address-line-2"
                    placeholder="Street 2#, Steven Clinic">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="city" class="text-light">City</label>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="city" type="text" id="city" class="form-control"
                            placeholder="City">
                    </div>
                    <div class="col">
                        <input type="text" name="state" class="form-control"
                            placeholder="State/Reigon/Province">
                    </div>
                </div>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="postal" type="text" id="postal" class="form-control"
                            placeholder="Postal Code">
                    </div>
                    <div class="col">
                        <select class="form-control" id="medical-lisence-issuing-country"
                            name="medical-lisence-issuing-country">
                            <option value="" class="text-light">-- Select Country --</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="position" class="text-light">Position/Designation</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="position" id="position">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Educational Details</h4>

            <div class="form-group mb-3">
                <label class="form-label" for="degree" class="text-light">Medical Degree (e.g. MD, MBBS, BDS
                    etc)</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="degree" id="degree">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="school-or-college" class="text-light">Medical
                    School/College/University Name</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="school-or-college" id="school-or-college">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="date-of-graduation" class="text-light">Date of Graduation</label>
                <input class="form-control datepicker" type="date" name="date-of-graduation"
                    id="date-of-graduation">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="additional-certifications" class="text-light">Additional
                    Certifications (e.g., Board
                    Certifications, Fellowships, etc.)</label>
                <textarea class="form-control" name="additional-certifications" id="additional-certifications"></textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="continuing-medical-education" class="text-light">Continuing Medical
                    Education (CME): (if
                    applicable)</label>
                <input class="form-control" type="text" name="continuing-medical-education"
                    id="continuing-medical-education">
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Professional Affiliations
            </h4>

            <div class="form-group mb-3">
                <label class="form-label" for="membership-in-medical-associations" class="text-light">Membership in
                    Medical Associations:
                    (e.g., AMA,
                    WHO, etc.)</label>
                <textarea class="form-control" name="membership-in-medical-associations" id="membership-in-medical-associations"></textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="licensing-bodies" class="text-light">Licensing Bodies (e.g., General
                    Medical Council, etc.)</label>
                <textarea class="form-control" name="licensing-bodies" id="licensing-bodies"></textarea>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Professional Affiliations
            </h4>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">Are you willing to treat international patients
                    </legend>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="willing-to-international-patients"
                                id="yes" value="yes" checked>
                            <label class="form-check-label" for="yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="willing-to-international-patients"
                                id="no" value="no">
                            <label class="form-check-label" for="no">
                                No
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="willing-to-international-patients"
                                id="prefer-not-to-say" value="prefer-not-to-say">
                            <label class="form-check-label" for="prefer-not-to-say">
                                Prefer not to say
                            </label>
                        </div>

                    </div>
                </div>
            </fieldset>

            <div class="form-group mb-3">
                <label class="form-label" for="preffered-patient-countries" class="text-light">Countries You Are
                    Willing to Treat
                    Patients From (Any Preferences Or Exclusions)
                </label>
                <textarea class="form-control" name="preffered-patient-countries" id="preffered-patient-countries"></textarea>
            </div>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">
                        Preferred Communication Methods: <small>(e.g., email, phone, video call)</small>
                    </legend>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication[]"
                                id="email" value="email">
                            <label class="form-check-label" for="email">Email</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication[]"
                                id="phone" value="phone">
                            <label class="form-check-label" for="phone">Phone Call</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication[]"
                                id="video" value="video">
                            <label class="form-check-label" for="video">Video Call</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication[]"
                                id="whatsapp" value="whatsapp">
                            <label class="form-check-label" for="whatsapp">WhatsApp</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication[]"
                                id="all" value="all">
                            <label class="form-check-label" for="all">All Of Above</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">Do you offer virtual consultations?
                    </legend>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="virtual-consultations"
                                id="yes" value="yes" checked>
                            <label class="form-check-label" for="yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="virtual-consultations"
                                id="no" value="no">
                            <label class="form-check-label" for="no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">Do you provide second opinions?
                    </legend>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="second-opinions" id="yes"
                                value="yes" checked>
                            <label class="form-check-label" for="yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="second-opinions" id="no"
                                value="no">
                            <label class="form-check-label" for="no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group mb-3">
                <label class="form-label text-light" for="membership-in-medical-associations">List of
                    Procedures/Treatments
                    Offered</label>
                <textarea class="form-control" name="treatments-list" id="treatments-list"></textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label text-light" for="membership-in-medical-associations">Estimated Costs
                    for Common
                    Procedures</label>
                <textarea class="form-control" name="estimated-cost" id="estimated-cost"></textarea>
            </div>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">Do you accept international insurance?
                    </legend>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="international-insurance"
                                id="yes" value="yes" checked>
                            <label class="form-check-label" for="yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="international-insurance"
                                id="no" value="no">
                            <label class="form-check-label" for="no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">
                        Payment Methods Accepted:
                    </legend>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">Bank Transfer</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                id="credit_card" value="credit_card">
                            <label class="form-check-label" for="credit_card">Credit Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                id="wire_transfer" value="wire_transfer">
                            <label class="form-check-label" for="wire_transfer">Wire Transfers</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_methods[]" id="cash"
                                value="cash">
                            <label class="form-check-label" for="cash">Cash</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                id="all_payments" value="all">
                            <label class="form-check-label" for="all_payments">All Of Above</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Documents Upload</h4>

            <div class="mb-3">
                <label class="form-label custom-file-label" for="medical_license">
                    Medical License
                    <i class="bi bi-upload ms-2"></i>
                </label>
                <input class="form-control custom-file-input" type="file" id="medical_license"
                    name="medical_license" accept=".pdf,.docx,.txt">
                <small class="form-text">
                    Please upload a scanned copy in PDF, DOCX, or TXT format.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label custom-file-label" for="degree_certificates">
                    Degree Certificates
                    <i class="bi bi-upload ms-2"></i>
                </label>
                <input class="form-control custom-file-input" type="file" id="degree_certificates"
                    name="degree_certificates[]" multiple accept=".pdf,.docx,.txt">
                <small class="form-text">
                    Please add all degree certificates and upload.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label custom-file-label" for="certifications">
                    Certifications
                    <i class="bi bi-upload ms-2"></i>
                </label>
                <input class="form-control custom-file-input" type="file" id="certifications"
                    name="certifications" accept=".pdf,.docx,.txt">
                <small class="form-text">
                    Please upload scanned copy of all certificates in one PDF, DOCX, or TXT format file.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label custom-file-label" for="resume">
                    Resume/CV
                    <i class="bi bi-upload ms-2"></i>
                </label>
                <input class="form-control custom-file-input" type="file" id="resume" name="resume"
                    accept=".pdf,.docx,.txt">
                <small class="form-text">
                    Please upload your resume/CV in one PDF, DOCX, or TXT file.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label custom-file-label" for="profile_picture">
                    Profile Picture
                    <i class="bi bi-upload ms-2"></i>
                </label>
                <input class="form-control custom-file-input" type="file" id="profile_picture"
                    name="profile_picture" accept="image/*" capture="user">
                <small class="form-text">
                    Please upload a profile picture (or capture using your webcam).
                </small>
            </div>

            <div class="mb-3">
                <p class="mb-1">Terms & Conditions</p>
                <div class="border p-3" style="max-height: 300px; overflow-y: auto; border-radius: 10px;">
                    <p><strong>1. Introduction</strong></p>
                    <p>
                        By registering as a patient or healthcare provider on MedVoyage Global's platform,
                        you agree to comply with and be bound by the following terms and conditions.
                    </p>

                    <p><strong>2. Use of Service</strong></p>
                    <ul>
                        <li><strong>Patients:</strong> You agree to provide accurate and complete information during
                            registration and use the service in accordance with applicable laws.</li>
                        <li><strong>Healthcare Providers:</strong> You must hold appropriate qualifications,
                            licenses, and comply with all professional standards and regulatory requirements.</li>
                    </ul>

                    <p><strong>3. Privacy and Data Protection</strong></p>
                    <p>
                        Your privacy is important to us. We will handle your personal data in accordance with the
                        General Data Protection Regulation (GDPR) and our Privacy Policy.
                    </p>

                    <p><strong>4. Limitation of Liability</strong></p>
                    <p>
                        MedVoyage Global is not liable for any indirect, incidental, or consequential damages
                        arising from the use or inability to use the services.
                    </p>

                    <p><strong>5. Dispute Resolution</strong></p>
                    <p>
                        Any disputes arising under these terms shall be resolved in accordance with UK law
                        and subject to the exclusive jurisdiction of the UK courts.
                    </p>

                    <p><strong>6. Changes to Terms</strong></p>
                    <p>
                        We reserve the right to modify these terms at any time. Changes will be communicated
                        through the platform.
                    </p>

                    <p><strong>7. Contact Information</strong></p>
                    <p>
                        For any questions regarding these terms, please contact us at
                        <a href="mailto:info@myglobaldr.com">info@myglobaldr.com</a>.
                    </p>
                </div>
                <div class="form-check mx-2 mt-2">
                    <input class="form-check-input" type="checkbox" name="terms-and-conditions"
                        id="terms-and-conditions" value="all">
                    <label class="form-check-label" for="all">I accept the Terms and Conditions.</label>
                </div>
            </div>

            <div class="signature">

                <!-- Simple signature pad -->
                <style>
                    .sig-wrap {
                        position: relative;
                        width: 100%;
                        max-width: 800px;
                        /* optional limit */
                    }

                    /* textarea-sized canvas */
                    #sigCanvas {
                        width: 100%;
                        height: 150px;
                        /* textarea-sized */
                        border: 1px solid rgba(0, 0, 0, 0.15);
                        border-radius: 4px;
                        touch-action: none;
                        /* prevents scrolling while drawing */
                        background: white;
                        display: block;
                    }

                    /* clear button positioned at the right middle */
                    .sig-clear-btn {
                        position: absolute;
                        right: 8px;
                        bottom: 5%;
                        color: white;
                        transform: translateY(-50%);
                        z-index: 10;
                        padding: 4px 10px;
                        font-size: 0.9rem;
                        border-radius: 4px;
                        border: 1px solid rgba(0, 0, 0, 0.12);
                        background: rgba(22, 19, 19, 0.9);
                        cursor: pointer;
                    }
                </style>

                <h6>Signature</h6>
                <div class="sig-wrap" aria-label="signature-pad-container">
                    <canvas id="sigCanvas" aria-label="signature pad"></canvas>
                    <button type="button" class="sig-clear-btn" id="sigClear">&times;</button>
                </div>

                <script>
                    (function() {
                        const canvas = document.getElementById('sigCanvas');
                        const ctx = canvas.getContext('2d');
                        const clearBtn = document.getElementById('sigClear');

                        // simple state
                        let drawing = false;
                        let last = null;

                        // scale canvas for devicePixelRatio for sharper lines
                        function resizeCanvas() {
                            const rect = canvas.getBoundingClientRect();
                            const dpr = window.devicePixelRatio || 1;
                            canvas.width = Math.round(rect.width * dpr);
                            canvas.height = Math.round(rect.height * dpr);
                            // scale drawing operations back to CSS pixels
                            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                            ctx.lineCap = 'round';
                            ctx.lineJoin = 'round';
                            ctx.strokeStyle = '#000'; // black stroke
                            ctx.lineWidth = 2;
                        }

                        // initial CSS sizing if none set
                        if (!canvas.style.height) canvas.style.height = '150px';

                        // convert pointer event to canvas coords (CSS pixels)
                        function getPos(evt) {
                            const r = canvas.getBoundingClientRect();
                            return {
                                x: evt.clientX - r.left,
                                y: evt.clientY - r.top
                            };
                        }

                        function start(e) {
                            e.preventDefault();
                            drawing = true;
                            last = getPos(e);
                        }

                        function move(e) {
                            if (!drawing) return;
                            e.preventDefault();
                            const p = getPos(e);
                            ctx.beginPath();
                            ctx.moveTo(last.x, last.y);
                            ctx.lineTo(p.x, p.y);
                            ctx.stroke();
                            last = p;
                        }

                        function end(e) {
                            if (!drawing) return;
                            drawing = false;
                            last = null;
                        }

                        // attach pointer events (works for mouse & touch)
                        canvas.addEventListener('pointerdown', start);
                        canvas.addEventListener('pointermove', move);
                        window.addEventListener('pointerup', end);
                        canvas.addEventListener('pointercancel', end);

                        // clear action
                        clearBtn.addEventListener('click', () => {
                            // clear using full canvas size (accounting for DPR transform)
                            const rect = canvas.getBoundingClientRect();
                            ctx.clearRect(0, 0, rect.width, rect.height);
                        });

                        // initial resize + keep crisp on resize
                        function init() {
                            resizeCanvas();
                        }
                        init();
                        window.addEventListener('resize', () => {
                            // preserve nothing — simple and minimal as requested
                            resizeCanvas();
                        });

                    })();
                </script>
            </div>
            <button class="btn mt-3" style="width: 100%; border: 1px solid white; color:white;">
                Submit
            </button>
    </div>
    </form>

    </div>
    <script>
        document.querySelectorAll(".custom-file-input").forEach(input => {
            input.addEventListener("change", function() {
                const fileName = this.files.length > 0 ? this.files[0].name : "Upload File";
                this.nextElementSibling.textContent = "📂 " + fileName;
            });
        });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear previous errors
            clearErrors();

            // Get signature data from canvas
            const canvas = document.getElementById('sigCanvas');
            const signatureData = canvas.toDataURL();

            // Create FormData object
            const formData = new FormData(this);
            formData.append('signature', signatureData);

            try {
                const response = await axios.post('{{ route('doctors.register') }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Registration submitted successfully',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    displayErrors(errors);

                    Swal.fire({
                        title: 'Validation Error!',
                        text: 'Please check the form for errors',
                        icon: 'error'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: error.response?.data?.message || 'Something went wrong',
                        icon: 'error'
                    });
                }
            }
        });

        // Function to display errors
        function displayErrors(errors) {
            for (let field in errors) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    // Add error class to input
                    input.classList.add('is-invalid');

                    // Create error message element
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.innerHTML = errors[field][0]; // Get first error message

                    // Insert error message after input
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            }
        }

        // Function to clear all errors
        function clearErrors() {
            // Remove all error messages
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Remove error classes
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }

        // Add error styling
        const style = document.createElement('style');
        style.textContent = `
            .is-invalid {
                border-color: #dc3545 !important;
                background-color: rgba(220, 53, 69, 0.1) !important;
            }

            .invalid-feedback {
                display: block;
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
        `;
        document.head.appendChild(style);
    </script>

    {{-- @endsection --}}




    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/owl-carousel/owl.carousel.js') }}"></script>

    <script src="{{ asset('/js/react.development.js') }}"></script>
    <script src="{{ asset('/js/react-dom.development.js') }}"></script>
    <script src="{{ asset('/js/babel.min.js') }}"></script>
    <script src="{{ asset('/js/axios.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/fontawesome.js') }}"></script>

    <!-- Firebase UMD SDKs -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>

    <script type="text/babel" src="{{ asset('/js/components/BestDoctors.js?v=' . time()) }}"></script>
    <script src="{{ asset('/js/script.js?v=' . time()) }}"></script>

    @yield('script')
</body>

</html>
