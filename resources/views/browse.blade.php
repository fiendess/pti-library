<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find Books in Nearby Libraries!
      </h1>
      
      <!-- Book Search Form -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 mb-8">
        <form id="bookSearchForm" class="flex items-center space-x-2">
          <input class="border p-2 rounded-md w-64" type="text" id="bookTitle" placeholder="Enter book title" required>
          </input>
          <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md">
            Search Book
          </button>
        </form>
      </div>

      <!-- Search Results Sections -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Book Details -->
        <div id="bookResults" class="text-left"></div>
        <!-- Library Locations -->
        <div id="libraryResults">
          <div id="locations-results" class="mt-8"></div>
        </div>
      </div>

      @if(isset($libraries))
        <h2>Search results for "{{ $query }}"</h2>
        <ul>
            @forelse($libraries as $library)
                <li>{{ $library->name }} - {{ $library->location }}</li>
            @empty
                <li>No libraries found.</li>
            @endforelse
        </ul>
    @endif


      <!-- Map Container -->
      <div id="map-container" class="hidden transition duration-500 ease-in-out relative mt-6">
    <div id="map" class="w-full h-96"></div>
</div>
    </div>
  </section>
<script src="{{ asset('js/browse.js') }}"></script>
</x-layout>