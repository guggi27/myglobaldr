@extends ("layouts/app")
@section('title', 'Appointments')

@section('main')

    @php
        $user = auth()->user();
    @endphp

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

    <div class="appointments-container">
        <div class="container">
            <!-- Header -->
            <div class="appointments-header">
                <h1 class="appointments-title">My Appointments</h1>
                <p class="text-muted mb-0">Manage and track all your patient appointments</p>
            </div>

            <!-- Table View -->
            <div class="table-view">
                @if (count($data) > 0)
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
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $d->first_name . ' ' . $d->last_name }}</div>
                                            <small class="text-muted">{{ $d->email }}</small><br>
                                            <small class="text-muted">{{ $d->phone }}</small>
                                        </td>
                                        <td>
                                            @if (!is_null($d->slot))
                                                <div class="fw-bold">{{ $d->slot['day'] }}</div>
                                                <small class="text-muted">{{ $d->slot['time'] }}</small>
                                            @else
                                                <small class="text-muted">No slot assigned</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->type == 'doctor')
                                                <select class="status-select"
                                                    onchange="onchangeStatus(event, '{{ $d->id }}');">
                                                    <option value="">Select status</option>
                                                    <option value="pending" {{ $d->status == 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="cancelled"
                                                        {{ $d->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    <option value="approved"
                                                        {{ $d->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="done" {{ $d->status == 'done' ? 'selected' : '' }}>Done
                                                    </option>
                                                </select>
                                            @else
                                                <span class="status-badge status-{{ $d->status }}">
                                                    {{ $d->status ?? 'Unknown' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="payment-status payment-{{ strtolower($d->payment_status) }}">
                                                {{ $d->payment_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $d->total }} PKR</div>
                                            @if ($d->discount > 0)
                                                <small class="text-success">Discount: {{ $d->discount }} PKR</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; width: max-content; gap:3px">
                                                <button class="btn-details" onclick="showDetails('{{ $d->id }}')">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Details
                                                </button>
                                                <a href="{{ url('/calls/' . $d->call_unique_id . '/detail') }}"
                                                    class="btn-details"
                                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                    <i class="fas fa-video me-2"></i>
                                                    Join Call
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {!! $links !!}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3 class="text-muted">No Appointments Found</h3>
                        <p class="text-muted">You don't have any appointments scheduled at the moment.</p>
                    </div>
                @endif
            </div>

            <!-- Detail View (Hidden by default) -->
            <div class="detail-view">
                @foreach ($data as $d)
                    <div class="appointment-detail" id="detail-{{ $d->id }}" style="display: none;">
                        <button class="back-button" onclick="showTable()">
                            <i class="fas fa-arrow-left"></i>
                            Back to Appointments
                        </button>

                        <div class="appointment-card">
                            <!-- Appointment Header -->
                            <div class="appointment-header">
                                <div class="patient-info">
                                    <div class="patient-name">
                                        <i class="fas fa-user-circle me-2"></i>
                                        {{ $d->first_name . ' ' . $d->last_name }}
                                    </div>
                                    <div class="patient-contact">
                                        <div><i class="fas fa-envelope me-2"></i>{{ $d->email }}</div>
                                        <div><i class="fas fa-phone me-2"></i>{{ $d->phone }}</div>
                                    </div>
                                </div>
                                <div class="appointment-status">
                                    @if ($user->type == 'doctor')
                                        <select class="status-select"
                                            onchange="onchangeStatus(event, '{{ $d->id }}');">
                                            <option value="">Select status</option>
                                            <option value="pending" {{ $d->status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="cancelled" {{ $d->status == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                            <option value="approved" {{ $d->status == 'approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="done" {{ $d->status == 'done' ? 'selected' : '' }}>Done
                                            </option>
                                        </select>
                                    @else
                                        <span class="status-badge status-{{ $d->status }}">
                                            {{ $d->status ?? 'Unknown' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="appointment-details">
                                <!-- Medical Information -->
                                <div class="detail-section">
                                    <div class="detail-title">
                                        <i class="fas fa-stethoscope me-2"></i>
                                        Medical Information
                                    </div>
                                    <div class="detail-content">
                                        @if ($d->symptoms)
                                            <div class="mb-3">
                                                <strong>Symptoms:</strong><br>
                                                {{ $d->symptoms }}
                                            </div>
                                        @endif
                                        @if ($d->reason_for_visit)
                                            <div>
                                                <strong>Reason for visit:</strong><br>
                                                {{ $d->reason_for_visit }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Appointment Slot -->
                                @if (!is_null($d->slot))
                                    <div class="detail-section">
                                        <div class="detail-title">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Appointment Slot
                                        </div>
                                        <div class="slot-info">
                                            <i class="fas fa-clock me-2"></i>
                                            {{ $d->slot['day'] . ' at ' . $d->slot['time'] }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Services -->
                                @if (count($d->services) > 0)
                                    <div class="detail-section">
                                        <div class="detail-title">
                                            <i class="fas fa-list-ul me-2"></i>
                                            Services
                                        </div>
                                        <div class="detail-content">
                                            @foreach ($d->services as $service)
                                                <div class="service-item">
                                                    <span class="service-name">{{ $service['name'] }}</span>
                                                    <span class="service-price">{{ $service['price'] }} PKR</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Financial Summary -->
                                <div class="detail-section financial-summary">
                                    <div class="detail-title">
                                        <i class="fas fa-calculator me-2"></i>
                                        Financial Summary
                                    </div>
                                    <div class="detail-content">
                                        <div class="financial-row">
                                            <span>Consultation Fee:</span>
                                            <span>{{ $d->fee }} PKR</span>
                                        </div>
                                        <div class="financial-row">
                                            <span>Discount:</span>
                                            <span>{{ $d->discount }} PKR</span>
                                        </div>
                                        <div class="financial-row">
                                            <span>Total Amount:</span>
                                            <span>{{ $d->total }} PKR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions and Additional Info -->
                            <div class="appointment-actions">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <!-- Payment Status -->
                                    <div>
                                        <small class="text-muted d-block mb-1">Payment Status</small>
                                        <span class="payment-status payment-{{ strtolower($d->payment_status) }}">
                                            {{ $d->payment_status }}
                                        </span>
                                    </div>

                                    <!-- Call Link -->
                                    @if (!empty($d->call_unique_id))
                                        <a href="{{ url('/calls/' . $d->call_unique_id . '/detail') }}" class="call-link">
                                            <i class="fas fa-video me-2"></i>
                                            Join Call
                                        </a>
                                    @endif
                                </div>

                                <!-- Attachments -->
                                @if (count($d->attachments) > 0)
                                    <div>
                                        <small class="text-muted d-block mb-2">Attachments</small>
                                        @foreach ($d->attachments as $attachment)
                                            <a href="{{ url('/appointments/attachment/' . $d->id . '/' . basename($attachment)) }}"
                                                target="_blank" class="attachment-link">
                                                <i class="fas fa-paperclip me-2"></i>
                                                {{ basename($attachment) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
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
    </script>

    @if ($user->type == 'doctor')
        <script>
            async function onchangeStatus(event, id) {
                const value = event.currentTarget.value || "";
                const selectElement = event.currentTarget;

                try {
                    // Show loading state
                    selectElement.disabled = true;
                    const originalHtml = selectElement.innerHTML;
                    selectElement.innerHTML = '<option>Updating...</option>';

                    const formData = new FormData();
                    formData.append("id", id);
                    formData.append("status", value);

                    const response = await axios.post(
                        baseUrl + "/appointments/change-status",
                        formData
                    );

                    if (response.data.status == "success") {
                        // Show success notification
                        if (typeof swal !== 'undefined') {
                            swal.fire("Success", "Appointment status updated successfully", "success");
                        }

                        // Update all status selects with the same appointment ID
                        document.querySelectorAll(`select[onchange*="${id}"]`).forEach(function(select) {
                            if (select !== selectElement) {
                                select.innerHTML = originalHtml;
                                select.value = value;
                            }
                        });

                        // Update status badges if any
                        document.querySelectorAll('.status-badge').forEach(function(badge) {
                            if (badge.closest('tr') && badge.closest('tr').querySelector(
                                    `button[onclick*="${id}"]`)) {
                                badge.className = `status-badge status-${value}`;
                                badge.textContent = value.charAt(0).toUpperCase() + value.slice(1);
                            }
                        });

                    } else {
                        if (typeof swal !== 'undefined') {
                            swal.fire("Error", response.data.message || "Failed to update status", "error");
                        }
                        // Restore original state
                        selectElement.innerHTML = originalHtml;
                    }
                } catch (exp) {
                    if (typeof swal !== 'undefined') {
                        swal.fire("Error", exp.message || "Network error occurred", "error");
                    }
                    // Restore original state
                    selectElement.innerHTML = originalHtml;
                } finally {
                    selectElement.disabled = false;
                }
            }
        </script>
    @endif

@endsection
