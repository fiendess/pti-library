<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find Libraries or Books Near you!
      </h1>
      
      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
        <!-- Form for searching specific location -->
                    <form class="max-w-2xl mx-auto w-full">
                <div class="flex">
                    <button id="dropdown-button" data-dropdown-toggle="dropdown" class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">All categories <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
              </svg></button>
                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Books</button>
                        </li>
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Libraries</button>
                        </li>
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">List</button>
                        </li>
                        </ul>
                    </div>
                    <div class="relative w-full">
                        <input type="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Search for books and libraries" required />
                        <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </div>
                </div>
            </form>

        <!-- Button for searching current location -->
        <!-- <button id="searchNearby" class="px-4 py-2 bg-green-600 text-white rounded-md mt-4 sm:mt-0">Find Near Me</button> -->
      </div>

      <div class="mt-8">
        <!-- Locations Results -->
        <div id="locations-results" class="mt-8"></div>
      </div>
      
      <!-- Google Map Embed -->
      <div id="map" style="width: 100%; height: 400px;" class="mt-8"></div>
    </div>

    
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
