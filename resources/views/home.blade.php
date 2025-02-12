<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find Libraries Near You!
      </h1>

      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">

        <!-- Button for searching current location -->
        <button id="searchNearby" class="px-4 py-2 bg-green-600 text-white rounded-md mt-4 sm:mt-0 ml-2">
          Find Near Me
        </button>
      </div>

      <!-- Google Map Embed -->
      <div id="map" style="width: 100%; height: 400px;" class="mt-8 border rounded-md"></div>
    </div>

    <!-- Locations Results By nearby -->
    <div class="mt-8 block w-full p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
      <div id="locations-results" class="mt-4"></div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 p-4">
    @foreach ($books as $book)
        <div class="w-full max-w-sm bg-white  dark:border-gray-700">
            <!-- Cover -->
          <a href="/books-detaildb/{{ $book->id }}" class="flex justify-center items-center p-4">
                    <img class="rounded-t-lg" src="{{ $book['cover_image'] }}" alt="Cover of {{ $book['title'] }}" style="max-width: 100%; height: auto;">
                </a>
            <!-- Details -->
            <div class="px-5 pb-5">
                <a href="#">
                    <h4 class="text-md font-semibold tracking-tight text-gray-900 dark:text-white">
                        <p>{{ $book->title }}</p>
                    </h4>
                </a>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>

            </div>
        </div>
    @endforeach
</div>
  </section>
 <script src="{{ asset('js/home.js') }}"></script>
</x-layout>
