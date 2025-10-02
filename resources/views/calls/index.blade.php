@extends ("layouts/app")
@section ("title", "Call logs")

@section ("main")

    @php
        $is_doctor = (auth()->user()->type == "doctor");
        $is_patient = (auth()->user()->type == "patient");
    @endphp

    <section class="bg-white py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Call logs</h2>

            <div class="overflow-x-auto">
              <table class="min-w-full border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="px-4 py-2 border border-gray-300 text-left">ID</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">With</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Message</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Status</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Duration</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Date & time</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($calls as $call)
                      <tr>
                        <td class="px-4 py-2 border border-gray-300">
                            <a href="{{ url('/calls/' . ($call->call_id ?? '') . '/detail') }}"
                                class="text-blue-600 hover:text-blue-800">{{ $call->call_id ?? "" }}</a>
                        </td>
                        
                        <td class="px-4 py-2 border border-gray-300">
                            @if ($is_doctor)
                                <a href="{{ url('/patients/' . $call->patient_id) }}"
                                    class="text-blue-600 hover:text-blue-800">
                                    <img src="{{ $call->u_profile_image }}" alt="{{ $call->u_name }}"
                                         class="w-24 h-24 rounded-full mb-2 object-cover shadow-inner"
                                         onerror="this.remove();" />

                                    {{ $call->u_name }} <br />
                                    {{ $call->u_email }}
                                </a>
                            @elseif ($is_patient)
                                <a href="{{ url('/doctors/' . $call->doctor_id) }}"
                                    class="text-blue-600 hover:text-blue-800">
                                    <img src="{{ $call->u_profile_image }}" alt="{{ $call->u_name }}"
                                         class="w-24 h-24 rounded-full mb-2 object-cover shadow-inner"
                                         onerror="this.remove();" />

                                    {{ $call->u_name }} <br />
                                    {{ $call->u_email }}
                                </a>
                            @endif
                        </td>
                        
                        <td class="px-4 py-2 border border-gray-300">{!! $call->message ?? "" !!}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ ucfirst($call->status ?? "") }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ get_duration($call->start ?? "", $call->end ?? "") }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ date("d F, Y h:i:s a", strtotime(($call->created_at ?? "") . " UTC")) }}</td>
                      </tr>
                    @endforeach
                </tbody>
              </table>

              <div class="mt-5">
                {!! $pagination !!}
              </div>
            </div>

        </div>
    </section>

@endsection