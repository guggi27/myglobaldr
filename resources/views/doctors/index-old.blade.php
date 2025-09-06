@extends ("layouts/app")
@section ("title", $speciality)

@section ("main")

    <section class="bg-white py-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">{{ $speciality }} ({{ $total }})</h2>

        <div class="grid md:grid-cols-3 gap-6">
      
          @foreach ($users as $u)
            <!-- Single Doctor Card -->
            <a href="{{ route('doctors.detail', [ 'id' => $u->id ]) }}" class="block">
              <div class="bg-gray-50 rounded-xl shadow hover:shadow-md transition p-6 flex flex-col items-center text-center">
                <img src="{{ $u->profile_image }}" alt="{{ $u->name }}"
                     class="w-24 h-24 rounded-full mb-4 object-cover shadow-inner"
                     onerror="this.src = baseUrl + '/img/user-placeholder.png'" />
                
                <h3 class="text-lg font-semibold text-gray-800">{{ $u->name }}</h3>
                <p class="text-sm text-gray-500">
                  @foreach ($u->specialities as $speciality)
                    {{ $speciality }} |
                  @endforeach
                </p>
                
                <div class="mt-3 flex flex-wrap justify-center gap-2 text-xs">
                  @foreach ($u->services as $service)
                    <span class="px-2 py-1 rounded-full"
                      style="background-color: {{ random_color() }};
                        color: white;">{{ $service }}</span>
                  @endforeach
                </div>

                <p class="mt-3">Fee {{ strtoupper(config("config.currency")) . " " . $u->fee }}</p>
              </div>
            </a>
          @endforeach

        </div>
      </div>
    </section>

@endsection