<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        Find items in library near you!
      </h1>
      
      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
        <!-- Form for searching specific location -->
        <form action="{{ route('search.locations') }}" method="GET" class="flex items-center space-x-2">
          @csrf
          <input type="text" name="location" placeholder="Search for bookstores or libraries" required class="border p-2 rounded-md" />
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Search Locations</button>
        </form>

        <!-- Button for searching current location -->
        <button id="searchNearby" class="px-4 py-2 bg-green-600 text-white rounded-md mt-4 sm:mt-0">Find Near Me</button>
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
        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <!-- Cover -->
          <a href="#" class="flex justify-center items-center p-4">
                    <img class="rounded-t-lg" src="{{ $book['cover_image'] }}" alt="Cover of {{ $book['title'] }}" style="max-width: 100%; height: auto;">
                </a>
            <!-- Details -->
            <div class="px-5 pb-5">
                <a href="#">
                    <h4 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        <p>{{ $book->title }}</p>
                    </h4>
                </a>
                <p class="text-gray-500 dark:text-gray-400">{{ $book->author }}</p>

            </div>
        </div>
    @endforeach
</div>
  </section>

  <script>
    let map;
    let service;
    let infowindow;
    let userMarker;

    function initMap() {
      const defaultLocation = { lat: -6.9147, lng: 107.6098 }; // Bandung as default
      map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 12,
      });
    }

    document.getElementById('searchNearby').addEventListener('click', function() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            // Center map to user's location
            map.setCenter(pos);
            map.setZoom(15);

            // Add marker for user's location
            if (userMarker) userMarker.setMap(null);
            userMarker = new google.maps.Marker({
              position: pos,
              map: map,
              title: "Your Location",
              icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            });

            // Search nearby libraries and bookstores
            const request = {
              location: pos,
              radius: 4000, // 4 km radius
              type: ['library', 'book_store']
            };

            service = new google.maps.places.PlacesService(map);
            service.nearbySearch(request, (results, status) => {
              if (status === google.maps.places.PlacesServiceStatus.OK) {
                displayLocationsOnMap(results);
                updateLocationList(results);
              } else {
                alert("No nearby locations found or an error occurred.");
              }
            });
          },
          (error) => {
            handleLocationError(error);
          }
        );
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    });

    function displayLocationsOnMap(locations) {
      locations.forEach(location => {
        const marker = new google.maps.Marker({
          position: location.geometry.location,
          map: map,
          title: location.name,
        });

        const infowindow = new google.maps.InfoWindow({
          content: `<h3>${location.name}</h3><p>${location.vicinity}</p>`,
        });

        marker.addListener("click", () => infowindow.open(map, marker));
      });
    }

    function updateLocationList(locations) {
      const resultsContainer = document.getElementById('locations-results');
      resultsContainer.innerHTML = ''; // Clear previous results

      const locationsList = document.createElement('ul');
      locations.forEach(location => {
        const li = document.createElement('li');
        li.className = 'mb-4 p-4 bg-gray-100 rounded-lg';
        li.innerHTML = `
          <h3 class="text-xl font-semibold">${location.name}</h3>
          <p>${location.vicinity}</p>
          <p>Rating: ${location.rating || 'No rating'}</p>
          <button onclick="map.setCenter(new google.maps.LatLng(${location.geometry.location.lat()}, ${location.geometry.location.lng()})); map.setZoom(17);" 
                  class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">
            View on Map
          </button>
        `;
        locationsList.appendChild(li);
      });

      resultsContainer.appendChild(locationsList);
    }

    function handleLocationError(error) {
      let errorMessage = '';
      switch (error.code) {
        case error.PERMISSION_DENIED:
          errorMessage = "Permission denied. Please allow location access.";
          break;
        case error.POSITION_UNAVAILABLE:
          errorMessage = "Location information is unavailable.";
          break;
        case error.TIMEOUT:
          errorMessage = "The request to get user location timed out.";
          break;
        default:
          errorMessage = "An unknown error occurred.";
          break;
      }
      alert(errorMessage);
    }
  </script>

  <!-- Google Maps API Script -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA&libraries=places&callback=initMap" async defer></script>
</x-layout>
