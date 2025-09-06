@extends ("layouts/app")
@section ("title", "Services")

@section ("main")

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="bold">Our <span class="color-primary">Services</span></h1>

                <p class="mt-5">We bring quality <span class="color-primary">health care</span> to your home with online doctors <br /> consultations, lab test booking and more</p>

                {{--
                <a href="#" class="btn bg-primary-gradient no-border white bold mt-5"
                    style="padding: 20px 40px;
                        font-size: 20px;">
                    Book an Appointment

                    <i class="fa-solid fa-angle-right ms-3 bold"></i>
                </a>

                <h2 class="mt-5 bold">Why choose our <span class="color-gradient-purple">services</span></h1>
                --}}
            </div>
        </div>

        {{--
        <div class="row mt-5" id="why-choose-our-services">
            <div class="col-md-3">
                <img src="{{ asset('/img/Doctor First Aid 1 Streamline UX Duotone.png') }}" />
                <span>Certified Doctors</span>
            </div>

            <div class="col-md-3">
                <img src="{{ asset('/img/Call Support Streamline UX Colors.png') }}" />
                <span>24/7 Online support</span>
            </div>

            <div class="col-md-3">
                <img src="{{ asset('/img/Padlock Circle Streamline UX Colors.png') }}" />
                <span>Secure and private</span>
            </div>

            <div class="col-md-3">
                <img src="{{ asset('/img/Hand Coin 1 Streamline UX Colors.png') }}" />
                <span>Affordable pricing</span>
            </div>
        </div>
        --}}

        <div id="special-area-of-interest" class="mt-5">
            <div class="row">
                <div class="col-md-10 center-horizontal border-primary">
                    <h2 class="text-center bold mt-3">Special areas of interest</h2>

                    <div class="areas mt-5 mb-3">

                        <div class="row">
                            @foreach (get_areas_of_interest() as $area)
                                @if (!in_array($area->name, ["Virus ALT"]))
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ $area->url }}"
                                            style="background-color: {{ $area->bg_color }};">
                                            <img src="{{ $area->img }}" />
                                            <span>{{ $area->name }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{--
                        <div class="row g-3">
                            <div class="col-5th">
                                <div style="background-color: #E1BEE7;">
                                    <img src="{{ asset('/img/areas-of-interest/diabetes.png') }}" />
                                    <span>Diabetes</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #F8BBD0;">
                                    <img src="{{ asset('/img/areas-of-interest/blood_pressure.png') }}" />
                                    <span>Hypertension</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #FFF59D;">
                                    <img src="{{ asset('/img/areas-of-interest/angry.png') }}" />
                                    <span>Angry</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #B3E5FC;">
                                    <img src="{{ asset('/img/areas-of-interest/coughing_alt.png') }}" />
                                    <span>Coughing</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #B2DFDB;">
                                    <img src="{{ asset('/img/areas-of-interest/geriatrics.png') }}" />
                                    <span>Geriatrics</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-5th">
                                <div style="background-color: #B3E5FC;">
                                    <img src="{{ asset('/img/areas-of-interest/diarrhea.png') }}" />
                                    <span>Diarrhea</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #FFE0B2;">
                                    <img src="{{ asset('/img/areas-of-interest/urology.png') }}" />
                                    <span>Urology</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #E1BEE7;">
                                    <img src="{{ asset('/img/areas-of-interest/allergies.png') }}" />
                                    <span>Allergies</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #F8BBD0;">
                                    <img src="{{ asset('/img/areas-of-interest/headache.png') }}" />
                                    <span>Headache</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #F0F4C3;">
                                    <img src="{{ asset('/img/areas-of-interest/virus_alt.png') }}" />
                                    <span>Virus ALT</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-5th">
                                <div style="background-color: #FECDD2;">
                                    <img src="{{ asset('/img/areas-of-interest/hematology.png') }}" />
                                    <span>Hematology</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #B3E5FC;">
                                    <img src="{{ asset('/img/areas-of-interest/lungs.png') }}" />
                                    <span>Lungs</span>
                                </div>
                            </div>

                            <div class="col-5th">
                                <div style="background-color: #FFE0B2;">
                                    <img src="{{ asset('/img/areas-of-interest/mosquito.png') }}" />
                                    <span>Mosquito</span>
                                </div>
                            </div>
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <h2 class="bold">Best Doctors</h2>
            </div>
        </div>

        <div id="best-doctors" class="mt-3">
            
        </div>

        {{--
        <div class="row g-3 mt-5" id="testimonial">
            <div class="col-md-6 testimonial">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="{{ asset('/img/Ellipse 12.png') }}" />
                    </div>

                    <div class="col-md-7 col-8 ps-4">
                        <p class="name">Ayesha Khan</p>
                        <p class="location">Lahore Pakistan</p>
                    </div>

                    <div class="col-md-3 col-12 ratings">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <div class="col-md-12 col-12 mt-5 text-center review">Very satisfied with the online doctor consultation. The process was quick and easy, and I received my prescription in minutes.</div>
                </div>
            </div>

            <div class="col-md-6 testimonial">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="{{ asset('/img/Ellipse 12.png') }}" />
                    </div>

                    <div class="col-md-7 col-8 ps-4">
                        <p class="name">Ayesha Khan</p>
                        <p class="location">Lahore Pakistan</p>
                    </div>

                    <div class="col-md-3 col-12 ratings">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <div class="col-md-12 col-12 mt-5 text-center review">Very satisfied with the online doctor consultation. The process was quick and easy, and I received my prescription in minutes.</div>
                </div>
            </div>

            <div class="col-md-6 mt-5 testimonial">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="{{ asset('/img/Ellipse 12.png') }}" />
                    </div>

                    <div class="col-md-7 col-8 ps-4">
                        <p class="name">Ayesha Khan</p>
                        <p class="location">Lahore Pakistan</p>
                    </div>

                    <div class="col-md-3 col-12 ratings">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <div class="col-md-12 col-12 mt-5 text-center review">Very satisfied with the online doctor consultation. The process was quick and easy, and I received my prescription in minutes.</div>
                </div>
            </div>

            <div class="col-md-6 mt-5 testimonial">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="{{ asset('/img/Ellipse 12.png') }}" />
                    </div>

                    <div class="col-md-7 col-8 ps-4">
                        <p class="name">Ayesha Khan</p>
                        <p class="location">Lahore Pakistan</p>
                    </div>

                    <div class="col-md-3 col-12 ratings">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <div class="col-md-12 col-12 mt-5 text-center review">Very satisfied with the online doctor consultation. The process was quick and easy, and I received my prescription in minutes.</div>
                </div>
            </div>
        </div>
        --}}

        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <h2 class="bold mb-5">Top <span class="color-gradient-purple">services</span> we offer</h2>
                <p style="color: gray; max-width: 70%;" class="center-horizontal">In today’s fast-paced world, your health deserves the utmost attention and convenience. That’s why HealNet offers a suite of integrated services designed to cater to your healthcare needs digitally:</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <h2 class="bold mb-5">Why choose our <span class="color-gradient-purple">services</span></h2>

                <div class="row">
                    <div class="col-md-2 center-horizontal" style="text-align: left;">
                        <p class="why-our-service">
                            <i class="fa fa-check color-primary"></i>
                            <span>Certified Doctors</span>
                        </p>

                        <p class="why-our-service">
                            <i class="fa fa-check color-primary"></i>
                            <span>24/7 Online support</span>
                        </p>

                        <p class="why-our-service">
                            <i class="fa fa-check color-primary"></i>
                            <span>Affordable pricing</span>
                        </p>

                        <p class="why-our-service">
                            <i class="fa fa-check color-primary"></i>
                            <span>Certified Doctors</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="btn bg-primary-gradient no-border white bold mt-5 center-horizontal"
            style="padding: 20px 40px;
                font-size: 20px;">
            Book an Appointment

            <i class="fa-solid fa-angle-right ms-3 bold"></i>
        </a>
    </div>

    <style>
        .why-our-service span {
            margin-left: 10px;
            font-size: 15px;
        }

        #testimonial .testimonial .row {
            border: 1px solid black;
            border-radius: 20px;
            padding: 50px 20px;
            margin-left: 10px;
            margin-right: 10px;
        }
        #testimonial .testimonial .review {
            color: gray;
        }
        #testimonial .testimonial p {
            margin-bottom: 0px;
        }
        #testimonial .testimonial .name {
            font-weight: bold;
            font-size: 30px;
            color: black;
        }
        #testimonial .testimonial .location {
            color: gray;
        }
        #testimonial .testimonial img {
            width: 100%;
        }
        #testimonial .testimonial .ratings {
            padding: 0px;
        }
        #testimonial .testimonial .ratings svg {
            font-size: 14px;
            color: #FF7F22;
        }

        #special-area-of-interest {
            /*margin-top: 100px;*/
        }
        #special-area-of-interest .col-md-10 {
            padding: 25px 100px;
            border-radius: 20px;
            border-width: 2px !important;
        }
        #special-area-of-interest .areas a {
            text-decoration: none;
            color: black;
            padding: 20px 0px;
            border-radius: 10px;
            text-align: center;
            display: block;
        }
        #special-area-of-interest .areas img {
            width: 50px;
        }
        /*#special-area-of-interest .areas .col-5th div {
            text-align: center;
            border-radius: 10px;
            padding: 20px;
        }
        #special-area-of-interest .areas .col-5th span {
            display: block;
            font-size: 12px;
            margin-top: 10px;
        }*/

        #why-choose-our-services .col-md-3 {
            text-align: center;
        }
        #why-choose-our-services span {
            display: block;
        }
    </style>

    <input type="hidden" id="initial-doctors" value="{{ json_encode($doctors) }}" />
    <input type="hidden" id="initial-total" value="{{ json_encode($total) }}" />
    <input type="hidden" id="initial-pages" value="{{ $pages }}" />
    <input type="hidden" id="initial-specialities" value="{{ json_encode($specialities) }}" />

@endsection

@section ("script")
    <script type="text/babel">
        ReactDOM.createRoot(
            document.getElementById("best-doctors")
        ).render(<BestDoctors />);
    </script>
@endsection