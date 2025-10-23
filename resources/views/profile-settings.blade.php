@extends ("layouts/app")
@section('title', 'Profile Settings')

@section('main')

    <style>
        /* === KEEP YOUR ORIGINAL CSS EXACTLY AS BEFORE === */
        /* (I've copied your CSS unchanged) */
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
                    <!-- avatar will be filled by JS -->
                    <img id="profile-avatar" src="" class="profile-avatar" onerror="this.remove();"
                        alt="Profile Image" style="display:none;" />
                    <h1 class="profile-title" id="profile-title">Profile Settings</h1>
                </div>

                <!-- NOTE: form fields will be populated by JS. Keep same names for backend compatibility -->
                <form id="profile-form" class="p-4" onsubmit="updateProfile(event)">
                    <div class="row">
                        <!-- Personal Information Column -->
                        <div class="col-md-12 mb-4">
                            <div class="section-card">
                                <h3 class="section-title">Personal Information</h3>

                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input id="input-name" type="text" name="name" required value=""
                                        class="form-control" placeholder="Enter your full name" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input id="input-email" type="email" name="email" disabled value=""
                                        class="form-control" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Profile Image</label>
                                    <input id="input-profile-image" type="file" name="profile_image" accept="image/*"
                                        class="form-control" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input id="input-location" type="text" name="location" value=""
                                        class="form-control" placeholder="Enter your location" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Consultation Fee ({{ config('config.currency') }})</label>
                                        <input id="input-fee" type="number" min="0" name="fee" value=""
                                            class="form-control" placeholder="0.00" />
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="form-label">Discount Amount ({{ config('config.currency') }})</label>
                                        <input id="input-discount" type="number" min="0" name="discount"
                                            value="" class="form-control" placeholder="0.00" />
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
                                    <select id="select-speciality" name="speciality" class="form-control">
                                        <option value="">Select your speciality</option>
                                    </select>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="form-label">Available Services</label>
                                    <div id="services-list" class="services-list row">
                                        <!-- services will be injected here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-lg-6 offset-lg-3">
                            <button id="submit-btn" type="submit" name="submit" class="btn-update">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>

                <!-- hidden availability mirror (kept for compatibility if any legacy code expects it) -->
                <input type="hidden" id="hidden-availability" value="[]" />

                <!-- React availability component source (unchanged, but will use availabilityArr from JS) -->
                <script>
                    // global availability array will be set after fetching profile
                    let availabilityArr = [];

                    function onChange(availability) {
                        availabilityArr = availability;
                        // reflect in hidden input for compatibility
                        document.getElementById('hidden-availability').value = JSON.stringify(availabilityArr || []);
                    }
                </script>

                <!-- DoctorAvailability React component (same as before, but uses availabilityArr initial value) -->
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
                const [schedule, setSchedule] = React.useState(availabilityArr || []);

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
                }, [schedule]);

                return (
                  <div className="schedule-container">
                    {weekdays.map((weekday) => {
                      const daySchedule = schedule.find((s) => s.day === weekday.name) || {};
                      return ( 
                        <div key={weekday.name} className="day-schedule">
                          <div className="day-name">{weekday.short}</div>
                          <div>
                            <input
                              type="time"
                              step="60"
                              value={daySchedule?.from || ''}
                              onChange={(e) => handleChange(weekday.name, 'from', e.target.value)}
                              className="time-input"
                              placeholder="From"
                            />
                          </div>
                          <div>
                            <input
                              type="time"
                              step="60"
                              value={daySchedule?.to || ''}
                              onChange={(e) => handleChange(weekday.name, 'to', e.target.value)}
                              className="time-input"
                              placeholder="To"
                            />
                          </div>
                          <div>
                            <select
                            name={'select-time-' + weekday.name}
                              value={daySchedule?.difference || 30}
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
        (function() {
            const API_HOST = "{{ env('API_HOST') }}"; // injected host (keep as-is)
            const baseUrl = document.getElementById("baseUrl") ? document.getElementById("baseUrl").value : "";
            const form = document.getElementById("profile-form");
            const submitBtn = document.getElementById("submit-btn");

            // DOM elements to populate
            const avatarEl = document.getElementById("profile-avatar");
            const titleEl = document.getElementById("profile-title");
            const inputName = document.getElementById("input-name");
            const inputEmail = document.getElementById("input-email");
            const inputLocation = document.getElementById("input-location");
            const inputFee = document.getElementById("input-fee");
            const inputDiscount = document.getElementById("input-discount");
            const selectSpeciality = document.getElementById("select-speciality");
            const servicesList = document.getElementById("services-list");

            // Fetch profile + required lists from API
            async function loadProfile() {
                try {
                    const resp = await axios.get(`${API_HOST}/doctors/profile`, {
                        withCredentials: true,
                    });

                    if (!resp.data || !resp.data.success) {
                        // user not logged in or error — show default guest values
                        titleEl.textContent = "Profile Settings";
                        return;
                    }

                    const data = resp.data;
                    const doctor = data.doctor || {};
                    const specialities = data.specialities || [];
                    const services = data.services || [];

                    // populate basic fields
                    inputName.value = doctor.name || "";
                    inputEmail.value = doctor.email || "";
                    inputLocation.value = doctor.location || "";
                    inputFee.value = doctor.fee ?? "";
                    inputDiscount.value = doctor.discount ?? "";

                    // avatar
                    if (doctor.profile_image) {
                        avatarEl.src = doctor.profile_image;
                        avatarEl.style.display = "";
                    } else {
                        avatarEl.style.display = "none";
                    }

                    // title: use doctor.name or username
                    titleEl.textContent = doctor.name || doctor.username || "Profile Settings";

                    // availability
                    // prefer doctor.availability (should be array); fallback to defaults if empty
                    if (Array.isArray(doctor.availability) && doctor.availability.length > 0) {
                        availabilityArr = doctor.availability;
                    } else {
                        // keep defaults if none provided
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
                    // inform React component
                    onChange(availabilityArr);

                    // populate specialities dropdown
                    // clear first
                    selectSpeciality.innerHTML = '<option value="">Select your speciality</option>';
                    specialities.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.name;
                        opt.textContent = s.name;
                        if (Array.isArray(doctor.specialities) && doctor.specialities.includes(s.name)) {
                            opt.selected = true;
                        }
                        selectSpeciality.appendChild(opt);
                    });

                    // populate services list (checkbox + price input)
                    servicesList.innerHTML = '';
                    const doctorServices = Array.isArray(doctor.services) ? doctor.services : [];

                    services.forEach((svc) => {
                        // find if doctor has this service
                        let exists = false;
                        let price = '';
                        for (const ds of doctorServices) {
                            if (ds && ds.name === svc.name) {
                                exists = true;
                                price = ds.price ?? '';
                                break;
                            }
                        }

                        const wrapper = document.createElement('div');
                        wrapper.className = 'p-1 col-lg-4';

                        wrapper.innerHTML = `
                            <div class="service-item">
                                <label class="d-flex align-items-center">
                                    <input type="checkbox" name="services[]" value="${escapeHtml(svc.name)}" class="service-checkbox" ${exists ? 'checked' : ''} />
                                    <span class="flex-grow-1">${escapeHtml(svc.name)}</span>
                                    <input type="number" min="0" name="service_price[${escapeHtml(svc.name)}]" placeholder="Price" class="service-price" value="${price !== '' ? String(price) : ''}" />
                                </label>
                            </div>
                        `;
                        servicesList.appendChild(wrapper);
                    });

                    // set hidden availability mirror
                    document.getElementById('hidden-availability').value = JSON.stringify(availabilityArr || []);

                } catch (err) {
                    console.error("Failed to load profile:", err);
                    swal.fire("Error", "Unable to load profile data. Check console for details.", "error");
                }
            }

            // minimal HTML-escape to avoid injection via service names
            function escapeHtml(str) {
                if (typeof str !== 'string') return str;
                return str.replace(/[&<>"'`=\/]/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;',
                    '`': '&#x60;',
                        '=': '&#x3D;'
                    })[s];
                });
            }

            // Form submit handler
            async function updateProfile(event) {
                event.preventDefault();
                const theForm = form;
                try {
                    submitBtn.setAttribute('disabled', 'disabled');

                    const fd = new FormData();

                    // append simple fields
                    fd.append('name', inputName.value || '');
                    fd.append('location', inputLocation.value || '');
                    fd.append('fee', inputFee.value || '');
                    fd.append('discount', inputDiscount.value || '');
                    fd.append('speciality', selectSpeciality.value || '');

                    // append availability
                    fd.append('availability', JSON.stringify(availabilityArr || []));

                    // append services selected + prices
                    // collect checked services
                    const checked = Array.from(theForm.querySelectorAll('input[name="services[]"]:checked'))
                        .map(ch => ch.value);

                    // append services as JSON array of objects { name, price }
                    const servicesArr = checked.map(name => {
                        const priceInput = theForm.querySelector(
                            `input[name="service_price[${CSS.escape(name)}]"]`);
                        const price = priceInput ? Number(priceInput.value || 0) : 0;
                        return {
                            name,
                            price
                        };
                    });

                    fd.append('services', JSON.stringify(servicesArr));

                    // append file if present
                    const fileInput = document.getElementById('input-profile-image');
                    if (fileInput && fileInput.files && fileInput.files.length > 0) {
                        fd.append('profile_image', fileInput.files[0]);
                    }

                    // send to API with credentials
                    const res = await axios.post(`${API_HOST}/doctors/profile`, fd, {
                        withCredentials: true,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    });

                    if (res.data && res.data.status === 'success') {
                        swal.fire("Update profile", res.data.message || "Profile updated successfully", "success");
                        // reload to reflect changes (avatar etc.)
                        await loadProfile();
                    } else {
                        swal.fire("Error", (res.data && res.data.message) ? res.data.message : "Update failed",
                            "error");
                    }
                } catch (err) {
                    console.error("Update error:", err);
                    swal.fire("Error", err?.response?.data?.message || "Update failed", "error");
                } finally {
                    submitBtn.removeAttribute('disabled');
                }
            }

            // initial load
            loadProfile();
        })();
    </script>

@endsection
