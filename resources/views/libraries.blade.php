<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find Libraries near you!
      </h1>

      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
        <form id="searchForm" action="{{ route('search.locations') }}" method="GET" class="flex items-center space-x-2">
          <input type="text" id="locationInput" name="location" placeholder="Enter Library name" required class="border p-2 rounded-md w-full sm:w-auto"/>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
            Search Libraries
          </button>
        </form>
      </div>

    <!-- Location Result Form -->
    <div id="locations-results-form" class="mt-4 space-y-4"></div>
    @if(session('error'))
        <div class="text-red-500">{{ session('error') }}</div>
    @endif

      <!-- Google Map Embed -->
      <div id="map" style="width: 100%; height: 340px;" class="mt-8 border rounded-md"></div>
    </div>

    <div class="mt-8 block w-full p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
      <div id="locations-results" class="mt-4"></div>
    </div>
  </section>

  <script src="{{ asset('js/libraries.js') }}"></script>

</x-layout>
