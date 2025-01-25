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

  <script>
    let map;
    let service;
    let markers = [];
    const googleBooksAPI = 'https://www.googleapis.com/books/v1/volumes';

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -6.9147, lng: 107.6098 },
        zoom: 12
      });
    }

    // Book Search Handler
    document.getElementById('bookSearchForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const bookTitle = document.getElementById('bookTitle').value;
      
      try {
        // 1. Search Book using Google Books API
        const bookResponse = await fetch(`${googleBooksAPI}?q=${encodeURIComponent(bookTitle)}`);
        const bookData = await bookResponse.json();
        
        if (!bookData.items || bookData.items.length === 0) {
          alert('No books found!');
          return;
        }
        
        // Display Book Details
        displayBookDetails(bookData.items[0]);
        
        // 2. Search Libraries with Place API
        searchLibrariesNearby(bookData.items[0]);

      } catch (error) {
        console.error('Error:', error);
        alert('Error searching for books');
      }
    });

    function displayBookDetails(book) {
      const bookInfo = book.volumeInfo;
      const bookResults = document.getElementById('bookResults');
      
      bookResults.innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h2 class="text-2xl font-bold mb-4">${bookInfo.title}</h2>
          ${bookInfo.authors ? `<p class="text-gray-600">By: ${bookInfo.authors.join(', ')}</p>` : ''}
          ${bookInfo.publishedDate ? `<p class="text-gray-600">Published: ${bookInfo.publishedDate}</p>` : ''}
          ${bookInfo.industryIdentifiers ? `
            <p class="text-gray-600 mt-2">
              ISBN: ${bookInfo.industryIdentifiers.map(id => `${id.type} ${id.identifier}`).join(', ')}
            </p>` : ''}
          ${bookInfo.imageLinks ? `
            <img src="${bookInfo.imageLinks.thumbnail}" 
                 alt="${bookInfo.title}" 
                 class="mt-4 mx-auto w-32 h-48 object-contain">` : ''}
        </div>
      `;
    }

    async function searchLibrariesNearby(book) {
      try {
        // Get user's location
        const position = await new Promise((resolve, reject) => {
          navigator.geolocation.getCurrentPosition(resolve, reject);
        });
        
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        // Configure Places API request
        const request = {
          location: pos,
          radius: 4000, // 4km
          type: ['library', 'book_store'],
          keyword: `"${book.volumeInfo.title}" book` // Search libraries with book title
        };

        // Search libraries
        service = new google.maps.places.PlacesService(map);
        service.nearbySearch(request, (results, status) => {
          if (status === google.maps.places.PlacesServiceStatus.OK) {
            updateMapAndList(results, book);
          } else {
            alert('No libraries found with this book');
          }
        });

      } catch (error) {
        handleLocationError(error);
      }
    }

    function updateMapAndList(libraries, book) {
      // Clear previous markers
      markers.forEach(marker => marker.setMap(null));
      markers = [];

      // Update Map
      libraries.forEach(library => {
        const marker = new google.maps.Marker({
          position: library.geometry.location,
          map: map,
          title: `${library.name} - ${book.volumeInfo.title}`,
          icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });

        const infowindow = new google.maps.InfoWindow({
          content: `
            <div class="p-2">
              <h3 class="font-bold">${library.name}</h3>
              <p>${library.vicinity}</p>
              <p class="mt-2 text-sm">
                <span class="font-semibold">Book Available:</span> 
                ${book.volumeInfo.title}
              </p>
            </div>
          `
        });

        marker.addListener('click', () => infowindow.open(map, marker));
        markers.push(marker);
      });

      // Update List
      const resultsContainer = document.getElementById('locations-results');
      resultsContainer.innerHTML = libraries.map(library => `
        <div class="p-4 mb-4 bg-white rounded-lg shadow">
          <h3 class="text-xl font-semibold">${library.name}</h3>
          <p class="text-gray-600">${library.vicinity}</p>
          <div class="mt-2">
            <button 
              onclick="map.panTo(new google.maps.LatLng(${library.geometry.location.lat()}, ${library.geometry.location.lng()})); map.setZoom(17);" 
              class="px-3 py-1 bg-blue-500 text-white rounded"
            >
              View on Map
            </button>
          </div>
        </div>
      `).join('');
    }

    function handleLocationError(error) {
      // ... (same error handling as previous code)
    }
  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA&libraries=places&callback=initMap" async defer></script>
</x-layout>