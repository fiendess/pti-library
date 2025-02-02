<x-layout>
<div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-1 xl:gap-4 dark:bg-gray-900">
  <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:border-gray-700 dark:bg-gray-800">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $book->title }}</h1>
      <p class="text-lg text-gray-500 dark:text-gray-400">Author: {{ $book->author }}</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
      <div class="flex-shrink-0">
        <img class="rounded-lg" src="{{ $book->cover_image }}" 
             alt="Cover of {{ $book->title }}" style="max-width: 200px; height: auto;">
      </div>
      <div>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Description</h2>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ strip_tags($book->description) }}</p>

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-6">Publisher</h2>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $book->publisher }}</p>

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-6">Published Date</h2>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $book->published_date }}</p>

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-6">ISBN</h2>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $book->isbn }}</p>
      </div>
    </div>

    <div class="mt-6 border-t pt-4">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Actions</h3>
      <div class="mt-4 flex gap-4">
        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Locate this book</button>
      </div>
    </div>
  </div>
</div>
</x-layout>
