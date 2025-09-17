@extends ("layouts/app")
@section('title', 'Schedules')

@php
    $step = request('step');
@endphp

@section('main')
    <style>
        .body {
            background-color: #f8f9fa;

        }
    </style>
    <div class="container my-3">

        <!-- Header -->
        <div class="schedules-header container d-flex justify-content-between align-items-center">
            <div class="schedules-logo">
                {{-- <img src="{{ asset('/img/sm-logo.png') }}" alt=""> --}}
                {{-- <span class="fw-bold">MedBook</span> --}}
            </div>
            <button class="btn bg-primary-gradient no-border white bold">Back</button>
        </div>
        <!-- Doctor Profile Card -->
        <div class="schedules-doctor-card border-primary-gradient shadow-sm">
            <div class="schedules-doctor-info">
                <div class="schedules-doctor-info-img-container">
                    <img class="schedules-doctor-info-img" src="{{ asset('/img/doctors/2.png') }}" alt="">
                    <div class="bg-dark schedules-doctor-info-img" style="width: 100%; height: 100%;"></div>
                    <div class="schedules-icons-container">
                        <div class="schedules-star-icon">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.63057 1.28424C3.92993 0.362934 5.23333 0.362934 5.53269 1.28425L5.92871 2.50309C6.06259 2.91512 6.44654 3.19408 6.87977 3.19408H8.16134C9.13007 3.19408 9.53284 4.43369 8.74913 5.00309L7.71231 5.75638C7.36183 6.01103 7.21517 6.46239 7.34904 6.87442L7.74507 8.09327C8.04442 9.01458 6.98994 9.7807 6.20623 9.2113L5.16942 8.45801C4.81893 8.20336 4.34433 8.20336 3.99385 8.45801L2.95703 9.2113C2.17332 9.7807 1.11884 9.01458 1.41819 8.09326L1.81422 6.87442C1.94809 6.46239 1.80143 6.01103 1.45095 5.75638L0.414131 5.00309C-0.369582 4.43369 0.0331938 3.19408 1.00192 3.19408H2.28349C2.71672 3.19408 3.10067 2.91512 3.23455 2.50309L3.63057 1.28424Z"
                                    fill="white" />
                            </svg>
                            4.2
                        </div>
                        <div class="schedules-doctor-heart-icon">
                            <svg width="20" height="20" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.9602 2.06689C9.7535 2.06689 8.6735 2.65356 8.00016 3.55356C7.32683 2.65356 6.24683 2.06689 5.04016 2.06689C2.9935 2.06689 1.3335 3.73356 1.3335 5.79356C1.3335 6.58689 1.46016 7.32023 1.68016 8.00023C2.7335 11.3336 5.98016 13.3269 7.58683 13.8736C7.8135 13.9536 8.18683 13.9536 8.4135 13.8736C10.0202 13.3269 13.2668 11.3336 14.3202 8.00023C14.5402 7.32023 14.6668 6.58689 14.6668 5.79356C14.6668 3.73356 13.0068 2.06689 10.9602 2.06689Z"
                                    fill="#CE93D8" />
                            </svg>

                        </div>
                    </div>
                </div>
                <div class="schedules-doctor-details flex-grow-1">
                    <h5>Dr. Kamran</h5>
                    <div class="schedules-doctor-specialty">MBBS, DNB - Neurology</div>
                    <hr>
                    <div style="display: flex; align-items: start; justify-content: space-between;gap:10px">

                        <div class="schedules-status-badges">
                            <span class="schedules-location-badge">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.99992 8.95346C9.14867 8.95346 10.0799 8.02221 10.0799 6.87346C10.0799 5.7247 9.14867 4.79346 7.99992 4.79346C6.85117 4.79346 5.91992 5.7247 5.91992 6.87346C5.91992 8.02221 6.85117 8.95346 7.99992 8.95346Z"
                                        stroke="#012047" stroke-width="1.5" />
                                    <path
                                        d="M2.4133 5.66016C3.72664 -0.113169 12.28 -0.106502 13.5866 5.66683C14.3533 9.0535 12.2466 11.9202 10.4 13.6935C9.05997 14.9868 6.93997 14.9868 5.5933 13.6935C3.7533 11.9202 1.64664 9.04683 2.4133 5.66016Z"
                                        stroke="#012047" stroke-width="1.5" />
                                </svg>
                                <span>Lahore, Pakistan</span>
                            </span>
                            <span class="schedules-votes-badge">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.36328 10.7039L6.17161 12.1039C6.40495 12.3373 6.92995 12.4539 7.27995 12.4539H9.49662C10.1966 12.4539 10.9549 11.9289 11.1299 11.2289L12.5299 6.97061C12.8216 6.15394 12.2966 5.45394 11.4216 5.45394H9.08828C8.73828 5.45394 8.44661 5.16228 8.50495 4.75394L8.79661 2.88728C8.91328 2.36228 8.56328 1.77894 8.03828 1.60394C7.57161 1.42894 6.98828 1.66228 6.75495 2.01228L4.36328 5.57061"
                                        stroke="#012047" stroke-width="1.5" stroke-miterlimit="10" />
                                    <path
                                        d="M1.38818 10.7044V4.98773C1.38818 4.17106 1.73818 3.87939 2.55485 3.87939H3.13818C3.95485 3.87939 4.30485 4.17106 4.30485 4.98773V10.7044C4.30485 11.5211 3.95485 11.8127 3.13818 11.8127H2.55485C1.73818 11.8127 1.38818 11.5211 1.38818 10.7044Z"
                                        stroke="#012047" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span>98% (252 / 287 Votes)</span>
                            </span>
                            <span class="schedules-experience-badge">
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.896 5.2793C6.93433 5.65846 8.066 5.65846 9.10433 5.2793" stroke="#012047"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M10.3115 1.1665H4.68819C3.44569 1.1665 2.43652 2.1815 2.43652 3.41817V11.6373C2.43652 12.6873 3.18902 13.1307 4.11069 12.6232L6.95736 11.0423C7.26069 10.8732 7.75069 10.8732 8.04819 11.0423L10.8949 12.6232C11.8165 13.1365 12.569 12.6932 12.569 11.6373V3.41817C12.5632 2.1815 11.554 1.1665 10.3115 1.1665Z"
                                        stroke="#012047" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.3115 1.1665H4.68819C3.44569 1.1665 2.43652 2.1815 2.43652 3.41817V11.6373C2.43652 12.6873 3.18902 13.1307 4.11069 12.6232L6.95736 11.0423C7.26069 10.8732 7.75069 10.8732 8.04819 11.0423L10.8949 12.6232C11.8165 13.1365 12.569 12.6932 12.569 11.6373V3.41817C12.5632 2.1815 11.554 1.1665 10.3115 1.1665Z"
                                        stroke="#012047" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span>20 Years of Experience</span>
                            </span>
                        </div>
                        <div class="schedules-consultation-fee">Consultation Fee <div>3000/PKR</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Steps --}}
        <div class="container schedules-update-steps-container my-5">

            <ul class="row">
                <li class="col-md-2 col-4">
                    <a href="/doctors/schedules/update?step=1" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 1 ? 'step-active' : '' }}">
                            <span>
                                1
                            </span>
                            Speciality
                        </div>
                    </a>
                </li>
                <li class="col-md-1 col-4 mx-3">
                    <a href="/doctors/schedules/update?step=2" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 2 ? 'step-active' : '' }}">
                            <span>
                                2
                            </span>
                            Appointment Type
                        </div>
                    </a>
                </li>
                <li class="col-md-1 col-4 mx-3">
                    <a href="/doctors/schedules/update?step=3" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 3 ? 'step-active' : '' }}">

                            <span>
                                3
                            </span>
                            Date & Time
                        </div>
                    </a>
                </li>
                <li class="col-md-1 col-4 mx-3">
                    <a href="/doctors/schedules/update?step=4" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 4 ? 'step-active' : '' }}">

                            <span>
                                4
                            </span>
                            Basic Information
                        </div>
                    </a>
                </li>
                <li class="col-md-1 col-4 mx-3">
                    <a href="/doctors/schedules/update?step=5" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 5 ? 'step-active' : '' }}">

                            <span>
                                5
                            </span>
                            Payment
                        </div>
                    </a>
                </li>
                <li class="col-md-1 col-4 mx-3">
                    <a href="/doctors/schedules/update?step=6" style="text-decoration: none;">
                        <div class="schedules-update-steps {{ $step == 6 ? 'step-active' : '' }}">

                            <span>
                                6
                            </span>
                            Confirmation
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        @switch($step)
            @case(1)
                <div class="card p-4 mt-5 custom-card-container">
                    <h5 class="card-title">Select Speciality</h5>
                    <div class="mb-4">
                        <select id="specialitySelect" class="form-select" aria-label="Select Speciality">
                            @foreach ($specialities as $index => $speciality)
                                <option value="{{ $speciality->id }}" {{ $index === 0 ? 'selected' : '' }}>
                                    {{ $speciality->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="card-title mt-4">Services</h5>
                    <div id="servicesContainer" class="row g-3">
                        {{-- Services will load dynamically --}}
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const specialitySelect = document.getElementById("specialitySelect");
                        const servicesContainer = document.getElementById("servicesContainer");

                        // Laravel gives us the full data in JSON format
                        const servicesData = @json($specialities);

                        // Render services for a given speciality
                        function renderServices(specialityId) {
                            const speciality = servicesData.find(s => s.id == specialityId);
                            servicesContainer.innerHTML = "";

                            if (speciality && speciality.services.length > 0) {
                                speciality.services.forEach(service => {
                                    const serviceCard = document.createElement("div");
                                    serviceCard.className = "col-12 col-md-4";
                                    serviceCard.innerHTML = `
                        <div class="service-card">
                            <div class="service-details">
                                <span class="service-name">${service.name}</span>
                                <span class="service-price">${service.price} PKR</span>
                            </div>
                            <div class="checkmark-icon d-none">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    `;

                                    // Bind click event to toggle selection
                                    serviceCard.querySelector(".service-card").addEventListener("click", function() {
                                        this.classList.toggle("active");
                                        const checkmark = this.querySelector(".checkmark-icon");
                                        checkmark.classList.toggle("d-none");
                                    });

                                    servicesContainer.appendChild(serviceCard);
                                });
                            } else {
                                servicesContainer.innerHTML =
                                    `<p class="text-muted">No services available for this speciality.</p>`;
                            }
                        }

                        // Load services when speciality changes
                        specialitySelect.addEventListener("change", function() {
                            renderServices(this.value);
                        });

                        // Default: render first speciality’s services
                        if (servicesData.length > 0) {
                            renderServices(servicesData[0].id);
                        }
                    });
                </script>
            @break

            @case(2)
                <div class="card p-5">
                    <h6 class="mb-3">
                        <strong>
                            Select Appointment Type
                        </strong>
                    </h6>
                    <div class="d-flex gap-3 justify-content-evenly">

                        <div class="service-card" data-type="clinic">
                            <div class="service-details" style="text-align: center; font-size: larger;">
                                <i class="bi bi-hospital"></i>
                                <span>Clinic</span>
                            </div>
                        </div>

                        <div class="service-card" data-type="video">
                            <div class="service-details" style="text-align: center; font-size: larger;">
                                <i class="bi bi-camera-video"></i>
                                <span>Video Call</span>
                            </div>
                        </div>

                        <div class="service-card" data-type="audio">
                            <div class="service-details" style="text-align: center; font-size: larger;">
                                <i class="bi bi-telephone"></i>
                                <span>Audio Call</span>
                            </div>
                        </div>

                        <div class="service-card" data-type="chat">
                            <div class="service-details" style="text-align: center; font-size: larger;">
                                <i class="bi bi-chat-dots"></i>
                                <span>Chat</span>
                            </div>
                        </div>

                        <div class="service-card" data-type="home">
                            <div class="service-details" style="text-align: center; font-size: larger;">
                                <i class="bi bi-house-door"></i>
                                <span>Home Visit</span>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    const serviceCards = document.querySelectorAll(".service-card");

                    serviceCards.forEach(card => {
                        card.addEventListener("click", () => {
                            // remove active class from all
                            serviceCards.forEach(c => c.classList.remove("active"));

                            // add active to clicked one
                            card.classList.add("active");

                        });
                    });
                </script>
            @break

            @case (3)
                <div class="card p-4 custom-form-card">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="appointmentDate" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="appointmentDate">
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label">Select Time Slot</label>
                            <div class="row g-2 mt-2 time-slots-container">
                                <div class="col-auto">
                                    <div class="time-slot-card active">
                                        9:00 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        9:30 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        10:00 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        10:30 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        11:00 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        11:30 AM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        12:00 PM
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="time-slot-card">
                                        12:30 PM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @break

            @case(4)
                <div class="card p-4 custom-form-card">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName">
                        </div>
                        <div class="col-md-4">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName">
                        </div>
                        <div class="col-md-4">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber">
                        </div>
                        <div class="col-md-4">
                            <label for="emailAddress" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="emailAddress">
                        </div>
                        <div class="col-md-4">
                            <label for="speciality" class="form-label">Speciality</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <select class="form-select border-start-0" id="speciality">
                                    <option selected>Physiologist</option>
                                    <option value="1">Cardiologist</option>
                                    <option value="2">Dermatologist</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="symptoms" class="form-label">Symptoms</label>
                            <input type="text" class="form-control" id="symptoms">
                        </div>
                        <div class="col-md-6 mt-4" style="width:100%">
                            <label for="attachment" class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="custom-input-group form-control" id="attachment">
                            {{-- <a href="#" class="d-block text-decoration-none upload-link">Upload File</a> --}}
                        </div>
                        <div class="col-12 mt-4">
                            <label for="reasonForVisit" class="form-label">Reason for Visit</label>
                            <textarea class="form-control" id="reasonForVisit" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            @break

            @case(5)
                <div class="container my-5">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card p-4 custom-card-container">
                                <h5 class="card-title">Payment Gateway</h5>
                                <div class="d-flex gap-3 payment-options my-3">
                                    <button type="button" class="btn btn-outline-primary active">
                                        <i class="bi bi-credit-card-fill me-2"></i> Credit Card
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary">
                                        <i class="bi bi-paypal me-2"></i> Paypal
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary">
                                        <i class="bi bi-stripe me-2"></i> Stripe
                                    </button>
                                </div>

                                <form class="row g-3">
                                    <div class="col-12">
                                        <label for="cardHolderName" class="form-label">Card Holder Name</label>
                                        <div class="custom-input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="cardHolderName">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="cardNumber" class="form-label">Card Number</label>
                                        <div class="custom-input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                            <input type="text" class="form-control" id="cardNumber">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiryDate" class="form-label">Expire Date</label>
                                        <div class="custom-input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <div class="custom-input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="text" class="form-control" id="cvv">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card p-4 custom-card-container booking-info-card">
                                <h5 class="card-title">Booking Info</h5>
                                <div class="booking-details-item">
                                    <strong>Date & Time</strong>
                                    <p class="mb-0">10:00 - 11:00 AM, 15, Oct 2025</p>
                                </div>
                                <div class="booking-details-item mt-3">
                                    <strong>Appointment with</strong>
                                    <p class="mb-0">Dr. Kamran - Regular Check-up - Physiology - Via Video Call</p>
                                </div>

                                <hr class="my-4">

                                <h5 class="card-title">Payment Info</h5>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Regular Check-up</span>
                                    <span>3000 PKR</span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Prescription</span>
                                    <span>500 PKR</span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Tax</span>
                                    <span>7%</span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Discount</span>
                                    <span class="text-danger">-100 PKR</span>
                                </div>

                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary btn-lg w-100">
                                        Total <span class="ms-2">3645 PKR</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @break

            @case(6)
                <div class="container my-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-6">
                            <div class="card p-4 custom-card-container">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="booking-check-icon me-2">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                    <h5 class="card-title fw-bold m-0">Booking Confirmed</h5>
                                </div>

                                <p class="mb-4">
                                    Your <span class="badge rounded-pill bg-light text-primary">Request</span> for <span
                                        class="badge rounded-pill bg-light text-primary">Regular Check-up</span> for <span
                                        class="badge rounded-pill bg-light text-primary">Physiology</span> with
                                    <span class="">
                                        <img src="{{ asset('/img/doctors/1.png') }}" width="25" height="25"
                                            alt="Dr. Kamran" class="rounded-circle me-1">
                                        Dr Kamran
                                    </span> on <span class="badge rounded-pill bg-light text-primary">Monday, April 1st,
                                        2024</span> at <span class="badge rounded-pill bg-light text-primary">10:00 AM</span> has
                                    been sent.
                                </p>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <h6 class="fw-bold mb-1">Need Our Assistance</h6>
                                        <small class="text-secondary">Call us in case you face any issue on Booking /
                                            Cancellation</small>
                                    </div>
                                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2">
                                        <i class="bi bi-telephone-fill"></i>
                                        Call Us
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div
                                class="card p-4 custom-card-container text-center h-100 d-flex flex-column align-items-center justify-content-center">
                                <h5 class="card-title fw-bold">Booking Number</h5>
                                <p class="booking-number-badge mb-3">DCRA12565</p>
                                <div class="qr-code-container mb-3">
                                    <img src="{{ asset('/img/qr-codes/example-qr.png') }}" alt="QR Code" class="img-fluid">
                                </div>
                                <small class="text-secondary mt-2">Scan this QR Code to Join the Call on appointed time</small>
                            </div>
                        </div>
                    </div>
                </div>
            @break

            @default
                <script>
                    window.location.href = '/doctors/schedules/update?step=1'
                </script>
            @break

        @endswitch
    </div>
@endsection
