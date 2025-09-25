@extends ("admin/layouts/app")
@section ("title", "Call #" . ($call->call_id ?? ""))

@section ("main")

  <div class="pagetitle">
    <div style="display: flex;">
      <h1>Calls</h1>
    </div>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Call #{{ $call->call_id ?? "" }}</li>
      </ol>
    </nav>
  </div>

  <section class="section" style="padding: 0px !important;" id="call-detail">
    <div class="row">
      <div class="col-12">
        <div class="card card-body">
          <div class="section mt-3">
            <h2>Call Information</h2>
            <ul class="info-list">
              <li><span class="label">Call ID:</span> {{ $call->call_id ?? "" }}</li>
              <li><span class="label">Type:</span> {{ ucfirst($call->type ?? "") }}</li>
              <li><span class="label">Status:</span> <span class="status completed">{{ ucfirst($call->status ?? "") }}</span></li>
              <li><span class="label">Date & Time:</span> {{ $call->created_at ?? "" }}</li>
              <li><span class="label">End At:</span> {{ $call->updated_at ?? "" }}</li>
            </ul>
          </div>

          <div class="section">
            <h2>Doctor Information</h2>
            <div class="profile">
              <img src="{{ $call->d_profile_image ?? '' }}"
                alt="{{ $call->d_name ?? '' }}"
                onerror="this.remove();" />
              <div>
                <div><strong>{{ $call->d_name ?? "" }}</strong></div>
                <div>{{ $call->d_email ?? "" }}</div>
              </div>
            </div>
            <ul class="info-list">
              <li>
                <span class="label">Services:</span>

                @foreach ($call->services as $service)
                  {{ $service }} | 
                @endforeach
              </li>
              
              <li>
                <span class="label">Specialities:</span>

                @foreach ($call->specialities as $speciality)
                  {{ $speciality }} | 
                @endforeach
              </li>

              <li><span class="label">Message:</span> {!! $call->message ?? '' !!}</li>
            </ul>
          </div>

          <div class="section mb-0">
            <h2>Patient Information</h2>
            <div class="profile">
              <img src="{{ $call->p_profile_image ?? '' }}"
                alt="{{ $call->p_name ?? '' }}"
                onerror="this.remove();" />

              <div>
                <div><strong>{{ $call->p_name ?? '' }}</strong></div>
                <div>{{ $call->p_email ?? '' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <style>
    #call-detail .section {
      margin-bottom: 30px;
      padding: 15px;
      border-radius: 5px;
      background-color: #f5faff;
      border-color: #cce7ff;
    }

    #call-detail .section h2 {
      font-size: 18px;
      margin-bottom: 15px;
      color: #555;
      border-left: 4px solid #3490dc;
      padding-left: 10px;
    }

    #call-detail .profile {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 10px;
    }

    #call-detail .profile img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
      border: 1px solid #ddd;
    }

    #call-detail .info-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #call-detail .info-list li {
      margin-bottom: 8px;
    }

    #call-detail .label {
      font-weight: bold;
      color: #555;
      min-width: 120px;
      display: inline-block;
    }

    #call-detail .status {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 12px;
      background-color: #e2e8f0;
      color: #333;
    }

    /* Parent styling using :has() for status */
    #call-detail .info-list:has(.status.calling) {
      border-left: 4px solid #ecc94b;
      padding-left: 10px;
    }
    #call-detail .info-list:has(.status.accepted) {
      border-left: 4px solid #ecc94b;
      padding-left: 10px;
    }
    #call-detail .info-list:has(.status.rejected) {
      border-left: 4px solid #e53e3e;
      padding-left: 10px;
    }
    #call-detail .info-list:has(.status.completed) {
      border-left: 4px solid #38a169;
      padding-left: 10px;
    }

    #call-detail .status.completed {
      background-color: #c6f6d5;
      color: #22543d;
    }

    #call-detail .status.calling {
      background-color: #fefcbf;
      color: #744210;
    }

    #call-detail .status.cancelled {
      background-color: #fed7d7;
      color: #742a2a;
    }
  </style>

@endsection