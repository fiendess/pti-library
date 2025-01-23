<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Find items in library near you!</h1>
      
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
        @if (isset($locations))
          <ul>
            @foreach ($locations as $location)
              <li class="mb-4">
                <h3 class="text-xl font-semibold">{{ $location['name'] }}</h3>
                <p>{{ $location['formatted_address'] }}</p>
                <p>Rating: {{ $location['rating'] ?? 'No rating' }}</p>
                <a href="https://www.google.com/maps?q={{ $location['geometry']['location']['lat'] }},{{ $location['geometry']['location']['lng'] }}" target="_blank" class="text-blue-600">View on Map</a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
      
      <!-- Google Map Embed (Empty initially) -->
      <div id="map" style="width: 100%; height: 400px;"></div>

    </div>
  </section>

  <script>
    // Fungsi untuk mendapatkan lokasi pengguna
    document.getElementById('searchNearby').addEventListener('click', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Debug: Tampilkan koordinat di konsol untuk memverifikasi
            console.log("Latitude: " + lat + ", Longitude: " + lng);

            // Tampilkan lokasi pengguna pada peta
            displayMap(lat, lng);

            // Kirim permintaan API untuk mencari lokasi terdekat
            fetchNearbyLibraries(lat, lng);
        }, function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Permission denied. Please allow location access.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                default:
                    alert("An unknown error occurred.");
                    break;
            }
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
});


let map; // Variabel global untuk peta

function displayMap(lat, lng) {
    if (!map) { // Inisialisasi peta hanya jika belum ada
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: lat, lng: lng },
            zoom: 12,
        });
    } else {
        map.setCenter({ lat: lat, lng: lng });
    }

    // Marker untuk lokasi pengguna
    new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        title: "You are here!",
    });
}

function displayLocationsOnMap(locations) {
    locations.forEach(location => {
        // Pastikan lokasi memiliki koordinat yang valid
        const lat = location.latitude;
        const lng = location.longitude;

        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: location.name,
        });

        const infowindow = new google.maps.InfoWindow({
            content: `<h3>${location.name}</h3><p>${location.address}</p>`,
        });

        marker.addListener("click", function() {
            infowindow.open(map, marker);
        });
    });
}


    // Fetch user's current location and find nearby places
    document.getElementById('searchNearby').addEventListener('click', function () {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Debug: Tampilkan koordinat di konsol untuk memverifikasi
        console.log("Latitude: " + lat + ", Longitude: " + lng);

        // Misalkan kita ingin menampilkan lokasi pada peta menggunakan Google Maps API
        displayMap(lat, lng);
        
        // Kirim permintaan API untuk mencari lokasi terdekat menggunakan lat dan lng
        fetch(`/search-locations?lat=${lat}&lng=${lng}`)
          .then(response => response.json())
          .then(data => {
            // Tampilkan lokasi di peta atau daftar
            displayLocationsOnMap(data);
          })
          .catch(error => console.error('Error fetching locations:', error));
      },
      function(error) {
        switch(error.code) {
          case error.PERMISSION_DENIED:
            alert("Permission denied. Please allow location access.");
            break;
          case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable.");
            break;
          case error.TIMEOUT:
            alert("The request to get user location timed out.");
            break;
          default:
            alert("An unknown error occurred.");
            break;
        }
      }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
  }
});



      // Adding markers for libraries/bookstores
      locations.forEach(location => {
        new google.maps.Marker({
          position: {
            lat: location.geometry.location.lat,
            lng: location.geometry.location.lng
          },
          map: map,
          title: location.name,
        });
      });
    
function fetchNearbyLibraries(lat, lng) {
    fetch(`/search-locations?lat=${lat}&lng=${lng}`)
        .then(response => response.json())
        .then(data => {
            // Tampilkan lokasi pada peta
            displayLocationsOnMap(data);
        })
        .catch(error => console.error('Error fetching nearby locations:', error));
}


    
  </script>

  <!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA&callback=initMap" async defer></script>
</x-layout>
