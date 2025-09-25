@extends ("layouts/app")
@section ("title", "Appointments")

@section ("main")

    @php
        $user = auth()->user();
    @endphp

    <div class="container mt-5 mb-5">
        <div class="row mt-5 mb-5">
            <div class="col-md-12 text-center">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Symptoms</th>
                            <th>Reason for visit</th>
                            <th>Slot</th>
                            <th>Services</th>
                            <th>Fee (PKR)</th>
                            <th>Payment</th>
                            <th>Attachments</th>
                            <th>Call link</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>
                                    Name: {{ $d->first_name . " " . $d->last_name }} <br />
                                    Email: {{ $d->email }} <br />
                                    Phone: {{ $d->phone }} <br />
                                </td>

                                <td>{{ $d->symptoms }}</td>
                                <td>{{ $d->reason_for_visit }}</td>
                                <td>
                                    @if (!is_null($d->slot))
                                        {{ $d->slot["day"] . " " . $d->slot["time"] }}
                                    @endif
                                </td>

                                <td>
                                    @foreach ($d->services as $service)
                                        {{ $service["name"] }} ({{ $service["price"] }} PKR) <br />
                                    @endforeach
                                </td>

                                <td>
                                    Fee: {{ $d->fee }} <br />
                                    Discount: {{ $d->discount }} <br />
                                    Total: {{ $d->total }} <br />
                                </td>

                                <td>{{ $d->payment_status }}</td>

                                <td>
                                    @foreach ($d->attachments as $attachment)
                                        <a href="{{ url('/appointments/attachment/' . $d->id . '/' . basename($attachment)) }}"
                                            target="_blank">
                                            {{ basename($attachment) }}
                                        </a>
                                    @endforeach
                                </td>

                                <td>
                                    @if (!empty($d->call_unique_id))
                                        <a href="{{ url('/calls/' . $d->call_unique_id. '/detail') }}">
                                            {{ $d->call_unique_id }}
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    @if ($user->type == "doctor")
                                        <select class="form-control" onchange="onchangeStatus(event, '{{ $d->id }}');">
                                            <option value="">Select status</option>
                                            <option value="pending" {{ $d->status == "pending" ? "selected" : "" }}>Pending</option>
                                            <option value="cancelled" {{ $d->status == "cancelled" ? "selected" : "" }}>Cancelled</option>
                                            <option value="approved" {{ $d->status == "approved" ? "selected" : "" }}>Approved</option>
                                            <option value="done" {{ $d->status == "done" ? "selected" : "" }}>Done</option>
                                        </select>
                                    @else
                                        {{ $d->status ?? "" }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $links !!}
            </div>
        </div>
    </div>

    @if ($user->type == "doctor")
        <script>
            async function onchangeStatus(event, id) {
                const value = event.currentTarget.value || "";

                try {
                    const formData = new FormData();
                    formData.append("id", id);
                    formData.append("status", value);

                    const response = await axios.post(
                        baseUrl + "/appointments/change-status",
                        formData
                    );

                    if (response.data.status == "success") {
                        // 
                    } else {
                        // swal.fire("Error", response.data.message, "error")
                        ref.remove();
                    }
                } catch (exp) {
                    // swal.fire("Error", exp.message, "error")
                }
            }
        </script>
    @endif

@endsection