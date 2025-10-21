@extends ("layouts/app")
@section('title', 'Appointments')

@section('main')

    <style>
        .appointments-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        .appointments-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .appointments-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Table View Styles */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow-x: auto;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .appointments-table th {
            background: #eeeeee;
            /* color: white; */
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border: none;
            font-size: 0.9rem;
        }

        .appointments-table th:first-child {
            border-radius: 12px 0 0 0;
        }

        .appointments-table th:last-child {
            border-radius: 0 12px 0 0;
        }

        .appointments-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 0.9rem;
            background: white;
        }

        .appointments-table tr:hover td {
            background: #f8fafc;
        }

        .appointments-table tr:last-child td:first-child {
            border-radius: 0 0 0 12px;
        }

        .appointments-table tr:last-child td:last-child {
            border-radius: 0 0 12px 0;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-done {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-details {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-decoration: none;
            width: 100%;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .status-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
            background: white;
            min-width: 120px;
        }

        /* Detail View Styles */
        .detail-view {
            display: none;
        }

        .appointment-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .appointment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .back-button {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 114, 128, 0.3);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .patient-info {
            flex: 1;
            min-width: 250px;
        }

        .patient-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .patient-contact {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .appointment-status {
            flex-shrink: 0;
        }

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #e2e8f0;
        }

        .detail-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-title::before {
            content: '';
            width: 3px;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .detail-content {
            color: #4b5563;
            line-height: 1.6;
        }

        .service-item {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-name {
            font-weight: 500;
            color: #374151;
        }

        .service-price {
            font-weight: 600;
            color: #059669;
        }

        .financial-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 16px;
            padding: 1.5rem;
        }

        .financial-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
        }

        .financial-row:last-child {
            border-top: 2px solid #0ea5e9;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .attachment-link {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            margin: 0.25rem 0.5rem 0.25rem 0;
            border: 1px solid #d1d5db;
            transition: all 0.3s ease;
        }

        .attachment-link:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
        }

        .call-link {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .call-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            color: white;
            text-decoration: none;
        }

        .appointment-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .slot-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
            color: #92400e;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
        }

        .payment-status {
            padding: 0.4rem 0.8rem;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .payment-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .payment-pending {
            background: #fee2e2;
            color: #991b1b;
        }

        .payment-failed {
            background: #fef3c7;
            color: #92400e;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .empty-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .appointments-container {
                padding: 1rem;
            }

            .table-container {
                padding: 1rem;
            }

            .appointments-table {
                font-size: 0.8rem;
            }

            .appointments-table th,
            .appointments-table td {
                padding: 0.5rem;
            }

            .appointment-card {
                padding: 1.5rem;
            }

            .appointment-header {
                flex-direction: column;
                align-items: stretch;
            }

            .appointment-details {
                grid-template-columns: 1fr;
            }

            .appointment-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <script>
        function getDummyAppointments() {
            return [{
                    id: 1,
                    first_name: "John",
                    last_name: "Smith",
                    email: "john.smith@email.com",
                    phone: "+1 (555) 123-4567",
                    symptoms: "Persistent headaches, dizziness, and fatigue for the past week. Symptoms worsen in the evening.",
                    reason_for_visit: "Regular checkup and consultation regarding recent headaches and sleep issues",
                    slot: {
                        day: "Monday",
                        time: "10:00 AM",
                    },
                    services: [{
                            name: "General Consultation",
                            price: 1500
                        },
                        {
                            name: "Blood Pressure Check",
                            price: 500
                        },
                        {
                            name: "ECG Test",
                            price: 800
                        },
                    ],
                    fee: 2000,
                    discount: 200,
                    total: 2600,
                    payment_status: "Paid",
                    attachments: ["medical_report_2024.pdf", "lab_results.pdf"],
                    call_unique_id: "call_abc123",
                    status: "approved",
                },
                {
                    id: 2,
                    first_name: "Sarah",
                    last_name: "Johnson",
                    email: "sarah.johnson@email.com",
                    phone: "+1 (555) 987-6543",
                    symptoms: "Chest pain and shortness of breath during physical activity",
                    reason_for_visit: "Cardiac evaluation and stress test consultation",
                    slot: {
                        day: "Tuesday",
                        time: "2:30 PM",
                    },
                    services: [{
                            name: "Cardiac Consultation",
                            price: 2500
                        },
                        {
                            name: "Stress Test",
                            price: 1200
                        },
                    ],
                    fee: 2500,
                    discount: 0,
                    total: 3700,
                    payment_status: "Pending",
                    attachments: ["previous_ecg.pdf"],
                    call_unique_id: "",
                    status: "pending",
                },
                {
                    id: 3,
                    first_name: "Michael",
                    last_name: "Brown",
                    email: "michael.brown@email.com",
                    phone: "+1 (555) 456-7890",
                    symptoms: "Lower back pain radiating to legs, numbness in toes",
                    reason_for_visit: "Follow-up appointment for chronic back pain management",
                    slot: {
                        day: "Wednesday",
                        time: "11:15 AM",
                    },
                    services: [{
                            name: "Orthopedic Consultation",
                            price: 1800
                        },
                        {
                            name: "X-Ray Review",
                            price: 600
                        },
                        {
                            name: "Physical Therapy Assessment",
                            price: 900
                        },
                    ],
                    fee: 1800,
                    discount: 300,
                    total: 3000,
                    payment_status: "Paid",
                    attachments: [
                        "xray_spine_2024.pdf",
                        "mri_report.pdf",
                        "therapy_notes.pdf",
                    ],
                    call_unique_id: "call_xyz789",
                    status: "done",
                },
                {
                    id: 4,
                    first_name: "Emily",
                    last_name: "Davis",
                    email: "emily.davis@email.com",
                    phone: "+1 (555) 234-5678",
                    symptoms: "Recurring migraines with visual disturbances",
                    reason_for_visit: "Neurological consultation for migraine management",
                    slot: {
                        day: "Thursday",
                        time: "9:00 AM",
                    },
                    services: [{
                            name: "Neurological Consultation",
                            price: 2200
                        },
                        {
                            name: "Visual Field Test",
                            price: 800
                        },
                    ],
                    fee: 2200,
                    discount: 100,
                    total: 2900,
                    payment_status: "Failed",
                    attachments: [],
                    call_unique_id: "",
                    status: "cancelled",
                },
                {
                    id: 5,
                    first_name: "David",
                    last_name: "Wilson",
                    email: "david.wilson@email.com",
                    phone: "+1 (555) 345-6789",
                    symptoms: "Joint pain and stiffness, particularly in hands and knees",
                    reason_for_visit: "Arthritis evaluation and treatment planning",
                    slot: {
                        day: "Friday",
                        time: "3:45 PM",
                    },
                    services: [{
                            name: "Rheumatology Consultation",
                            price: 1900
                        },
                        {
                            name: "Joint X-Ray",
                            price: 700
                        },
                        {
                            name: "Inflammation Markers Test",
                            price: 600
                        },
                    ],
                    fee: 1900,
                    discount: 150,
                    total: 3050,
                    payment_status: "Paid",
                    attachments: ["joint_xrays.pdf"],
                    call_unique_id: "call_def456",
                    status: "approved",
                },
            ];
        }

        // State management for appointments
        let appointments = [];
        let currentPage = 1;
        let totalPages = 1;
        let userType = localStorage.getItem('userType'); // Store user type in localStorage on login

        // Fetch appointments from API
        async function fetchAppointments(page = 1) {
            try {
                // Show loading state
                document.querySelector('.appointments-container').innerHTML = `
                    <div class="container">
                        <div class="appointments-header">
                            <h1 class="appointments-title">My Appointments</h1>
                            <p class="text-muted mb-0">Loading appointments...</p>
                        </div>
                        <div class="table-container text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                        </div>
                    </div>
                `;

                // Fetch data from API
                // const response = await fetch(`${env('API_HOST')}/appointments?page=${page}`, {
                //     method: 'GET',
                //     headers: {
                //         'Authorization': `Bearer ${localStorage.getItem('token')}`,
                //         'Content-Type': 'application/json'
                //     }
                // });

                // if (!response.ok) {
                //     throw new Error(`HTTP error! status: ${response.status}`);
                // }

                // const data = await response.json();

                // Update state
                appointments = getDummyAppointments();;
                currentPage = 1;
                totalPages = 2;

                // Render appointments
                renderAppointments();

            } catch (error) {
                console.error('Error fetching appointments:', error);

                // Show error state
                document.querySelector('.appointments-container').innerHTML = `
                    <div class="container">
                        <div class="appointments-header">
                            <h1 class="appointments-title">My Appointments</h1>
                            <p class="text-muted mb-0">Manage and track all your patient appointments</p>
                        </div>
                        <div class="empty-state">
                            <div class="empty-icon text-danger">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h3 class="text-muted">Error Loading Appointments</h3>
                            <p class="text-muted">${error.message}</p>
                            <button class="btn-details mt-3" onclick="fetchAppointments(${currentPage})">
                                <i class="fas fa-sync-alt me-2"></i>Retry
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        // Render appointments to DOM
        function renderAppointments() {
            const container = document.querySelector('.appointments-container');

            if (!appointments.length) {
                container.innerHTML = `
                    <div class="container">
                        <div class="appointments-header">
                            <h1 class="appointments-title">My Appointments</h1>
                            <p class="text-muted mb-0">Manage and track all your patient appointments</p>
                        </div>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h3 class="text-muted">No Appointments Found</h3>
                            <p class="text-muted">You don't have any appointments scheduled at the moment.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Generate table rows HTML
            const appointmentsHTML = appointments.map(appointment => `
                <tr>
                    <td>
                        <div class="fw-bold">${appointment.first_name} ${appointment.last_name}</div>
                        <small class="text-muted">${appointment.email}</small><br>
                        <small class="text-muted">${appointment.phone}</small>
                    </td>
                    <td>
                        ${appointment.slot ? `
                                            <div class="fw-bold">${appointment.slot.day}</div>
                                            <small class="text-muted">${appointment.slot.time}</small>
                                        ` : '<small class="text-muted">No slot assigned</small>'}
                    </td>
                    <td>
                        ${userType === 'doctor' ? renderStatusSelect(appointment) : renderStatusBadge(appointment)}
                    </td>
                    <td>
                        <span class="payment-status payment-${appointment.payment_status.toLowerCase()}">
                            ${appointment.payment_status}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold">${appointment.total} PKR</div>
                        ${appointment.discount > 0 ? `
                                            <small class="text-success">Discount: ${appointment.discount} PKR</small>
                                        ` : ''}
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; width: max-content; gap:3px">
                            <button class="btn-details" onclick="showDetails('${appointment.id}')">
                                <i class="fas fa-eye me-1"></i>
                                Details
                            </button>
                            ${appointment.call_unique_id ? `
                                                <a href="/calls/${appointment.call_unique_id}/detail" class="btn-details" 
                                                   style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                    <i class="fas fa-video me-2"></i>
                                                    Join Call
                                                </a>
                                            ` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');

            // Render full content
            container.innerHTML = `
                <div class="container">
                    <div class="appointments-header">
                        <h1 class="appointments-title">My Appointments</h1>
                        <p class="text-muted mb-0">Manage and track all your patient appointments</p>
                    </div>
                    <div class="table-view">
                        <div class="table-container">
                            <table class="appointments-table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Appointment</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="appointmentsTableBody">
                                    ${appointmentsHTML}
                                </tbody>
                            </table>
                            
                            <div class="pagination-wrapper mt-4" id="paginationContainer">
                                ${renderPagination()}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail view container -->
                    <div class="detail-view">
                        <!-- Details will be dynamically inserted here -->
                    </div>
                </div>
            `;
        }

        // Render pagination controls
        function renderPagination() {
            if (totalPages <= 1) return '';

            let pages = [];
            for (let i = 1; i <= totalPages; i++) {
                pages.push(`
                    <button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} mx-1"
                            onclick="fetchAppointments(${i})">
                        ${i}
                    </button>
                `);
            }

            return pages.join('');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            fetchAppointments(1);
        });

        // Update the existing onchangeStatus function
        async function onchangeStatus(event, id) {
            const value = event.currentTarget.value || "";
            const selectElement = event.currentTarget;

            try {
                selectElement.disabled = true;
                const originalHtml = selectElement.innerHTML;
                selectElement.innerHTML = '<option>Updating...</option>';

                const response = await fetch(`${env('API_HOST')}/appointments/change-status`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        status: value
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to update status');
                }

                const data = await response.json();

                if (data.status === "success") {
                    // Update local state
                    appointments = appointments.map(app =>
                        app.id === id ? {
                            ...app,
                            status: value
                        } : app
                    );

                    // Re-render appointments
                    renderAppointments();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire("Success", "Appointment status updated successfully", "success");
                    }
                } else {
                    throw new Error(data.message || 'Failed to update status');
                }
            } catch (error) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire("Error", error.message, "error");
                }
                // Restore original state
                selectElement.innerHTML = originalHtml;
            } finally {
                selectElement.disabled = false;
            }
        }

        // Helper functions
        function renderStatusSelect(appointment) {
            return `
                <select class="status-select" onchange="onchangeStatus(event, '${appointment.id}')">
                    <option value="">Select status</option>
                    <option value="pending" ${appointment.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="cancelled" ${appointment.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    <option value="approved" ${appointment.status === 'approved' ? 'selected' : ''}>Approved</option>
                    <option value="done" ${appointment.status === 'done' ? 'selected' : ''}>Done</option>
                </select>
            `;
        }

        function renderStatusBadge(appointment) {
            return `
                <span class="status-badge status-${appointment.status}">
                    ${appointment.status || 'Unknown'}
                </span>
            `;
        }

        function showDetails(appointmentId) {
            // Hide table view
            document.querySelector('.table-view').style.display = 'none';

            // Show detail view
            document.querySelector('.detail-view').style.display = 'block';

            // Hide all appointment details
            document.querySelectorAll('.appointment-detail').forEach(function(detail) {
                detail.style.display = 'none';
            });

            // Show specific appointment detail
            document.getElementById('detail-' + appointmentId).style.display = 'block';
        }

        function showTable() {
            // Hide detail view
            document.querySelector('.detail-view').style.display = 'none';

            // Show table view
            document.querySelector('.table-view').style.display = 'block';
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            fetchAppointments(1);
        });
    </script>

    <div class="appointments-container">
        <div class="container">
            <!-- Header -->
            <div class="appointments-header">
                <h1 class="appointments-title">My Appointments</h1>
                <p class="text-muted mb-0">Manage and track all your patient appointments</p>
            </div>

            <!-- Table View -->
            <div class="table-view">
                <div class="table-container">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Appointment</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTableBody">
                            <!-- Data will be inserted here by JavaScript -->
                        </tbody>
                    </table>

                    <div class="pagination-wrapper mt-4" id="paginationContainer">
                        <!-- Pagination will be inserted here -->
                    </div>
                </div>
            </div>

            <!-- Detail View Container -->
            <div class="detail-view">
                <!-- Details will be dynamically inserted here -->
            </div>
        </div>
    </div>

@endsection
