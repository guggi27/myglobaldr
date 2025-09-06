@extends ("layouts/app")

@section ("main")

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="bold" style="font-size: 60px;">
                    Your
                    <span class="color-gradient-purple">trusted partner</span>
                    in digital healthcare.
                </h2>

                <p class="mt-5 text-justify">
                    <span class="color-primary bold">Empowering Your Health at Every Step.</span>
                    Experience personalized medical care from the comfort of your home. Connect with
                    <span class="color-primary bold">certified doctors,</span>
                    or manage prescriptions, and schedule appointments with ease. Ready to take control of your health?
                    <a href="#" class="color-primary bold no-underline on-hover-underline">Get Started</a>
                    or Book an Appointment today.
                </p>

                <a href="#" class="btn bg-primary-gradient no-border white bold mt-5"
                    style="padding: 20px 40px;
                        font-size: 20px;">
                    Book an Appointment

                    <i class="fa-solid fa-angle-right ms-3 bold"></i>
                </a>
            </div>

            <div class="col-md-6">
                <img src="{{ asset('/img/banner-doctor.png') }}" style="width: 100%;" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p class="mt-5 mb-3" style="color: gray;">Trusted by millions across the globe:</p>

                <img src="{{ asset('/img/companies-logos.png') }}" style="width: 300px;
                    max-width: 100%;" />
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="owl-carousel areas-of-interest">
                    @foreach (get_areas_of_interest() as $area)
                        <div class="item">
                            <a href="{{ $area->url }}"
                                style="background-color: {{ $area->bg_color }};">
                                <img src="{{ $area->img }}" />
                                <span>{{ $area->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 100px;
            margin-bottom: 100px;">
            <div class="col-md-10 center-horizontal">
                <div id="book-appointments" class="border-primary bg-secondary-gradient">
                    <p class="color-primary bold" style="font-size: 30px;">Easily book an appointment in 3 simple steps.</p>

                    <form>
                        <div class="row mt-5 pb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label child-center-vertical">
                                        <i class="fa fa-envelope color-primary" style="font-size: 22px;"></i>&nbsp;&nbsp;
                                        Email Address
                                    </label>

                                    <input type="email" name="email" placeholder="Enter your Email Address"
                                        class="form-control mt-3 mb-3 input" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label child-center-vertical">
                                        <i class="fa fa-envelope color-primary" style="font-size: 22px;"></i>&nbsp;&nbsp;
                                        Contact No.
                                    </label>

                                    <input type="text" name="contact" placeholder="Enter your Contact No."
                                        class="form-control mt-3 mb-3 input" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label child-center-vertical">
                                        <i class="fa fa-envelope color-primary" style="font-size: 22px;"></i>&nbsp;&nbsp;
                                        Date of Appointment
                                    </label>

                                    <input type="date" name="date_appointment" placeholder="Select Date of Appointment"
                                        class="form-control mt-3 mb-3 input" />
                                </div>
                            </div>

                            <div class="col-md-3" style="position: relative;">
                                <button type="submit" class="btn white bold"
                                    style="background-color: #1F2ADF;
                                        padding: 10px 20px;
                                        font-size: 22px;
                                        position: relative;
                                        top: 60%;
                                        transform: translateY(-50%);">
                                    Book Now
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center bold">
                    Top
                    <span class="color-gradient-purple-reverse">services</span>
                    we offer
                </h2>
            </div>

            <div class="col-md-8 center-horizontal text-justify mt-3">
                <p class="text-center" style="color: darkgray;">In today’s fast-paced world, your health deserves the utmost attention and convenience. That’s why HealNet offers a suite of integrated services designed to cater to your healthcare needs digitally:</p>
            </div>
        </div>

        <div id="services" class="mt-5">
            <div class="row">
                <div class="col-md-7 mb-3">
                    <div class="section">
                        <i class="fa-solid fa-camera"></i>

                        <p class="heading">Online Consultations</p>

                        <p class="content">Consult with top doctors across various specialties via video or chat communication. It’s totally secure, private, and convenient. Choose the best time for an in-person visit with our easy-to-use scheduling system, or proceed with our online consultation.</p>
                    </div>
                </div>

                <div class="offset-md-1 col-md-4">
                    <div class="section">
                        <i class="fa-solid fa-calendar-days"></i>

                        <p class="heading">Booking Appointments</p>

                        <p class="content">Choose the best time for an in-person visit with our easy-to-use scheduling system, or proceed with our online consultation features.</p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-4 mb-3">
                    <div class="section">
                        <i class="fa-solid fa-calendar-days"></i>

                        <p class="heading">Prescriptions</p>

                        <p class="content">Receive and renew prescriptions digitally after your consultation with our specialists.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="section">
                        <i class="fa-solid fa-calendar-days"></i>

                        <p class="heading">Medical Notes</p>

                        <p class="content">Obtain necessary medical notes for work or school with only a few clicks of hassle.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="section">
                        <i class="fa-solid fa-calendar-days"></i>

                        <p class="heading">Medicine Refills</p>

                        <p class="content">Skip the pharmacy queues and save time + energy by ordering medicine refills online.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5" style="margin-left: 5px;
            margin-right: 5px;">
            <div class="col-md-12">
                <div class="row border-primary" style="border-radius: 20px;
                padding: 40px;
                border-width: 1px !important;">
                    <div class="col-md-6">
                        <img src="{{ asset('/img/doctor-smiling-female.png') }}"
                            style="width: 100%;" />
                    </div>

                    <div class="col-md-6" style="position: relative;">
                        <p>HealNet is more than just an online medical service; it’s a movement towards accessible, efficient, and compassionate healthcare for all. Founded by a team of visionary doctors, healthcare professionals, and technology experts, we are driven by the mission to deliver exceptional medical care directly to you, no matter where you are. Our platform is built on the pillars of trust, innovation, and patient-centricity, ensuring that every interaction is personalized and every treatment plan is tailored to your unique needs. With a network of licensed practitioners from diverse medical fields, we guarantee comprehensive care that’s just a click away.</p>

                        <a href="#" class="btn white bold"
                            style="background-color: #1F2ADF;
                                padding: 10px 20px;
                                font-size: 22px;
                                position: absolute;
                                bottom: 0px;">
                            Learn more about us
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 100px;
            margin-bottom: 100px;">
            <div class="col-md-8 center-horizontal">
                <h2 class="text-center bold">
                    How
                    <span class="color-gradient-purple-reverse">our platform</span>
                    works
                </h2>

                <p class="text-center mt-5" style="color: gray;
                    max-width: 900px;">Navigating your healthcare journey with HealNet is seamless. Just follow these steps mentioned below to proceed with your selected services. You can also see our FAQ section for more guidance:</p>

                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-number">1</div>

                                <div class="ms-3">
                                    <h3 class="bold">Create Your Profile</h3>
                                    <p>Sign up and fill in your medical history securely. Setting up your profile this way would ensure that you stay up-to-date with your medical processes.</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-number">2</div>

                                <div class="ms-3">
                                    <h3 class="bold">Choose Your Service</h3>
                                    <p>Select from our range of services and book a consultation. Booking a consultation with HealNet is fairly simple and straight-forward.</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-number">3</div>

                                <div class="ms-3">
                                    <h3 class="bold">Meet Your Doctor</h3>
                                    <p>Have a virtual consultation with one of our certified specialists, or go for a physical visit to the doctor in case you opted for a physical check-up.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <img src="{{ asset('/img/doctor-1.png') }}"
                            style="width: 100%; max-width: 500px;" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2 class="color-gradient-purple-reverse bold text-center">Patient Testimonials:</h2>
                <p class="text-center bold black" style="font-size: 30px;">Hear from Those We've Cared For</p>
                <p class="text-center mt-5" style="color: gray;">Discover the difference we make through the voices of those we’ve served:</p>
            </div>

            <div class="col-md-10 mt-5 center-horizontal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row testimonial">
                            <div class="col-md-3">
                                <img src="{{ asset('/img/testimonial-1.png') }}" style="width: 100%;" />
                            </div>

                            <div class="col-md-9">
                                <p class="review">“After my knee surgery, the convenience of online consultations made my recovery smoother than I could have imagined.”</p>
                                <p class="name bold black mb-0">- Linda A.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="row testimonial">
                            <div class="col-md-3">
                                <img src="{{ asset('/img/testimonial-2.png') }}" style="width: 100%;" />
                            </div>

                            <div class="col-md-9">
                                <p class="review">“Managing chronic conditions like diabetes requires a lot of vigilance, but the medicine refill system has simplified my life.”</p>
                                <p class="name bold black mb-0">- Henry B.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-10 center-horizontal">
                <div class="row stats">
                    <div class="col-md-3">
                        <p class="number color-gradient-purple">10,000+</p>
                        <p class="info">Successful Consultants</p>
                    </div>

                    <div class="col-md-3">
                        <p class="number color-gradient-purple">2,500+</p>
                        <p class="info">Healthcare Professionals</p>
                    </div>

                    <div class="col-md-3">
                        <p class="number color-gradient-purple">98%</p>
                        <p class="info">Patient Satisfaction Rate</p>
                    </div>

                    <div class="col-md-3">
                        <p class="number color-gradient-purple">200+</p>
                        <p class="info">Top Specialists</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mt-5 center-horizontal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row testimonial">
                            <div class="col-md-3">
                                <img src="{{ asset('/img/testimonial-3.png') }}" style="width: 100%;" />
                            </div>

                            <div class="col-md-9">
                                <p class="review">“The prescription refill system is a game-changer for managing my diabetes. It’s really efficient and completely hassle-free.”</p>
                                <p class="name bold black mb-0">- Joshua T.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="row testimonial">
                            <div class="col-md-3">
                                <img src="{{ asset('/img/testimonial-4.png') }}" style="width: 100%;" />
                            </div>

                            <div class="col-md-9">
                                <p class="review">“Finding a doctor who really understands all of my health needs has never been easier. This platform has changed my life.”</p>
                                <p class="name bold black mb-0">- Samantha K.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 100px;" id="masters-of-medicine">
            <div class="col-md-12 text-center">
                <h2 class="color-gradient-purple bold" style="font-size: 36px;">Masters of Medicine:</h2>
                <p class="bold" style="font-size: 30px;">Meet our team of specialists</p>
                
                <div class="col-md-8 center-horizontal mt-5">
                    <p style="color: gray;">Our team of specialists is at the forefront of medical innovation. Each specialist brings a unique blend of expertise, empathy, and experience to ensure that your health is in the best hands:</p>

                    <div class="row mt-5 p-0" style="background: linear-gradient(to right, rgb(121, 123, 254), rgb(23, 0, 215));
                        padding: 20px;
                        border-radius: 20px;
                        margin-left: 10px;
                        margin-right: 10px;">
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/img/doctor-2.png') }}" style="width: 100%;" />
                        </div>

                        <div class="col-md-8 pt-5 mb-4" style="text-align: left;
                            position: relative;">
                            <p class="white bold" style="font-size: 26px;">Dr. Sarah Johnson (Cardiologist)</p>
                            <p class="white">Heart health is Dr. Wong’s passion, and her approach to cardiology integrates cutting-edge technology with compassionate care. She’s a respected voice in the prevention of heart disease and a trusted partner to her patients on their journey to wellness.</p>

                            <a href="#" style="background-color: rgb(226, 225, 247);
                                padding: 15px 20px;
                                border-radius: 10px;
                                font-size: 18px;
                                text-decoration: none;
                                position: absolute;
                                bottom: 0px;"
                                class="book-appointment">
                                
                                <span class="color-gradient-purple-reverse">
                                    Book appointment&nbsp;
                                </span>

                                <i class="fa fa-phone"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 100px;">
            <div class="col-md-12 text-center">
                <h2 class="bold">Reach our <span class="color-gradient-purple-reverse">Help Desk</span> for support</h2>
            </div>

            <div class="col-md-5 mt-5 center-horizontal text-center">
                <p style="color: gray;">
                    Questions? Need assistance? Our dedicated support team is here to help you every step of the way:
                </p>
            </div>
        </div>

        <form>
            <div class="row mt-5">
                <div class="col-md-8 center-horizontal">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div style="position: relative;">
                              <i class="fa fa-user color-primary" 
                                 style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); color: #888888; pointer-events: none;"></i>
                              
                              <input type="text" placeholder="Enter your First Name"
                                    class="border-primary" 
                                     style="padding: 15px 8px 15px 50px; border-radius: 10px; font-size: 1rem; width: 100%;" />
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div style="position: relative;">
                              <i class="fa fa-envelope color-primary" 
                                 style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); color: #888888; pointer-events: none;"></i>
                              
                              <input type="email" placeholder="Enter your Email Address" 
                                    class="border-primary" 
                                     style="padding: 15px 8px 15px 50px; border-radius: 10px; font-size: 1rem; width: 100%;" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" style="background-color: #1F2ADF;
                                color: white;
                                padding: 10px 25px;
                                border: none;
                                font-weight: bold;
                                font-size: 20px;
                                border-radius: 10px;">
                                Contact us &nbsp;
                                <i class="fa-solid fa-circle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener("load", function () {
            $('.owl-carousel.areas-of-interest').owlCarousel({
                items: 9,
                loop: true,
                margin: 10,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true, // ✅ Pauses on hover
                responsive: {
                    0: { items: 1 },
                    768: { items: 4 },
                    1024: { items: 9 }
                }
            });
        });
    </script>

    <style>
        .areas-of-interest .owl-nav .owl-prev,
        .areas-of-interest .owl-nav .owl-next {
            font-size: 50px !important;
        }
        .areas-of-interest .owl-nav {
            text-align: center;
            margin-top: 20px;
        }
        .areas-of-interest .item a {
            text-decoration: none;
            color: black;
            padding: 20px 0px;
            border-radius: 10px;
            text-align: center;
            display: block;
        }
        .areas-of-interest .item img {
            width: 50px;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        .testimonial {
              border: 1px solid #1F2ADF !important;
              padding: 20px;
              border-radius: 20px;
              background: linear-gradient(120deg, #d5d6fe 1%, #f7f9fa 99%);
              margin-right: 10px;
              margin-left: 10px;
              margin-bottom: 10px;
        }
        .testimonial .review {
              color: gray;
        }
        .timeline {
              position: relative;
        }
        .timeline-item {
              position: relative;
              margin-left: 40px;
              padding-bottom: 40px; /* space for connector */
        }
        .timeline-number {
              position: absolute;
              left: -50px;
              top: 0;
              width: 50px;
              height: 50px;
              background: linear-gradient(to right, #9097FF, #1F2ADF);
              border: none;
              border-radius: 50%;
              text-align: center;
              line-height: 26px;
              font-weight: bold;
              color: white;
              align-content: center;
              font-size: 30px;
        }
        /* connector line: stretch until next item */
        .timeline-item::after {
              content: "";
              position: absolute;
              left: -25px; /* center under circle */
              top: 50px;   /* bottom of circle */
              bottom: 0;   /* stretch to next item */
              /*border-left: 4px dashed #9097FF;*/

              /* Remove normal border */
              border-left: none;

              /* Custom dashed effect */
              width: 2px;
              background-image: repeating-linear-gradient(
                    to bottom,
                    #9097FF 0,
                    #9097FF 6px,   /* dash length */
                    transparent 10px,
                    transparent 18px /* gap length */
              );
        }
        /* remove connector after last item */
        .timeline-item:last-child::after {
              display: none;
        }
        #services .section {
              position: relative;
              padding: 40px;
              border-radius: 20px; /* border radius */
              background: linear-gradient(90deg, #9097FF, #1F2ADF); /* gradient border */
              z-index: 0; /* create stacking context */
        }
        #services .section::before {
              content: "";
              position: absolute;
              inset: 1px; /* border thickness */
              background: #fff; /* inner background */
              border-radius: 18px; /* radius - border thickness */
              z-index: -1; /* push behind text */
        }
        #services .section {
              position: relative;
              z-index: 1; /* keep text above */
        }
        #services .content {
              color: gray !important;
              max-width: 600px;
              text-align: justify;
              margin-bottom: 0px;
        }
        #services .heading {
              color: #1F2ADF !important;
              font-weight: bold;
              font-size: 20px;
              margin-top: 20px;
        }
        #services svg {
              background-color: #EFEFFF;
              color: #FF005F !important;
              padding: 10px;
              border-radius: 10px;
              font-size: 30px;
        }
    </style>

@endsection