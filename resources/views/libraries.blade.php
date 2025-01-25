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
          class="px-4 py-2 bg-green-600 text-white rounded-md mt-4 sm:mt-0"
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
      <div class="mt-8 block w-full p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700"">
        <div id="locations-results" class="mt-4">

        </div>
      </div>
  </div>
  </section>

 <script>
  let map;
  let service;
  const bandungCenter = { lat: -6.9147, lng: 107.6098 }; 
  let userLocation = bandungCenter;

  function initMap() {
    // Inisialisasi peta
    map = new google.maps.Map(document.getElementById("map"), {
      center: bandungCenter,
      zoom: 12,
    });
  }

  document.getElementById("searchNearby").addEventListener("click", function () {
    // Reset peta ke tengah Kota Bandung
    map.setCenter(bandungCenter);
    map.setZoom(14);

    const request = {
      query: "perpustakaan atau library",
      location: bandungCenter,
      radius: 10000, 
    };

    service = new google.maps.places.PlacesService(map);
    service.textSearch(request, (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        if (results.length > 0) {
          displayLocationsOnMap(results);
          fetchDetailedInfo(results);
        } else {
          alert("No libraries or bookstores found nearby.");
        }
      } else {
        alert("An error occurred while searching for locations.");
      }
    });
  });

  function displayLocationsOnMap(locations) {
    locations.forEach((location) => {
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

  function fetchDetailedInfo(locations) {
    const resultsContainer = document.getElementById("locations-results");
    resultsContainer.innerHTML = ""; // Bersihkan hasil sebelumnya

    const locationsList = document.createElement("ul");

    locations.forEach((location) => {
      const request = { placeId: location.place_id };

      service.getDetails(request, (place, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          // Hitung jarak dari lokasi user ke lokasi tempat
          const distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(userLocation.lat, userLocation.lng),
            place.geometry.location
          );

          // Konversi jarak ke kilometer
          const distanceKm = (distance / 1000).toFixed(1);

          const li = document.createElement("li");
            li.className = "flex flex-col sm:flex-row items-start space-x-4 mb-4 p-4 bg-gray-100 rounded-lg relative";
            li.innerHTML = `
              <button class="absolute top-2 right-2 px-2 py-1 bg-red-500 text-white rounded-full hover:bg-red-600">
                ❤
              </button>
              ${
                place.photos
                  ? `<img src="${place.photos[0].getUrl({
                      maxWidth: 120,
                    })}" alt="${place.name}" class="w-32 h-32 object-cover rounded-lg" />`
                  : `<div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No Image</div>`
              }

              <div class="flex-1">
                <h3 class="text-xl font-semibold">${place.name}</h3>
                <p>${place.formatted_address || "Address not available"}</p>
                <p>Rating: ${place.rating || "No rating"}</p>
             <div class="flex items-center space-x-2 mt-2">
            <!-- Ikon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            
            <!-- Teks -->
            <p>${distanceKm} kilometers from your current location.</p>
          </div>

                <p>Open Now: ${
                  place.opening_hours?.isOpen() ? "Yes" : "No"
                }</p>

                <button onclick="map.setCenter(new google.maps.LatLng(${place.geometry.location.lat()}, ${place.geometry.location.lng()})); map.setZoom(17);" 
                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">
                  Get Directions
                </button>
              </div>
            `;

          locationsList.appendChild(li);
        }
      });
    });

    resultsContainer.appendChild(locationsList);
  }
</script>

<!-- Tambahkan library Geometry API pada Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA&libraries=places,geometry&callback=initMap" async defer></script>
</x-layout>