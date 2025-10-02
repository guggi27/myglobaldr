@extends ("admin/layouts/app")
@section ("title", "Calls")

@section ("main")

  <div class="pagetitle">
    <div style="display: flex;">
      <h1>Calls</h1>
    </div>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Calls</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <table class="table table-bordered table-responsive">
          <thead>
            <tr>
              <th>ID</th>
              <th>Doctor</th>
              <th>Patient</th>
              <th>Message</th>
              <th>Type</th>
              <th>Status</th>
              <th>Duration</th>
              <th>Date & time</th>
            </tr>
          </thead>

          <tbody>

            @if (count($calls) <= 0)
              <tr>
                <td colspan="8">No call found.</td>
              </tr>
            @endif

            @foreach ($calls as $call)
              <tr>
                <td>
                  <a href="{{ url('/admin/calls/' . $call->call_id . '/detail') }}">
                    {{ $call->call_id ?? "" }}
                  </a>
                </td>

                <td>
                  <a href="{{ route('doctors.detail', [ 'id' => $call->doctor_id ]) }}">
                    <img src="{{ $call->d_profile_image }}"
                      style="width: 50px;
                        height: 50px;
                        object-fit: cover;
                        border-radius: 50%;"
                      onerror="event.target.remove();" /> <br />

                    {{ $call->d_name ?? "" }} <br />
                    {{ $call->d_email ?? "" }}
                  </a>
                </td>

                <td>
                  <img src="{{ $call->p_profile_image }}"
                    style="width: 50px;
                      height: 50px;
                      object-fit: cover;
                      border-radius: 50%;"
                    onerror="event.target.remove();" /> <br />
                    
                  {{ $call->p_name ?? "" }} <br />
                  {{ $call->p_email ?? "" }}
                </td>

                <td>{!! $call->message ?? "" !!}</td>
                <td>{{ $call->type ?? "" }}</td>
                <td>{{ $call->status ?? "" }}</td>
                <td>{{ get_duration($call->start ?? "", $call->end ?? "") }}</td>
                <td>{{ date("d F, Y H:i:s a", strtotime($call->created_at . " UTC")) }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>

        {!! $pagination !!}
      </div>
    </div>
  </section>

@endsection