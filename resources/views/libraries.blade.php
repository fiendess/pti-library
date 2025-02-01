<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find items in library near you!
      </h1>

      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
        <!-- Form for searching specific location -->
        <form id="searchForm" class="flex items-center space-x-2">
          <input
            type="text"
            id="locationInput"
            name="location"
            placeholder="Search for bookstores or libraries"
            required
            class="border p-2 rounded-md w-full sm:w-auto"
          />
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md"
          >
            Search Locations
          </button>
        </form>

        <!-- Button for searching current location -->
        <button
          id="searchNearby"
          class="px-4 py-2 bg-green-600 text-white rounded-md mt-4 sm:mt-0 ml-2"
        >
          Find Near Me
        </button>
      </div>

      <!-- Google Map Embed -->
      <div
        id="map"
        style="width: 100%; height: 400px;"
        class="mt-8 border rounded-md"
      ></div>
    </div>

      <!-- Locations Results -->
      <div class="mt-8 block w-full p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700"">
        <div id="locations-results" class="mt-4">

        </div>
      </div>
  </div>
  </section>
 <script src="{{ asset('js/libraries.js') }}"></script>

</x-layout>