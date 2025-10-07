@extends ("layouts/app")
@section('title', 'Profile Settings')

@section('main')

    <style>
        .profile-container {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            padding: 2rem 0;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            pointer-events: none;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .profile-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control:disabled {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        .btn-update {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-update:active {
            transform: translateY(0);
        }

        .service-item {
            /* background: #f8fafc; */
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .service-item:hover {
            border-color: #9C27B0;

        }

        .service-item:has(input:checked) {
            border-color: #9C27B0;
            background-color: #F3E5F5;
            box-shadow: 0 0 10px rgba(111, 66, 193, 0.3);
        }


        /* .service-item:hover {
                                                                                                                                                border-color: #cbd5e0;
                                                                                                                                                background: white;
                                                                                                                                            } */

        .service-checkbox {
            margin-right: 0.75rem;
            transform: scale(1.2);
        }

        .service-price {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.5rem;
            margin-left: 0.75rem;
            width: 100px;
            font-size: 0.9rem;
        }

        .schedule-container {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .day-schedule {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #e2e8f0;
            /* display: grid; */
            /* grid-template-columns: 80px 1fr 1fr 120px; */
            gap: 1rem;
            align-items: center;
            transition: all 0.3s ease;
        }

        .day-schedule:hover {
            border-color: #cbd5e0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .day-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }

        .time-input,
        .schedule-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 0.9rem;
            background: white;
        }

        .time-input:focus,
        .schedule-select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 1rem;
            }

            .profile-header {
                padding: 2rem 1rem;
            }

            .profile-title {
                font-size: 2rem;
            }

            .section-card {
                padding: 1.5rem;
            }

            .day-schedule {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>

    <div class="profile-container">
        <div class="container">
            <div class="profile-card">
                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="{{ get_absolute_path(auth()->user()->profile_image) }}" class="profile-avatar"
                        onerror="this.remove();" alt="Profile Image" />
                    <h1 class="profile-title">Profile Settings</h1>
                </div>

                <form onsubmit="updateProfile(event)" class="p-4">
                    <div class="row">
                        <!-- Personal Information Column -->
                        <div class="col-md-12 mb-4">
                            <div class="section-card">
                                <h3 class="section-title">Personal Information</h3>

                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" required value="{{ auth()->user()->name ?? '' }}"
                                        class="form-control" placeholder="Enter your full name" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" disabled value="{{ auth()->user()->email ?? '' }}"
                                        class="form-control" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" accept="image/*" class="form-control" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" value="{{ $doctor?->location ?? '' }}"
                                        class="form-control" placeholder="Enter your location" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Consultation Fee ({{ config('config.currency') }})</label>
                                        <input type="number" min="0" name="fee"
                                            value="{{ $doctor?->fee ?? '' }}" class="form-control" placeholder="0.00" />
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="form-label">Discount Amount ({{ config('config.currency') }})</label>
                                        <input type="number" min="0" name="discount"
                                            value="{{ $doctor?->discount ?? '' }}" class="form-control"
                                            placeholder="0.00" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Schedule Column -->
                        <div class="col-md-12 mb-4">
                            <div class="section-card">
                                <h3 class="section-title">Weekly Schedule</h3>
                                <div id="DoctorAvailability"></div>
                            </div>
                        </div>

                        <!-- Speciality & Services Column -->
                        <div class="col-md-12 mb-4">
                            <div class="section-card">
                                <h3 class="section-title">Speciality & Services</h3>

                                <div class="form-group">
                                    <label class="form-label">Medical Speciality</label>
                                    <select name="speciality" class="form-control">
                                        <option value="">Select your speciality</option>
                                        @foreach ($specialities as $speciality)
                                            <option value="{{ $speciality->name ?? '' }}"
                                                {{ in_array($speciality->name, json_decode($doctor?->specialities ?? '[]', true) ?? []) ? 'selected' : '' }}>
                                                {{ $speciality->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="form-label">Available Services</label>
                                    <div class="services-list row">
                                        @foreach ($services as $service)
                                            @php
                                                $exists = false;
                                                $price = 0;
                                                $doctor_services = json_decode($doctor?->services ?? '[]', true);

                                                foreach ($doctor_services as $doctor_service) {
                                                    if (
                                                        isset($doctor_service['name']) &&
                                                        $doctor_service['name'] == $service->name
                                                    ) {
                                                        $exists = true;
                                                        $price = $doctor_service['price'];
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <div class="p-1 col-lg-4">
                                                <div class="service-item">
                                                    <label class="d-flex align-items-center">
                                                        <input type="checkbox" name="services[]"
                                                            value="{{ $service->name ?? '' }}" class="service-checkbox"
                                                            {{ $exists ? 'checked' : '' }} />
                                                        <span class="flex-grow-1">{{ $service->name ?? '' }}</span>
                                                        <input type="number" min="0"
                                                            name="service_price[{{ $service->name ?? '' }}]"
                                                            placeholder="Price" class="service-price"
                                                            value="{{ $price > 0 ? $price : '' }}" />
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-lg-6 offset-lg-3">
                            <button type="submit" name="submit" class="btn-update">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>

                <input type="hidden" id="hidden-availability" value="{{ $doctor?->availability ?? '[]' }}" />

                <script>
                    let availabilityArr = JSON.parse(document.getElementById("hidden-availability").value || "{}");

                    if (availabilityArr.length <= 0) {
                        availabilityArr = [{
                                day: 'Monday',
                                from: '09:00',
                                to: '17:00',
                                difference: 30
                            },
                            {
                                day: 'Tuesday',
                                from: '10:00',
                                to: '18:00',
                                difference: 45
                            },
                            {
                                day: 'Wednesday',
                                from: '08:00',
                                to: '16:00',
                                difference: 60
                            },
                            {
                                day: 'Thursday',
                                from: '09:00',
                                to: '17:00',
                                difference: 15
                            },
                            {
                                day: 'Friday',
                                from: '10:00',
                                to: '15:00',
                                difference: 30
                            },
                            {
                                day: 'Saturday',
                                from: '08:00',
                                to: '12:00',
                                difference: 45
                            },
                            {
                                day: 'Sunday',
                                from: '09:00',
                                to: '13:00',
                                difference: 60
                            },
                        ];
                    }

                    function onChange(availability) {
                        availabilityArr = availability;
                    }
                </script>

                <script type="text/babel">
            const weekdays = [
              { name: 'Monday', short: 'Mon' },
              { name: 'Tuesday', short: 'Tue' },
              { name: 'Wednesday', short: 'Wed' },
              { name: 'Thursday', short: 'Thu' },
              { name: 'Friday', short: 'Fri' },
              { name: 'Saturday', short: 'Sat' },
              { name: 'Sunday', short: 'Sun' },
            ];

            function DoctorAvailability({ onChange }) {
                const [schedule, setSchedule] = React.useState(availabilityArr);

                const handleChange = (day, field, value) => {
                  const updatedSchedule = schedule.map((item) =>
                    item.day === day ? { ...item, [field]: value } : item
                  );

                  setSchedule(updatedSchedule);

                  if (onChange != null) {
                    onChange(updatedSchedule);
                  }
                };

                React.useEffect(function () {
                  if (onChange != null) {
                    onChange(schedule);
                  }
                });

                return (
                  <div className="schedule-container">
                    {weekdays.map((weekday) => {
                      const daySchedule = schedule.find((s) => s.day === weekday.name);
                      return ( 
                        <div key={weekday.name} className="day-schedule">
                          <div className="day-name">{weekday.short}</div>
                          <div>
                            <input
                              type="time"
                              step="3600"
                              value={daySchedule?.from || ''}
                              onChange={(e) => handleChange(weekday.name, 'from', e.target.value)}
                              className="time-input"
                              placeholder="From"
                            />
                          </div>
                          <div>
                            <input
                              type="time"
                              step="3600"
                              value={daySchedule?.to || ''}
                              onChange={(e) => handleChange(weekday.name, 'to', e.target.value)}
                              className="time-input"
                              placeholder="To"
                            />
                          </div>
                          <div>
                            <select
                            name={'select-time'}
                              value={daySchedule?.difference}
                              onChange={(e) =>
                                handleChange(weekday.name, 'difference', parseInt(e.target.value))
                              }
                              className="schedule-select">
                              {[15, 30, 45, 60].map((val) => (
                                <option key={val} value={val}>
                                  {val}min
                                </option>
                              ))}
                            </select>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                );
            }
        </script>

                <script type="text/babel">
            ReactDOM.createRoot(
                document.getElementById("DoctorAvailability")
            ).render(<DoctorAvailability onChange={ onChange } />);
        </script>
            </div>
        </div>
    </div>

    <script>
        async function updateProfile(event) {
            event.preventDefault()
            const form = event.target

            try {
                const formData = new FormData(form)
                formData.append("availability", JSON.stringify(availabilityArr));
                form.submit.setAttribute("disabled", "disabled")

                const response = await axios.post(
                    baseUrl + "/profile-settings",
                    formData
                )

                if (response.data.status == "success") {
                    // window.location.reload();
                    swal.fire("Update profile", response.data.message, "success");
                } else {
                    swal.fire("Error", response.data.message, "error")
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            } finally {
                form.submit.removeAttribute("disabled")
            }
        }
    </script>

@endsection


{{--
@extends ("layouts/app")
@section ("title", "Profile Settings")

@section ("main")

    <div class="container mt-5 mb-5">
        <div class="row mt-5 mb-5">
          <div class="col-md-12 text-center">

            <img src="{{ get_absolute_path(auth()->user()->profile_image) }}"
                style="width: 200px;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 50%;"
                onerror="this.remove();" />

            <h2 class="mt-5">Profile settings</h2>
          </div>
        </div>

        <form onsubmit="updateProfile(event)" class="space-y-4">
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" required
                        value="{{ auth()->user()->name ?? '' }}" 
                        class="form-control" />
                </div>

                <div class="form-group mt-3 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" disabled
                        value="{{ auth()->user()->email ?? '' }}" 
                        class="form-control" />
                </div>

                <div class="form-group">
                    <label class="form-label">Profile image</label>
                    <input type="file" name="profile_image"
                        accept="image/*" 
                        class="form-control" />
                </div>

                <div class="form-group mt-3 mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location"
                        value="{{ $doctor?->location ?? '' }}" 
                        class="form-control" />
                </div>

                <div class="form-group mt-3 mb-3">
                    <label class="form-label">Consultation Fee ({{ config("config.currency") }})</label>
                    <input type="number" min="0" name="fee"
                        value="{{ $doctor?->fee ?? '' }}" 
                        class="form-control" />
                </div>

                <div class="form-group mt-3 mb-3">
                    <label class="form-label">Discount on Total ({{ config("config.currency") }})</label>
                    <input type="number" min="0" name="discount"
                        value="{{ $doctor?->discount ?? '' }}" 
                        class="form-control" />
                </div>
            </div>

            <div class="col-md-4">
              <div id="DoctorAvailability"></div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <h2>Speciality</h2>

                <select name="speciality" class="form-control">
                  <option value="">Select speciality</option>
                  @foreach ($specialities as $speciality)
                    <option value="{{ $speciality->name ?? '' }}"
                      {{ in_array($speciality->name, json_decode($doctor?->specialities ?? "[]", true) ?? []) ? "selected" : "" }}>{{ $speciality->name ?? '' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mt-5">
                <h2 class="mb-3">Services</h2>

                @foreach ($services as $service)
                  <p>
                    <label>
                      {{ $service->name ?? "" }}&nbsp;

                      @php
                        $exists = false;
                        $price = 0;
                        $doctor_services = json_decode($doctor?->services ?? "[]", true);

                        foreach ($doctor_services as $doctor_service)
                        {
                          if (isset($doctor_service['name']) && $doctor_service['name'] == $service->name)
                          {
                            $exists = true;
                            $price = $doctor_service['price'];
                            break;
                          }
                        }
                      @endphp

                      <input type="checkbox" name="services[]"
                        value="{{ $service->name ?? '' }}"
                        {{ $exists ? "checked" : "" }} />

                      <input type="number" min="0" name="service_price[{{ $service->name ?? '' }}]"
                        placeholder="Price"
                        class="ms-3"
                        value="{{ $price > 0 ? $price : '' }}" />
                    </label>
                  </p>
                @endforeach
              </div>
            </div>
          </div>

          <div class="row">
            <div class="offset-md-4 col-md-4">
              <div class="mt-3">
                <button type="submit" name="submit" 
                  class="btn btn-primary bg-primary-gradient no-border"
                  style="width: 100%;">
                  Update profile
                </button>
              </div>
            </div>
          </div>
        </form>

        <input type="hidden" id="hidden-availability" value="{{ $doctor?->availability ?? '[]' }}" />

        <script>
            let availabilityArr = JSON.parse(document.getElementById("hidden-availability").value || "{}");

            if (availabilityArr.length <= 0) {
              availabilityArr = [
                { day: 'Monday', from: '09:00', to: '17:00', difference: 30 },
                { day: 'Tuesday', from: '10:00', to: '18:00', difference: 45 },
                { day: 'Wednesday', from: '08:00', to: '16:00', difference: 60 },
                { day: 'Thursday', from: '09:00', to: '17:00', difference: 15 },
                { day: 'Friday', from: '10:00', to: '15:00', difference: 30 },
                { day: 'Saturday', from: '08:00', to: '12:00', difference: 45 },
                { day: 'Sunday', from: '09:00', to: '13:00', difference: 60 },
              ];
            }

            function onChange(availability) {
                availabilityArr = availability;
            }
        </script>

        <script type="text/babel">
            const weekdays = [
              { name: 'Monday', short: 'Mon' },
              { name: 'Tuesday', short: 'Tue' },
              { name: 'Wednesday', short: 'Wed' },
              { name: 'Thursday', short: 'Thu' },
              { name: 'Friday', short: 'Fri' },
              { name: 'Saturday', short: 'Sat' },
              { name: 'Sunday', short: 'Sun' },
            ];

            function DoctorAvailability({ onChange }) {
                const [schedule, setSchedule] = React.useState(availabilityArr);

                const handleChange = (day, field, value) => {
                  const updatedSchedule = schedule.map((item) =>
                    item.day === day ? { ...item, [field]: value } : item
                  );

                  setSchedule(updatedSchedule);

                  if (onChange != null) {
                    onChange(updatedSchedule);
                  }
                };

                React.useEffect(function () {
                  if (onChange != null) {
                    onChange(schedule);
                  }
                });

                return (
                  <div>
                    <h2>Weekly Schedule</h2>
                    {weekdays.map((weekday) => {
                      const daySchedule = schedule.find((s) => s.day === weekday.name);
                      return (
                        <div key={weekday.name} className="day-schedule">
                          <div>{weekday.short}</div>
                          <div>
                            <label>From: </label>
                            <input
                              type="time"
                              step="3600"
                              value={daySchedule?.from || ''}
                              onChange={(e) => handleChange(weekday.name, 'from', e.target.value)}
                            />
                          </div>
                          <div>
                            <label>To: </label>
                            <input
                              type="time"
                              step="3600"
                              value={daySchedule?.to || ''}
                              onChange={(e) => handleChange(weekday.name, 'to', e.target.value)}
                            />
                          </div>
                          <div>
                            <label>Difference: </label>
                            <select
                              value={daySchedule?.difference}
                              onChange={(e) =>
                                handleChange(weekday.name, 'difference', parseInt(e.target.value))
                              }>
                              {[15, 30, 45, 60].map((val) => (
                                <option key={val} value={val}>
                                  {val} mins
                                </option>
                              ))}
                            </select>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                );
            }
        </script>

        <script type="text/babel">
            ReactDOM.createRoot(
                document.getElementById("DoctorAvailability")
            ).render(<DoctorAvailability onChange={ onChange } />);
        </script>
    </div>

    <!-- <section class="bg-white py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Profile settings</h2>

            <form onsubmit="updateProfile(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" required
                        value="{{ auth()->user()->name ?? '' }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
                </div>

                <div class="mt-5 mb-5">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" disabled
                        value="{{ auth()->user()->email ?? '' }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2 disabled:bg-gray-200 disabled:cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Profile image</label>
                    <input type="file" name="profile_image"
                        accept="image/*" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
                </div>

                <div class="mt-5">
                    <button type="submit" name="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Update profile
                    </button>
                </div>
            </form>
        </div>
    </section> -->

    <script>
      async function updateProfile(event) {
          event.preventDefault()
          const form = event.target

          try {
              const formData = new FormData(form)
              formData.append("availability", JSON.stringify(availabilityArr));
              form.submit.setAttribute("disabled", "disabled")

              const response = await axios.post(
                  baseUrl + "/profile-settings",
                  formData
              )

              if (response.data.status == "success") {
                  // window.location.reload();
                swal.fire("Update profile", response.data.message, "success");
              } else {
                  swal.fire("Error", response.data.message, "error")
              }
          } catch (exp) {
              swal.fire("Error", exp.message, "error")
          } finally {
              form.submit.removeAttribute("disabled")
          }
      }
  </script>

@endsection
--}}