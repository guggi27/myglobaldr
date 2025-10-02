@extends ("layouts/app")
@section ("title", "Group call logs")

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
                    <th class="px-4 py-2 border border-gray-300 text-left">Status</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Start at</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">End at</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Duration</th>
                    <th class="px-4 py-2 border border-gray-300 text-left">Date & time</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($calls as $call)
                      <tr>
                        <td class="px-4 py-2 border border-gray-300">
                            <a href="{{ route('group-call.detail', [ 'id' => $call->call_id ?? '' ]) }}"
                                class="text-blue-600 hover:text-blue-800">{{ $call->call_id ?? "" }}</a>
                        </td>
                        
                        <td class="px-4 py-2 border border-gray-300">
                            <img src="{{ $call->p_profile_image }}" alt="{{ $call->p_name }}"
                                 class="w-24 h-24 rounded-full mb-2 object-cover shadow-inner"
                                 onerror="this.remove();" />

                            {{ $call->p_name }} <br />
                            {{ $call->p_email }}
                        </td>
                        
                        <td class="px-4 py-2 border border-gray-300">{{ ucfirst($call->status ?? "") }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $call->start ?? "" }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $call->end ?? "" }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ get_duration($call->start ?? "", $call->end ?? "") }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $call->created_at ?? "" }}</td>
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