@extends ("layouts/app")
@section ("title", "Doctors")

@section ("main")

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="bold">Find a <span class="color-primary">Doctor</span></h1>
                <p>Search for <span class="color-primary">top-rated specialists</span> and book online consultations instantly.</p>
            </div>
        </div>

        <div id="best-doctors" class="mt-5">
            
        </div>
    </div>

    <input type="hidden" id="initial-doctors" value="{{ json_encode($doctors) }}" />
    <input type="hidden" id="initial-total" value="{{ json_encode($total) }}" />
    <input type="hidden" id="initial-pages" value="{{ json_encode($pages) }}" />
    <input type="hidden" id="initial-specialities" value="{{ json_encode($specialities) }}" />

@endsection

@section ("script")
    <script type="text/babel">
        ReactDOM.createRoot(
            document.getElementById("best-doctors")
        ).render(<BestDoctors />);
    </script>
@endsection