@extends ("layouts/app")
@section ("title", "Page not found")

@section ("main")

    <section class="bg-white py-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-blue-600">404</h1>
            <p class="mt-4 text-xl text-gray-800">Page not found</p>
            <p class="mt-2 text-gray-500">The page you’re looking for doesn’t exist or has been moved.</p>

            <a href="{{ url('/') }}"
               class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
              Go to Homepage
            </a>
        </div>
      </div>
    </section>

@endsection