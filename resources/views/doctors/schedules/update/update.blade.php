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
            <div>
                @if ($step > 1)
                    <a href="/doctors/schedules/update?step={{ $step - 1 }}" class="btn no-border white bold"
                        style="background-color: darkgray">Back</a>
                @endif

                @if ($step < 6)
                    <a href="/doctors/schedules/update?step={{ $step + 1 }}"
                        class="btn bg-primary-gradient no-border white bold" style="background-color: darkgray">Next</a>
                @endif
            </div>
        </div>
        <!-- Doctor Profile Card -->
        <div class="schedules-doctor-card border-primary-gradient shadow-sm">
            <div class="schedules-doctor-info">
                <div class="schedules-doctor-info-img-container">
                    <img class="schedules-doctor-info-img" src="{{ asset('/img/doctors/2.png') }}" alt="Doctor">
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
                <div class="card p-5 mt-5 custom-card-container" id="">
                    <h5 class="card-title">Select Speciality</h5>
                    <div class="pb-4" style="border-bottom: 1px solid #E6E8EE">
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
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4915 1.6665H6.50817C3.47484 1.6665 1.6665 3.47484 1.6665 6.50817V13.4832C1.6665 16.5248 3.47484 18.3332 6.50817 18.3332H13.4832C16.5165 18.3332 18.3248 16.5248 18.3248 13.4915V6.50817C18.3332 3.47484 16.5248 1.6665 13.4915 1.6665ZM13.9832 8.08317L9.25817 12.8082C9.1415 12.9248 8.98317 12.9915 8.8165 12.9915C8.64984 12.9915 8.4915 12.9248 8.37484 12.8082L6.0165 10.4498C5.77484 10.2082 5.77484 9.80817 6.0165 9.5665C6.25817 9.32484 6.65817 9.32484 6.89984 9.5665L8.8165 11.4832L13.0998 7.19984C13.3415 6.95817 13.7415 6.95817 13.9832 7.19984C14.2248 7.4415 14.2248 7.83317 13.9832 8.08317Z" fill="#9C27B0"/>
                                </svg>
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
                <div class="card p-5 mt-5 custom-card-container">
                    <h6 class="mb-3">
                        <strong>
                            Select Appointment Type
                        </strong>
                    </h6>
                    <div class="d-flex gap-3 justify-content-evenly">

                        <div class="service-card appointment-type" data-type="clinic">
                            <div class="service-details appointment-type-detail">
                                <svg width="20" height="20" viewBox="0 0 19 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="">
                                    <path
                                        d="M14.7002 0H4.7002C1.7002 0 0.700195 1.79 0.700195 4V20H6.7002V13.94C6.7002 13.42 7.12019 13 7.64019 13H11.7702C12.2802 13 12.7102 13.42 12.7102 13.94V20H18.7102V4C18.7002 1.79 17.7002 0 14.7002 0ZM12.2002 7.25H10.4502V9C10.4502 9.41 10.1102 9.75 9.7002 9.75C9.2902 9.75 8.9502 9.41 8.9502 9V7.25H7.2002C6.7902 7.25 6.4502 6.91 6.4502 6.5C6.4502 6.09 6.7902 5.75 7.2002 5.75H8.9502V4C8.9502 3.59 9.2902 3.25 9.7002 3.25C10.1102 3.25 10.4502 3.59 10.4502 4V5.75H12.2002C12.6102 5.75 12.9502 6.09 12.9502 6.5C12.9502 6.91 12.6102 7.25 12.2002 7.25Z"
                                        fill="currentColor" />
                                </svg>

                                <span>Clinic</span>
                            </div>
                        </div>

                        <div class="service-card appointment-type" data-type="video">
                            <div class="service-details appointment-type-detail">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.2501 6.17C20.8401 5.95 19.9801 5.72 18.8101 6.54L17.3401 7.58C17.2301 4.47 15.8801 3.25 12.6001 3.25H6.6001C3.1801 3.25 1.8501 4.58 1.8501 8V16C1.8501 18.3 3.1001 20.75 6.6001 20.75H12.6001C15.8801 20.75 17.2301 19.53 17.3401 16.42L18.8101 17.46C19.4301 17.9 19.9701 18.04 20.4001 18.04C20.7701 18.04 21.0601 17.93 21.2501 17.83C21.6601 17.62 22.3501 17.05 22.3501 15.62V8.38C22.3501 6.95 21.6601 6.38 21.2501 6.17ZM11.1001 11.38C10.0701 11.38 9.2201 10.54 9.2201 9.5C9.2201 8.46 10.0701 7.62 11.1001 7.62C12.1301 7.62 12.9801 8.46 12.9801 9.5C12.9801 10.54 12.1301 11.38 11.1001 11.38Z"
                                        fill="currentColor" />
                                </svg>

                                <span>Video Call</span>
                            </div>
                        </div>

                        <div class="service-card appointment-type" data-type="audio">
                            <div class="service-details appointment-type-detail">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.55 14.95L9.7 16.8C9.31 17.19 8.69 17.19 8.29 16.81C8.18 16.7 8.07 16.6 7.96 16.49C6.93 15.45 6 14.36 5.17 13.22C4.35 12.08 3.69 10.94 3.21 9.81C2.74 8.67 2.5 7.58 2.5 6.54C2.5 5.86 2.62 5.21 2.86 4.61C3.1 4 3.48 3.44 4.01 2.94C4.65 2.31 5.35 2 6.09 2C6.37 2 6.65 2.06 6.9 2.18C7.16 2.3 7.39 2.48 7.57 2.74L9.89 6.01C10.07 6.26 10.2 6.49 10.29 6.71C10.38 6.92 10.43 7.13 10.43 7.32C10.43 7.56 10.36 7.8 10.22 8.03C10.09 8.26 9.9 8.5 9.66 8.74L8.9 9.53C8.79 9.64 8.74 9.77 8.74 9.93C8.74 10.01 8.75 10.08 8.77 10.16C8.8 10.24 8.83 10.3 8.85 10.36C9.03 10.69 9.34 11.12 9.78 11.64C10.23 12.16 10.71 12.69 11.23 13.22C11.33 13.32 11.44 13.42 11.54 13.52C11.94 13.91 11.95 14.55 11.55 14.95Z"
                                        fill="currentColor" />
                                    <path
                                        d="M22.4701 18.33C22.4701 18.61 22.4201 18.9 22.3201 19.18C22.2901 19.26 22.2601 19.34 22.2201 19.42C22.0501 19.78 21.8301 20.12 21.5401 20.44C21.0501 20.98 20.5101 21.37 19.9001 21.62C19.8901 21.62 19.8801 21.63 19.8701 21.63C19.2801 21.87 18.6401 22 17.9501 22C16.9301 22 15.8401 21.76 14.6901 21.27C13.5401 20.78 12.3901 20.12 11.2501 19.29C10.8601 19 10.4701 18.71 10.1001 18.4L13.3701 15.13C13.6501 15.34 13.9001 15.5 14.1101 15.61C14.1601 15.63 14.2201 15.66 14.2901 15.69C14.3701 15.72 14.4501 15.73 14.5401 15.73C14.7101 15.73 14.8401 15.67 14.9501 15.56L15.7101 14.81C15.9601 14.56 16.2001 14.37 16.4301 14.25C16.6601 14.11 16.8901 14.04 17.1401 14.04C17.3301 14.04 17.5301 14.08 17.7501 14.17C17.9701 14.26 18.2001 14.39 18.4501 14.56L21.7601 16.91C22.0201 17.09 22.2001 17.3 22.3101 17.55C22.4101 17.8 22.4701 18.05 22.4701 18.33Z"
                                        fill="currentColor" />
                                </svg>

                                <span>Audio Call</span>
                            </div>
                        </div>

                        <div class="service-card appointment-type" data-type="chat">
                            <div class="service-details appointment-type-detail">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.55 14.95L9.7 16.8C9.31 17.19 8.69 17.19 8.29 16.81C8.18 16.7 8.07 16.6 7.96 16.49C6.93 15.45 6 14.36 5.17 13.22C4.35 12.08 3.69 10.94 3.21 9.81C2.74 8.67 2.5 7.58 2.5 6.54C2.5 5.86 2.62 5.21 2.86 4.61C3.1 4 3.48 3.44 4.01 2.94C4.65 2.31 5.35 2 6.09 2C6.37 2 6.65 2.06 6.9 2.18C7.16 2.3 7.39 2.48 7.57 2.74L9.89 6.01C10.07 6.26 10.2 6.49 10.29 6.71C10.38 6.92 10.43 7.13 10.43 7.32C10.43 7.56 10.36 7.8 10.22 8.03C10.09 8.26 9.9 8.5 9.66 8.74L8.9 9.53C8.79 9.64 8.74 9.77 8.74 9.93C8.74 10.01 8.75 10.08 8.77 10.16C8.8 10.24 8.83 10.3 8.85 10.36C9.03 10.69 9.34 11.12 9.78 11.64C10.23 12.16 10.71 12.69 11.23 13.22C11.33 13.32 11.44 13.42 11.54 13.52C11.94 13.91 11.95 14.55 11.55 14.95Z"
                                        fill="currentColor" />
                                    <path
                                        d="M22.4701 18.33C22.4701 18.61 22.4201 18.9 22.3201 19.18C22.2901 19.26 22.2601 19.34 22.2201 19.42C22.0501 19.78 21.8301 20.12 21.5401 20.44C21.0501 20.98 20.5101 21.37 19.9001 21.62C19.8901 21.62 19.8801 21.63 19.8701 21.63C19.2801 21.87 18.6401 22 17.9501 22C16.9301 22 15.8401 21.76 14.6901 21.27C13.5401 20.78 12.3901 20.12 11.2501 19.29C10.8601 19 10.4701 18.71 10.1001 18.4L13.3701 15.13C13.6501 15.34 13.9001 15.5 14.1101 15.61C14.1601 15.63 14.2201 15.66 14.2901 15.69C14.3701 15.72 14.4501 15.73 14.5401 15.73C14.7101 15.73 14.8401 15.67 14.9501 15.56L15.7101 14.81C15.9601 14.56 16.2001 14.37 16.4301 14.25C16.6601 14.11 16.8901 14.04 17.1401 14.04C17.3301 14.04 17.5301 14.08 17.7501 14.17C17.9701 14.26 18.2001 14.39 18.4501 14.56L21.7601 16.91C22.0201 17.09 22.2001 17.3 22.3101 17.55C22.4101 17.8 22.4701 18.05 22.4701 18.33Z"
                                        fill="currentColor" />
                                </svg>
                                <span>Chat</span>
                            </div>
                        </div>

                        <div class="service-card appointment-type" data-type="home">
                            <div class="service-details appointment-type-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" fill="currentColor"
                                    class="bi bi-house-door-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
                                </svg>
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
                <div class="card p-5 mt-5 custom-card-container">
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
                <div class="card p-5 mt-5 custom-card-container">
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
                <div class="my-5">
                    <div class="row g-4 p-5">
                        <div class="col-lg-6">
                            <div class="custom-card-container p-4">
                                <h5 class ="card-title">Payment Gateway</h5>
                                <div class="payment-options my-3">
                                    <button type="button" class="payment-button" id="credit-card-btn">
                                        <img src="{{ asset('/img/payment/card.png') }}" alt="Credit Card"> Credit Card
                                    </button>
                                    <button type="button" class="payment-button" id="paypal-btn">
                                        <img src="{{ asset('/img/payment/paypal.png') }}" alt="Credit Card">Paypal
                                    </button>
                                    <button type="button" class="payment-button" id="stripe-btn">
                                        <img src="{{ asset('/img/payment/stripe.png') }}" alt="Credit Card">Stripe
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
                            <div class="card p-4 booking-info-card custom-card-container">
                                <h5 class="card-title">
                                    <strong>
                                        Booking Info
                                    </strong>
                                </h5>
                                <div class="booking-details-item">
                                    <strong>Date & Time</strong>
                                    <p class="mb-0">10:00 - 11:00 AM, 15, Oct 2025</p>
                                </div>
                                <div class="booking-details-item mt-3 p-4">
                                    <strong>Appointment with</strong>
                                    <p class="mb-0">Dr. Kamran - Regular Check-up - Physiology - Via Video Call</p>
                                </div>

                                <hr class="my-4">

                                <h5 class="card-title">
                                    <strong>
                                        Payment Info
                                </h5>
                                </strong>
                                <div class="d-flex justify-content-between my-2" style="font-weight: 500">
                                    <span>Regular Check-up</span>
                                    <span>
                                        3000 PKR
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Prescription</span>
                                    <span style="color: black">500 PKR</span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Tax</span>
                                    <span style="color: black">7%</span>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <span>Discount</span>
                                    <strong class="text-danger">-100 PKR</strong>
                                </div>

                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary btn-lg w-100 d-flex justify-content-between"
                                        style="font-weight: bold; background-color: #0E82FD; color: white;">
                                        Total
                                        <span class="ms-2">3645 PKR</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    const creditCardBtn = document.getElementById('credit-card-btn');
                    const paypalBtn = document.getElementById('paypal-btn');
                    const stripeBtn = document.getElementById('stripe-btn');
                    document.addEventListener('DOMContentLoaded', () => {

                        creditCardBtn.addEventListener('click', (e) => {
                            creditCardBtn.classList.add('active')
                            paypalBtn.classList.remove('active')
                            stripeBtn.classList.remove('active')
                            e.stopPropagation();
                        })
                        paypalBtn.addEventListener('click', (e) => {
                            paypalBtn.classList.add('active')
                            creditCardBtn.classList.remove('active')
                            stripeBtn.classList.remove('active')
                            e.stopPropagation();
                        })
                        stripeBtn.addEventListener('click', (e) => {
                            stripeBtn.classList.add('active')
                            paypalBtn.classList.remove('active')
                            creditCardBtn.classList.remove('active')
                            e.stopPropagation();
                        })
                    })
                </script>
            @break

            @case(6)
                <div class="my-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-6">
                            <div class="card p-4 custom-card-container" style="display: flex; align-items: center; gap: 20px;">
                                <div class="" style="border: 1px solid #E6E8EE; border-radius: 10px;">
                                    <div class="d-flex align-items-center mb-3 p-4" style="border-bottom: 1px solid #E6E8EE;">

                                        <span class="booking-check-icon me-2">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </span>
                                        <h5 class="card-title fw-bold m-0">Booking Confirmed</h5>

                                    </div>

                                    <p class="mb-4 px-4 py-2">
                                        Your <span class="span-light-blue special-span span-bold">Request</span> for <span
                                            class="special-span span-bold span-underline">Regular Check-up</span> for <span
                                            class="span-light-blue special-span span-bold">Physiology</span> with
                                        <span class="special-span span-bold">
                                            <img src="{{ asset('/img/doctors/3.png') }}" style="object-fit: cover"
                                                width="25" height="25" alt="Dr. Kamran" class="rounded-circle me-1">
                                            Dr Kamran
                                        </span> on <span class="special-span span-bold span-light-purple">
                                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0 14.2417C0 15.6158 1.05083 16.6667 2.425 16.6667H13.7417C15.1158 16.6667 16.1667 15.6158 16.1667 14.2417V7.775H0V14.2417ZM13.7417 2.11667H12.125V1.30833C12.125 0.823333 11.8017 0.5 11.3167 0.5C10.8317 0.5 10.5083 0.823333 10.5083 1.30833V2.11667H5.65833V1.30833C5.65833 0.823333 5.335 0.5 4.85 0.5C4.365 0.5 4.04167 0.823333 4.04167 1.30833V2.11667H2.425C1.05083 2.11667 0 3.1675 0 4.54167V6.15833H16.1667V4.54167C16.1667 3.1675 15.1158 2.11667 13.7417 2.11667Z"
                                                    fill="#4A148C" />
                                            </svg>

                                            Monday, April 1st,
                                            2024</span> at <span class="special-span span-bold span-green">
                                            <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.08333 0.5C7.89049 0.5 6.70933 0.745725 5.60729 1.22314C4.50525 1.70056 3.50391 2.40033 2.66045 3.28249C0.956991 5.06408 0 7.48044 0 10C0 12.5196 0.956991 14.9359 2.66045 16.7175C3.50391 17.5997 4.50525 18.2994 5.60729 18.7769C6.70933 19.2543 7.89049 19.5 9.08333 19.5C11.4924 19.5 13.8028 18.4991 15.5062 16.7175C17.2097 14.9359 18.1667 12.5196 18.1667 10C18.1667 8.75244 17.9317 7.5171 17.4752 6.36451C17.0188 5.21191 16.3497 4.16464 15.5062 3.28249C14.6628 2.40033 13.6614 1.70056 12.5594 1.22314C11.4573 0.745725 10.2762 0.5 9.08333 0.5ZM12.8983 13.99L8.175 10.95V5.25H9.5375V10.19L13.625 12.755L12.8983 13.99Z"
                                                    fill="#2E7D32" />
                                            </svg>

                                            10:00 AM</span>
                                        has
                                        been sent.
                                    </p>
                                </div>


                                <div class="d-flex px-4 py-2 justify-content-between align-items-center"
                                    style="border: 1px solid #E6E8EE; width: 100%;  border-radius:10px">
                                    <div class="d-flex flex-column">
                                        <h6 class="fw-bold mb-1">Need Our Assistance</h6>
                                        <small class="text-secondary">Call us in case you face any issue on Booking /
                                            Cancellation</small>
                                    </div>
                                    <button class="btn d-flex align-items-center gap-2"
                                        style="color: #012047; background-color: #E6E8EE; border-radius: 1000px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.02734 1.5835C3.10894 1.5835 3.1864 1.60085 3.25 1.63135L3.25684 1.63525C3.32332 1.66596 3.37547 1.70766 3.41797 1.76904L3.42188 1.77393L4.67773 3.54443L4.67969 3.54736C4.75898 3.65749 4.80883 3.748 4.83984 3.82373L4.84277 3.83154C4.87203 3.89982 4.87891 3.94625 4.87891 3.96533C4.87888 3.99459 4.87063 4.03542 4.83789 4.08936L4.83301 4.09619L4.8291 4.104C4.78659 4.17912 4.71459 4.2732 4.60742 4.38037L4.60059 4.38721L4.18945 4.81592C4.07275 4.93536 4.00379 5.07602 3.97656 5.2251L3.96289 5.37939L3.97168 5.50732C3.97708 5.54782 3.98465 5.58653 3.99414 5.62451L4.00098 5.65283L4.01074 5.6792C4.02315 5.71228 4.03564 5.74134 4.04199 5.75635C4.04482 5.76302 4.04684 5.76754 4.04785 5.77002L4.0625 5.81201L4.08398 5.85107C4.20511 6.07314 4.39735 6.3363 4.64453 6.62842L4.64844 6.63232C4.89726 6.91985 5.16449 7.21506 5.45508 7.51123L5.45801 7.51416C5.49007 7.54622 5.52321 7.57774 5.55078 7.604C5.58004 7.63187 5.60395 7.65522 5.62598 7.67725L5.63086 7.68115C5.65027 7.70008 5.64916 7.72721 5.63184 7.74463L4.62988 8.74658C4.6204 8.75607 4.61055 8.75923 4.60059 8.75928C4.59004 8.7593 4.57668 8.75522 4.56348 8.74268C4.53236 8.7118 4.502 8.68293 4.47559 8.65771C4.44517 8.62868 4.41857 8.60363 4.39355 8.57861C3.85419 8.03382 3.36879 7.46346 2.93555 6.86865C2.61572 6.42401 2.34541 5.98399 2.12793 5.55029L1.92773 5.11865C1.69424 4.55091 1.58301 4.02678 1.58301 3.54248C1.58303 3.23354 1.63744 2.94504 1.74219 2.68311L1.74316 2.68018C1.84458 2.42243 2.00726 2.18002 2.24414 1.95654L2.25195 1.94873C2.52296 1.68206 2.78125 1.58356 3.02734 1.5835Z"
                                                fill="#012047" stroke="#012047" />
                                            <path
                                                d="M11.9006 9.92873C11.9006 10.0804 11.8735 10.2375 11.8194 10.3891C11.8031 10.4325 11.7869 10.4758 11.7652 10.5191C11.6731 10.7141 11.5539 10.8983 11.3969 11.0716C11.1314 11.3641 10.8389 11.5754 10.5085 11.7108C10.5031 11.7108 10.4977 11.7162 10.4923 11.7162C10.1727 11.8462 9.82603 11.9166 9.45228 11.9166C8.89978 11.9166 8.30936 11.7866 7.68644 11.5212C7.06353 11.2558 6.44061 10.8983 5.82311 10.4487C5.61186 10.2916 5.40061 10.1346 5.2002 9.96665L6.97145 8.1954C7.12311 8.30915 7.25853 8.39581 7.37228 8.4554C7.39936 8.46623 7.43186 8.48248 7.46978 8.49873C7.51311 8.51498 7.55645 8.5204 7.6052 8.5204C7.69728 8.5204 7.7677 8.4879 7.82728 8.42831L8.23895 8.02206C8.37436 7.88665 8.50436 7.78373 8.62895 7.71873C8.75353 7.6429 8.87811 7.60498 9.01353 7.60498C9.11644 7.60498 9.22478 7.62665 9.34395 7.6754C9.46311 7.72415 9.5877 7.79456 9.72311 7.88665L11.516 9.15956C11.6569 9.25706 11.7544 9.37081 11.8139 9.50623C11.8681 9.64165 11.9006 9.77706 11.9006 9.92873Z"
                                                fill="#012047" />
                                        </svg>

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
