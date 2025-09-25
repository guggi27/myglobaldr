@extends ("admin/layouts/app")
@section ("title", "Settings")

@section ("main")

  <div class="pagetitle">
    <h1>Settings</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
        <li class="breadcrumb-item active">Settings</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

<section class="section dashboard">
    <form method="post" action="{{ url('/admin/settings') }}">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label class="form-label">Emergency Number</label>
                    <input type="text" name="emergency_number" autocomplete="off" class="form-control"
                        value="{{ fetch_setting_by_key('emergency_number', $settings) }}" />
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" autocomplete="off" class="form-control"
                        value="{{ fetch_setting_by_key('whatsapp_number', $settings) }}" />
                </div>

                <div class="submit-section mt-3">
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Save</button>
                </div>
            </div>
        </div>
    </form>
</section>

@endsection