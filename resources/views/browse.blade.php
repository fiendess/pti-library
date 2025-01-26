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
          <input 
            type="text" 
            id="bookTitle" 
            placeholder="Enter book title" 
            required 
            class="border p-2 rounded-md w-64"
          >
          <button 
            type="submit" 
            class="px-4 py-2 bg-purple-600 text-white rounded-md"
          >
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

      <!-- Map Container -->
      <div id="map" style="width: 100%; height: 500px;" class="mt-8"></div>
    </div>
  </section>
 <script src="{{ asset('js/browse.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA&libraries=places&callback=initMap" async defer></script>
</x-layout>