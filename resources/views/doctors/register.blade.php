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
        <form action="#">
            @csrf <!-- Add CSRF token -->
            <div class="form-group mb-3">
                <label class="form-label" for="salutation">Salutation</label>
                <select class="form-control" name="salutation" id="salutation">
                    <option value="mr">Mr</option>
                    <option value="mrs">Mrs</option>
                    <option value="ms">Ms</option>
                    <option value="dr">Dr</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="first-name">Name</label>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input type="text" id="first-name" name="first-name" class="form-control"
                            placeholder="First name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="last-name" id="last-name"
                            placeholder="Last name">
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
                            <input class="form-check-input" type="radio" name="gender" id="gender-not-preferred"
                                value="gender-not-preferred">
                            <label class="form-check-label" for="gender-not-preferred">
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
                        placeholder="Enter phone number">
                    <span class="input-group-text">
                        <i class="bi text-light bi-telephone-fill"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="custom-input-group">
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="Enter password">
                    <span class="input-group-text">
                        <i class="bi text-light bi-lock-fill"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="confirm-password">Confirm Password</label>
                <div class="custom-input-group">
                    <input type="password" class="form-control" name="confirm-password" id="confirm-password"
                        placeholder="Enter confirm password">
                    <span class="input-group-text">
                        <i class="bi text-light bi-lock-fill"></i>
                    </span>
                </div>
            </div>

            <h4 style="border-bottom: 1px solid lightgray;" class="mt-5 mb-4 text-light">Address</h4>

            <div class="form-group mb-3">
                <label class="form-label" for="address">Street Address</label>
                <input type="text" name="street-address" class="form-control" id="address"
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
                        <select class="form-control" id="medical-licence-issuing-country"
                            name="medical-licence-issuing-country">
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
                <label class="form-label" for="medical-licence-number">Medical License Number</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-licence-number"
                        id="medical-licence-number">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-licence-issuing-authority">Medical License Issuing
                    Authority</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-licence-issuing-authority"
                        id="medical-licence-issuing-authority">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-licence-issuing-country">Medical License Issuing
                    Country</label>
                <select class="form-control" id="medical-licence-issuing-country"
                    name="medical-licence-issuing-country">
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
                <label class="form-label" for="sub-specialization">Sub specialization</label>
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
                <label class="form-label" for="clinic-city" class="text-light">City</label>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="clinic-city" type="text" id="clinic-city" class="form-control"
                            placeholder="Clinic City">
                    </div>
                    <div class="col">
                        <input type="text" name="clinic-state" class="form-control"
                            placeholder="State/Reigon/Province">
                    </div>
                </div>
                <div class="form-row mb-3" style="display: flex; gap: 10px;">
                    <div class="col">
                        <input name="clinic-postal" type="text" id="clinic-postal" class="form-control"
                            placeholder="Postal Code">
                    </div>
                    <div class="col">
                        <select class="form-control" id="clinic-country" name="clinic-country">
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
                <label class="form-label" for="medical-degree" class="text-light">Medical Degree (e.g. MD, MBBS, BDS
                    etc)</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-degree" id="medical-degree">
                    <span class="input-group-text">
                        <i class="bi text-light bi-paperclip"></i>
                    </span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="medical-institute-name" class="text-light">Medical
                    School/College/University Name</label>
                <div class="custom-input-group">
                    <input type="text" class="form-control" name="medical-institute-name"
                        id="medical-institute-name">
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
                            <input class="form-check-input" type="radio"
                                name="support-international-patients-treatment" id="yes" value="yes"
                                checked>
                            <label class="form-check-label" for="yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="support-international-patients-treatment" id="no" value="no">
                            <label class="form-check-label" for="no">
                                No
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="support-international-patients-treatment" id="prefer-not-to-say"
                                value="prefer-not-to-say">
                            <label class="form-check-label" for="prefer-not-to-say">
                                Prefer not to say
                            </label>
                        </div>

                    </div>
                </div>
            </fieldset>

            <div class="form-group mb-3">
                <label class="form-label" for="preferred-patient-countries" class="text-light">Countries You Are
                    Willing to Treat
                    Patients From (Any Preferences Or Exclusions)
                </label>
                <textarea class="form-control" name="preferred-patient-countries" id="preferred-patient-countries"></textarea>
            </div>

            <fieldset class="form-group mb-3">
                <div class="row">
                    <legend class="col-form-label pt-0">
                        Preferred Communication Methods: <small>(e.g., email, phone, video call)</small>
                    </legend>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication_methods[]"
                                id="email" value="email">
                            <label class="form-check-label" for="email">Email</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication_methods[]"
                                id="phone" value="phone">
                            <label class="form-check-label" for="phone">Phone Call</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication_methods[]"
                                id="video" value="video">
                            <label class="form-check-label" for="video">Video Call</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="preferred_communication_methods[]"
                                id="whatsapp" value="whatsapp">
                            <label class="form-check-label" for="whatsapp">WhatsApp</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input all-check" type="checkbox"
                                name="preferred_communication_methods[]" id="all" value="">
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
                            <input class="form-check-input all-check" type="checkbox" name="payment_methods[]"
                                id="all_payments" value="">
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
                    name="degree_certificates" multiple accept=".pdf,.docx,.txt">
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
                    <label class="form-check-label" for="terms-and-conditions">I accept the Terms and
                        Conditions.</label>
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
        </form>
    </div>

    </div>
    <script>
        (function() {
            // Utility helpers
            const $ = (sel, ctx = document) => ctx.querySelector(sel);
            const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
            const byName = (name, ctx = document) => Array.from((ctx || document).querySelectorAll('[name="' + name +
                '"]'));
            const showError = (el, msg) => {
                if (!el) return;
                // find or create a .field-error next to the field
                let wrapper = el.closest('.form-group') || el.parentElement;
                if (!wrapper) wrapper = document.body;
                let err = wrapper.querySelector('.field-error');
                if (!err) {
                    err = document.createElement('div');
                    err.className = 'field-error text-danger small mt-1';
                    wrapper.appendChild(err);
                }
                err.textContent = msg;
            };
            const clearErrors = (form) => {
                $$('.field-error', form).forEach(n => n.remove());
            };

            // Basic validators (expand if you need)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9+\-\s()]{6,20}$/; // permissive; change to your local format

            // Configure what to validate and rules
            const rules = {
                // Authentication
                'password': {
                    required: true,
                    type: 'text',
                    min: 8
                },

                'confirm-password': {
                    required: true,
                    type: 'text',
                    min: 8
                },

                // Basic Details
                'salutation': {
                    required: false
                },
                'first-name': {
                    required: true
                },
                'last-name': {
                    required: true
                },
                'gender': {
                    required: true
                },
                'nationality': {
                    required: true
                },

                // Contact Info
                'email': {
                    required: true,
                    type: 'email'
                },
                'phone': {
                    required: true,
                    type: 'phone'
                },

                // Address
                'street-address': {
                    required: true
                },
                'address-line-2': {
                    required: false
                },
                'city': {
                    required: true
                },
                'state': {
                    required: true
                },
                'postal': {
                    required: true
                },
                'country': {
                    required: true
                },

                // Professional Information
                'medical-licence-number': {
                    required: true
                },
                'medical-licence-issuing-authority': {
                    required: true
                },
                'medical-licence-issuing-country': {
                    required: true
                },
                'specialization': {
                    required: true
                },
                'sub-specialization': {
                    required: false
                },
                'experience': {
                    required: true,
                    type: 'number'
                },
                'clinic-name': {
                    required: true
                },
                'clinic-street-address': {
                    required: true
                },
                'clinic-address-line-2': {
                    required: false
                },
                'clinic-city': {
                    required: true
                },
                'clinic-state': {
                    required: true
                },
                'clinic-postal-code': {
                    required: true
                },
                'clinic-country': {
                    required: true
                },
                'position': {
                    required: true
                },

                // Educational Details
                'medical-degree': {
                    required: true
                },
                'institution-name': {
                    required: true
                },
                'graduation-date': {
                    required: true,
                    type: 'date'
                },
                'additional-certifications': {
                    required: false
                },
                'continuing-medical-education': {
                    required: false
                },

                // Professional Affiliations
                'memberships': {
                    required: false
                },
                'licensing-bodies': {
                    required: false
                },
                'other-affiliations': {
                    required: false
                },

                // Preferences
                'international-patients': {
                    required: true
                },
                'countries-preferred': {
                    required: false
                },
                'preferred_communication_methods': {
                    required: true
                },
                'virtual-consultations': {
                    required: true
                },
                'second-opinions': {
                    required: true
                },
                'treatments-offered': {
                    required: false
                },
                'estimated-costs': {
                    required: false
                },
                'insurance-accepted': {
                    required: true
                },
                'payment-methods[]': {
                    required: true
                },

                // Document Uploads
                'medical_license': {
                    required: false,
                    type: 'file'
                },
                'degree_certificates[]': {
                    required: false,
                    type: 'file'
                },
                'certifications': {
                    required: false,
                    type: 'file'
                },
                'resume': {
                    required: false,
                    type: 'file'
                },
                'profile_picture': {
                    required: false,
                    type: 'file'
                },

                // Legal
                'terms-and-conditions': {
                    required: true
                },
                'signature': {
                    required: true,
                    type: 'file'
                },

                // Misc
                '_token': {
                    required: false
                }
            };

            // Inputs that are file inputs to append as files
            const fileInputs = [
                'medical_license',
                'degree_certificates',
                'certifications',
                'resume',
                'profile_picture'
            ];

            // main
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('input[type="checkbox"].all-check').forEach(allCheck => {
                    allCheck.addEventListener('change', e => {
                        const groupName = e.target.name;
                        const checkboxes = document.querySelectorAll(
                            `input[name="${groupName}"]`);

                        checkboxes.forEach(cb => {
                            // Set all others to match the "all-check" state
                            cb.checked = e.target.checked;
                        });
                    });
                });

                const form = document.querySelector('form[action="#"]') || document.querySelector('form');
                if (!form) {
                    console.warn('Registration form not found.');
                    return;
                }

                // create status area
                let statusArea = document.createElement('div');
                statusArea.id = 'register-status';
                statusArea.style.marginTop = '12px';
                form.appendChild(statusArea);

                // hook submit
                form.addEventListener('submit', async (ev) => {
                    ev.preventDefault();
                    clearErrors(form);
                    statusArea.textContent = '';
                    statusArea.className = '';

                    // run validation
                    const validationErrors = [];

                    // validate defined rules
                    for (const [name, rule] of Object.entries(rules)) {
                        // handle inputs which might be selects or checkboxes or text inputs
                        const inputs = byName(name, form);
                        if (rule.required) {
                            if (!inputs || inputs.length === 0) {
                                // if not found, skip (maybe it's missing in markup)
                                continue;
                            }

                            // For checkboxes/radios we check checked
                            const first = inputs[0];
                            if (first.type === 'checkbox') {
                                const anyChecked = inputs.some(i => i.checked);
                                if (!anyChecked) validationErrors.push({
                                    el: first,
                                    msg: 'This field is required.'
                                });
                                continue;
                            }

                            if (first.type === 'radio') {
                                const anyChecked = inputs.some(i => i.checked);
                                if (!anyChecked) validationErrors.push({
                                    el: first,
                                    msg: 'Please select an option.'
                                });
                                continue;
                            }

                            // fallback: value presence
                            const val = first.value && first.value.trim();
                            if (!val) validationErrors.push({
                                el: first,
                                msg: 'This field is required.'
                            });
                            if (val && rule.type === 'email' && !emailRegex.test(val))
                                validationErrors.push({
                                    el: first,
                                    msg: 'Enter a valid email.'
                                });
                            if (val && rule.type === 'phone' && !phoneRegex.test(val))
                                validationErrors.push({
                                    el: first,
                                    msg: 'Enter a valid phone number.'
                                });
                            if (val && rule.type === 'number' && isNaN(Number(val)))
                                validationErrors.push({
                                    el: first,
                                    msg: 'Enter a valid number.'
                                });
                        } else {
                            // not required but format validations if present and value exists
                            const inputs = byName(name, form);
                            if (inputs && inputs[0]) {
                                const val = inputs[0].value && inputs[0].value.trim();
                                if (val) {
                                    if (rule.type === 'email' && !emailRegex.test(val))
                                        validationErrors.push({
                                            el: inputs[0],
                                            msg: 'Enter a valid email.'
                                        });
                                    if (rule.type === 'phone' && !phoneRegex.test(val))
                                        validationErrors.push({
                                            el: inputs[0],
                                            msg: 'Enter a valid phone number.'
                                        });
                                    if (rule.type === 'number' && isNaN(Number(val)))
                                        validationErrors.push({
                                            el: inputs[0],
                                            msg: 'Enter a valid number.'
                                        });
                                }
                            }
                        }
                    }

                    // Additional validations for specific fields:
                    const fname = form.querySelector('[name="first-name"]');
                    const lname = form.querySelector('[name="last-name"]');
                    if (fname && fname.value.trim().length < 2) validationErrors.push({
                        el: fname,
                        msg: 'First name must be at least 2 characters.'
                    });
                    if (lname && lname.value.trim().length < 1) validationErrors.push({
                        el: lname,
                        msg: 'Last name is required.'
                    });

                    const emailInput = form.querySelector('[name="email"]');
                    if (emailInput && emailInput.value && !emailRegex.test(emailInput.value.trim()))
                        validationErrors.push({
                            el: emailInput,
                            msg: 'Enter a valid email.'
                        });

                    const phoneInput = form.querySelector('[name="phone"]');
                    if (phoneInput && phoneInput.value && !phoneRegex.test(phoneInput.value.trim()))
                        validationErrors.push({
                            el: phoneInput,
                            msg: 'Enter a valid phone number.'
                        });

                    // Files: optional checks (example max 5MB)
                    for (const fileName of fileInputs) {
                        const fi = form.querySelector('[name="' + fileName + '"]');
                        if (!fi) continue;
                        if (fi.files && fi.files.length > 0) {
                            for (const f of fi.files) {
                                if (f.size > 5 * 1024 * 1024) {
                                    validationErrors.push({
                                        el: fi,
                                        msg: `File "${f.name}" is larger than 5MB.`
                                    });
                                }
                            }
                        }
                    }

                    // signature basic check: ensure some drawing exists (simple pixel check)
                    const sigCanvas = form.querySelector('#sigCanvas');
                    if (sigCanvas) {
                        // check if canvas is blank by comparing pixel data
                        const ctx = sigCanvas.getContext('2d');
                        try {
                            const px = ctx.getImageData(0, 0, sigCanvas.width, sigCanvas.height)
                                .data;
                            let allEmpty = true;
                            for (let i = 0; i < px.length; i += 4) {
                                if (px[i + 3] !== 0) { // non-transparent pixel
                                    allEmpty = false;
                                    break;
                                }
                            }
                            // only warn, not fail; comment out next line if signature should be optional
                            if (allEmpty) {
                                // optional: require signature by uncommenting next line
                                // validationErrors.push({ el: sigCanvas, msg: 'Please provide a signature.' });
                            }
                        } catch (err) {
                            // ignore cross-origin or other issues
                        }
                    }

                    // display validation errors
                    if (validationErrors.length > 0) {
                        validationErrors.forEach(e => showError(e.el, e.msg));
                        statusArea.className = 'text-danger small';
                        statusArea.textContent =
                            `There are ${validationErrors.length} validation error(s). Please fix and try again.`;
                        statusArea.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        return;
                    }

                    // build FormData
                    const fd = new FormData();

                    // helper to append input(s) by name
                    const appendField = (name) => {
                        // handle checkboxes arrays (name with [] or with given name)
                        const els = byName(name, form);
                        if (!els || els.length === 0) return;
                        const first = els[0];
                        if (first.type === 'checkbox') {
                            // if there are multiple checkboxes with same name, append all checked values
                            const checked = els.filter(i => i.checked).map(i => i.value);
                            // append as JSON array string OR multiple keys — backend must support
                            // Append as multiple entries: e.g., preferred_communication_methods[] -> multiple values
                            if (name.endsWith('[]')) {
                                checked.forEach(v => fd.append(name, v));
                            } else {
                                // safe option: append JSON
                                if (name === 'terms-and-conditions')
                                    fd.append(name, checked[0] ? true : false)
                                else
                                    fd.append(name, JSON.stringify(checked));
                            }
                            return;
                        }

                        if (first.type === 'radio') {
                            const checked = els.find(i => i.checked);
                            if (checked) fd.append(name, checked.value);
                            return;
                        }

                        // file inputs
                        if (first.type === 'file') {
                            const files = first.files;
                            if (!files || files.length === 0) return;
                            // Always append with the same name — Multer groups them automatically
                            for (const f of files) fd.append(name, f);
                            return;
                        }


                        // simple text/select/number etc.
                        if (name === 'salutation') {
                            fd.append(name, first.value.toLowerCase());
                        } else {
                            fd.append(name, first.value);
                        }

                    };
                    // Append most common fields by iterating inputs in the form (robust)
                    const allInputs = $$('input, select, textarea', form);
                    const appended = new Set();
                    allInputs.forEach(inp => {
                        const name = inp.name;
                        if (!name) return;
                        if (appended.has(name) && !name.endsWith('[]'))
                            return; // already appended non-array
                        // Skip the built-in submit buttons
                        if (inp.type === 'submit' || inp.type === 'button') return;
                        // For multiple checkbox with [] naming, we want to append each
                        if (inp.type === 'checkbox' && name.endsWith('[]')) {
                            // will be handled below via appendField
                            return;
                        }
                        appendField(name);
                        appended.add(name);
                    });

                    // For checkboxes with array style name 'preferred_communication_methods[]' ensure appended
                    const checkArrayNames = [
                        'preferred_communication_methods[]',
                        'payment_methods[]',
                        'degree_certificates[]'
                    ];

                    checkArrayNames.forEach(name => {
                        const els = byName(name, form);
                        if (!els || els.length === 0) return;

                        // filter out "all" or empty checkboxes
                        const checked = els
                            .filter(i => i.checked && i.value.trim() !== '' && i.value
                                .toLowerCase() !== 'all')
                            .map(i => i.value);

                        if (checked.length === 0) return; // nothing meaningful selected

                        // append them one by one like before
                        checked.forEach(v => fd.append(name, v));

                        appended.add(name);
                    });

                    // signature: convert canvas to blob and append as signature.png
                    const canvas = form.querySelector('#sigCanvas');
                    if (canvas) {
                        // Use toBlob (async) and only send after blob ready
                        try {
                            statusArea.className = 'text-muted small';
                            statusArea.textContent = 'Preparing signature and uploading...';
                            await new Promise((resolve, reject) => {
                                canvas.toBlob((blob) => {
                                    if (blob && blob.size > 0) {
                                        fd.append('signature', blob,
                                            'signature.png');
                                    }
                                    resolve();
                                }, 'image/png');
                            });
                        } catch (err) {
                            // ignore, continue without signature
                        }
                    }

                    // CSRF token (common Laravel name: _token)
                    const tokenInput = form.querySelector('input[name="_token"]');
                    if (tokenInput && tokenInput.value) {
                        // Add to headers later; also append to FormData so backend receives it in case of file upload
                        fd.append('_token', tokenInput.value);
                    } else {
                        // try meta
                        const meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta && meta.content) {
                            fd.append('_token', meta.content);
                        }
                    }

                    // You might want to show a loading state
                    statusArea.className = 'text-info small';
                    statusArea.textContent = 'Submitting...';

                    // prepare axios headers
                    const headers = {
                        'X-Requested-With': 'XMLHttpRequest'
                        // axios will set Content-Type to multipart/form-data with boundary automatically when you pass FormData
                    };

                    // if token present in meta, include as header (common in Laravel setups)
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken && metaToken.content) headers['X-CSRF-TOKEN'] = metaToken.content;

                    try {
                        const response = await axios.post('{{ env('API_HOST') }}/doctor/register',
                            fd, {
                                headers,
                                withCredentials: true
                            });
                        // success handling
                        statusArea.className = 'text-success small';
                        // backend should return a message — show it or a default success
                        statusArea.textContent = (response.data && response.data.message) ? response
                            .data.message : 'Registration submitted successfully.';
                        if (response.data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Account Created",
                                text: "Please wait for the activation of your account so you can log in and work here.",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                // Redirect after user closes the alert
                                window.location.href = "/";
                            });
                        }
                    } catch (err) {
                        console.error(err);
                        Swal.fire({
                            icon: "error",
                            title: "Submission failed",
                            text: err.response?.data?.message ||
                                "Something went wrong while submitting your form. Please try again.",
                        });
                        statusArea.className = 'text-danger small';
                        if (err.response && err.response.data) {
                            // Try to show backend validation errors (assumes structure: errors: {field: [msg]})
                            const data = err.response.data;
                            if (data.errors && typeof data.errors === 'object') {
                                // show inline errors for fields returned by backend
                                Object.keys(data.errors).forEach(k => {
                                    const el = form.querySelector('[name="' + k + '"]') ||
                                        form.querySelector('[name="' + k + '[]"]');
                                    showError(el || statusArea, Array.isArray(data.errors[
                                        k]) ? data.errors[k].join(', ') : String(
                                        data.errors[k]));
                                });
                                statusArea.textContent = data.message ||
                                    'Validation failed. Please correct highlighted fields.';
                            } else if (data.message) {
                                statusArea.textContent = data.message;
                            } else {
                                statusArea.textContent =
                                    'Submission failed. See console for details.';
                            }
                        } else {
                            statusArea.textContent =
                                'Network or server error. Please try again later.';
                        }
                    }
                }); // end submit listener
            });
        })();
    </script>

    {{-- <script>
        document.querySelectorAll(".custom-file-input").forEach(input => {
            input.addEventListener("change", function() {
                const fileName = this.files.length > 0 ? this.files.name : "Upload File";
                this.nextElementSibling.textContent = "📂 " + fileName;
            });
        });
        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const salutation = document.getElementsByName('salutation')
            const firstName = document.getElementsByName('first-name')
            const lastName = document.getElementsByName('last-name')
            const gender = document.getElementsByName('gender')
            const nationality = document.getElementsByName('nationality')
            const email = document.getElementsByName('email')
            const phone = document.getElementsByName('phone')
            const streetAddress = document.getElementsByName('street-address')
            const addressLine2 = document.getElementsByName('address-line-2')
            const city = document.getElementsByName('city')
            const state = document.getElementsByName('state')
            const postal = document.getElementsByName('postal')
            const country = document.getElementsByName('country')
            const medicalLicenseNumber = document.getElementsByName('medical-license-number')
            const medicalLicenseIssuingAuthority = document.getElementsByName(
                'medical-license-issuing-authority')
            const medicalLicenseIssuingCountry = document.getElementsByName('medical-license-issuing-country')
            const specialization = document.getElementsByName('specialization')
            const subSpecialization = document.getElementsByName('sub-specialization')
            const experience = document.getElementsByName('experience')
            const clinicName = document.getElementsByName('clinic-name')
            const clinicAddress = document.getElementsByName('clinic-address')
            const clinicAddressLine2 = document.getElementsByName('clinic-address-line-2')
            const clinicCity = document.getElementsByName('clinic-city')
            const clinicState = document.getElementsByName('clinic-state')
            const clinicPostal = document.getElementsByName('clinic-postal')
            const clinicCountry = document.getElementsByName('clinic-country')
            const position = document.getElementsByName('position')
            const medicalDegree = document.getElementsByName('medical-degree')
            const medicalInstituteName = document.getElementsByName('medical-institute-name')
            const dateOfGraduation = document.getElementsByName('date-of-graduation')
            const additionalCertifications = document.getElementsByName('additional-certifications')
            const continuingMedicalEducation = document.getElementsByName('continuing-medical-education')
            const membershipInMedicalAssociations = document.getElementsByName(
                'membership-in-medical-associations')
            const licensingBodies = document.getElementsByName('licensing-bodies')
            const supportInternationalPatientsTreatment = document.getElementsByName(
                'support-international-patients-treatment')
            const preferredPatientCountries = document.getElementsByName('preferred-patient-countries')
            const preferredCommunicationMethods = document.getElementsByName('preferred_communication_methods')
            const virtualConsultations = document.getElementsByName('virtual-consultations')
            const secondOpinions = document.getElementsByName('second-opinions')
            // const membershipInMedicalAssociations = document.getElementsByName(
            //     'membership-in-medical-associations')
            const internationalInsurance = document.getElementsByName('international-insurance')
            const paymentMethods = document.getElementsByName('payment_methods')
            const medicalLicense = document.getElementsByName('medical_license')
            const degreeCertificates = document.getElementsByName('degree_certificates')
            const certifications = document.getElementsByName('certifications')
            const resume = document.getElementsByName('resume')
            const profilePicture = document.getElementsByName('profile_picture')
            const termsAndConditions = document.getElementsByName('terms-and-conditions')
            // Clear previous errors
            clearErrors();

            // Get signature data from canvas
            const canvas = document.getElementById('sigCanvas');
            const signatureData = canvas.toDataURL();

            // Create FormData object
            try {
                const response = await axios.post('{{ env('API_HOST') }}/doctor/register', {
                    data: {
                        salutation: salutation[0].value,
                        firstName: firstName[0].value,
                        lastName: lastName[0].value,
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
                    errorDiv.innerHTML = errors[field]; // Get first error message

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
    </script> --}}

    {{-- @endsection --}}


    <script>
        /**
         * Collect all field values (inputs, selects, textareas) into a JS object.
         * Handles radios, single/multiple checkboxes, and multiple selects.
         */
        function getAllFormData() {
            const data = {};
            const elements = document.querySelectorAll("input, select, textarea");

            elements.forEach((el) => {
                const name = el.name || el.id;
                if (!name) return; // Skip unnamed fields

                if (el.type === "radio") {
                    // Store only checked radio
                    if (el.checked) data[name] = el.value;
                } else if (el.type === "checkbox") {
                    // Handle multiple checkboxes with same name as array
                    const checkboxes = document.querySelectorAll(`input[name="${name}"][type="checkbox"]`);
                    if (checkboxes.length > 1) {
                        data[name] = data[name] || [];
                        if (el.checked) data[name].push(el.value);
                    } else {
                        data[name] = el.checked;
                    }
                } else if (el.tagName === "SELECT" && el.multiple) {
                    data[name] = Array.from(el.selectedOptions).map((opt) => opt.value);
                } else {
                    data[name] = el.value;
                }
            });

            return data;
        }

        /**
         * Fill all inputs, selects, and textareas from a JS object.
         * Handles radios, checkboxes, and multiple selects correctly.
         */
        function setAllFormData(data) {
            const elements = document.querySelectorAll("input, select, textarea");

            elements.forEach((el) => {
                const name = el.name || el.id;
                if (el.type === 'file') return;
                if (!name || !(name in data)) return;

                const value = data[name];

                if (el.type === "radio") {
                    el.checked = el.value === value;
                } else if (el.type === "checkbox") {
                    if (Array.isArray(value)) {
                        el.checked = value.includes(el.value);
                    } else {
                        el.checked = Boolean(value);
                    }
                } else if (el.tagName === "SELECT" && el.multiple && Array.isArray(value)) {
                    Array.from(el.options).forEach((opt) => {
                        opt.selected = value.includes(opt.value);
                    });
                } else {
                    el.value = value ?? "";
                }
            });
        }

        /** 
         * Optional: Automatically save and restore all form data using localStorage.
         * You can remove this block if you want manual control.
         */
        window.addEventListener("DOMContentLoaded", () => {
            const saved = localStorage.getItem("formState");
            if (saved) {
                try {
                    setAllFormData(JSON.parse(saved));
                } catch (err) {
                    console.error("Failed to restore saved form data:", err);
                }
            }
        });

        // Auto-save on any change (every input event)
        window.addEventListener("input", () => {
            try {
                localStorage.setItem("formState", JSON.stringify(getAllFormData()));
            } catch (err) {
                console.error("Failed to save form data:", err);
            }
        });
    </script>


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
