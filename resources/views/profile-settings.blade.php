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