@extends ("layouts/app")
@section ("title", "About Us")

@section ("main")

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="bold">About <span class="color-gradient-purple">Us</span></h1>
                <p class="bold">Empowering health, one click at a time</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-5 center-horizontal" style="background-color: white;
                border: 1px solid black;
                border-radius: 10px;
                padding: 40px 30px;">
                <p class="mb-0">We are a dedicated team of healthcare professionals committed to making <span class="color-primary">quality medical services accessible to everyone.</span> Our platform bridges the gap between patient and doctors through online consultations, expert medical advice, and continuous health monitoring.  Whether you're looking for a general check-up,specialist care, or health guidance, we're here to help anytime, anywhere. Our goal is to build trust, reduce waiting times, and make healthcare simple and stress-free for all.</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="our-mission">
                    <h2 class="bold">Our <span class="color-primary">Mission</span></h2>
                    <p class="mt-4">To simplify and humanize healthcare by providing <span class="color-primary">accessible,affordable, and secure digital medical services</span> to everyone.</p>

                    <ul class="mt-4 mb-0">
                        <li>
                            <i class="fa fa-user white p-2 me-2"></i>
                            <span>Quick access to verified doctors</span>
                        </li>

                        <li>
                            <i class="fa fa-user white p-2 me-2"></i>
                            <span>Trusted care from anywhere</span>
                        </li>

                        <li>
                            <i class="fa fa-user white p-2 me-2"></i>
                            <span>Empowering personal health</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6"></div>
        </div>
    </div>

@endsection