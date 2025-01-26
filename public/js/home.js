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

document.getElementById("searchNearby").addEventListener("click", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
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
                    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                });

                // Search nearby libraries and bookstores
                const request = {
                    location: pos,
                    radius: 4000, // 4 km radius
                    type: ["library", "book_store"],
                };

                service = new google.maps.places.PlacesService(map);
                service.nearbySearch(request, (results, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        displayLocationsOnMap(results);
                        updateLocationList(results);
                    } else {
                        alert(
                            "No nearby locations found or an error occurred."
                        );
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

function updateLocationList(locations) {
    const resultsContainer = document.getElementById("locations-results");
    resultsContainer.innerHTML = ""; // Clear previous results

    const locationsList = document.createElement("ul");
    locations.forEach((location) => {
        const li = document.createElement("li");
        li.className = "mb-4 p-4 bg-gray-100 rounded-lg";
        li.innerHTML = `
          <h3 class="text-xl font-semibold">${location.name}</h3>
          <p>${location.vicinity}</p>
          <p>Rating: ${location.rating || "No rating"}</p>
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
    let errorMessage = "";
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
