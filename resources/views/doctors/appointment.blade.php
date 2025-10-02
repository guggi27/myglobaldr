@extends ("layouts/app")
@section ("title", $user->name)

@section ("main")

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
                  <img class="schedules-doctor-info-img"
                    src="{{ $user->profile_image }}"
                    onerror="this.src = baseUrl + '/img/doctors/2.png'"
                    alt="" />
                  <div class="bg-dark schedules-doctor-info-img" style="width: 100%; height: 100%;"></div>
                  <div class="schedules-icons-container">

                      @if ($user->ratings > 0)
                        <div class="schedules-star-icon">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.63057 1.28424C3.92993 0.362934 5.23333 0.362934 5.53269 1.28425L5.92871 2.50309C6.06259 2.91512 6.44654 3.19408 6.87977 3.19408H8.16134C9.13007 3.19408 9.53284 4.43369 8.74913 5.00309L7.71231 5.75638C7.36183 6.01103 7.21517 6.46239 7.34904 6.87442L7.74507 8.09327C8.04442 9.01458 6.98994 9.7807 6.20623 9.2113L5.16942 8.45801C4.81893 8.20336 4.34433 8.20336 3.99385 8.45801L2.95703 9.2113C2.17332 9.7807 1.11884 9.01458 1.41819 8.09326L1.81422 6.87442C1.94809 6.46239 1.80143 6.01103 1.45095 5.75638L0.414131 5.00309C-0.369582 4.43369 0.0331938 3.19408 1.00192 3.19408H2.28349C2.71672 3.19408 3.10067 2.91512 3.23455 2.50309L3.63057 1.28424Z"
                                    fill="white" />
                            </svg>
                            {{ $user->ratings }}
                        </div>
                      @endif

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
                  <h5>{{ $user->name ?? "" }}</h5>
                  <div class="schedules-doctor-specialty">
                    @foreach ($user->specialities as $speciality)
                      {{ $speciality }} |
                    @endforeach
                  </div>
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
                              <span>{{ $user->location }}</span>
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

                              <span>98% ({{ $user->reviews }} Votes)</span>
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
                      <div class="schedules-consultation-fee">Consultation Fee <div>{{ ($user->fee ?? 0) . "/" . strtoupper(config("config.currency")) }}</div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          @if (count($user->specialities) > 0)
            <p>Speciality: {{ $user->specialities[0] ?? "" }}</p>
          @endif

          <h2>Services</h2>

          @foreach ($user->services as $service)

            @php
              $name = $service['name'];
              $price = $service['price'];
            @endphp

            <p>
              <button type="button"
                onclick="serviceSelected('{{ $name }}', {{ $price }});">{{ $name . "/" . $price . " " . config("config.currency") }}</button>
            </p>
          @endforeach
        </div>
      </div>
  </div>

  <input type="hidden" id="doctor-user-id" value="{{ $user->id }}" />

  <script>
    const doctorUserId = document.getElementById("doctor-user-id").value;
    
    function serviceSelected(name, price) {
      localStorage.setItem("selected_service", JSON.stringify({
        name: name,
        price: price
      }));
      window.location.href = baseUrl + "/doctors/" + doctorUserId + "/appointment-type";
    }
  </script>

@endsection