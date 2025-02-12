let map;
let service;
const bandungCenter = { lat: -6.9147, lng: 107.6098 };
let userLocation = bandungCenter;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: bandungCenter,
        zoom: 12,
    });
}

function fetchDetailedInfo(locations) {
    const resultsContainer = document.getElementById("locations-results");
    resultsContainer.innerHTML = "";

    const locationsList = document.createElement("ul");

    locations.forEach((location) => {
        const request = { placeId: location.place_id };

        service.getDetails(request, (place, status) => {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                const distance =
                    google.maps.geometry.spherical.computeDistanceBetween(
                        new google.maps.LatLng(
                            userLocation.lat,
                            userLocation.lng
                        ),
                        place.geometry.location
                    );

                const distanceKm = (distance / 1000).toFixed(1);
                const contact = place.formatted_phone_number || "Not Available";
                const openingHours =
                    place.opening_hours?.weekday_text?.join(", ") ||
                    "Not Available";
                const website = place.website || "Not Available";
                const type = place.types ? place.types[0] : "Library";

                const li = document.createElement("li");
                li.className =
                    "flex flex-col sm:flex-row items-start space-x-4 mb-4 p-4 bg-gray-100 rounded-lg relative";
                li.innerHTML = `
                  ${
                      place.photos
                          ? `<img src="${place.photos[0].getUrl({
                                maxWidth: 120,
                            })}" alt="${place.name}" 
                         class="w-32 h-32 object-cover rounded-lg" />`
                          : `<div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No Image</div>`
                  }

                  <div class="flex-1">
                    <h3 class="text-xl font-semibold">${place.name}</h3>
                    <p>${place.formatted_address || "Address not available"}</p>
                    <p>Rating: ${place.rating || "No rating"}</p>
                    <p>Contact: ${contact}</p>
                    <p>Opening Hours: ${openingHours}</p>
                    <p>Website: <a href="${website}" target="_blank">${website}</a></p>

                    <div class="flex items-center space-x-2 mt-2">
                        <p>${distanceKm} kilometers from your current location.</p>
                    </div>

                    <div class="flex space-x-2 mt-2">
                        <button onclick="openGoogleMaps(${place.geometry.location.lat()}, ${place.geometry.location.lng()})" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md">
                            Get Directions
                        </button>

                        <button onclick="viewOnMap(${place.geometry.location.lat()}, ${place.geometry.location.lng()}, '${
                    place.name
                }')" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md">
                            View on Map
                        </button>
                    </div>
                  </div>
                `;

                locationsList.appendChild(li);
            }
        });
    });
    resultsContainer.appendChild(locationsList);
}

function openGoogleMaps(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, "_blank");
}

document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchInput = document.getElementById("locationInput");
    const resultsContainer = document.getElementById("locations-results-form");

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let location = searchInput.value.trim();
        if (!location) return;

        const request = {
            query: location,
            fields: ["place_id", "name", "formatted_address", "geometry"],
        };

        service = new google.maps.places.PlacesService(map);
        service.textSearch(request, (results, status) => {
            resultsContainer.innerHTML = "";

            if (
                status !== google.maps.places.PlacesServiceStatus.OK ||
                !results.length
            ) {
                resultsContainer.innerHTML = `<p class="text-red-500">No locations found.</p>`;
                return;
            }

            const locationsList = document.createElement("ul");
            locationsList.className = "space-y-4";

            results.forEach((place) => {
                const requestDetails = { placeId: place.place_id };

                service.getDetails(requestDetails, (placeDetails, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        const contact =
                            placeDetails.formatted_phone_number ||
                            "Not Available";
                        const openingHours =
                            placeDetails.opening_hours?.weekday_text?.join(
                                ", "
                            ) || "Not Available";
                        const website = placeDetails.website || "Not Available";
                        const type = placeDetails.types
                            ? placeDetails.types[0]
                            : "Library";

                        const li = document.createElement("li");
                        li.className =
                            "flex flex-col sm:flex-row items-start space-x-4 p-4 bg-gray-100 rounded-lg shadow-md";
                        li.innerHTML = `
                            ${
                                placeDetails.photos
                                    ? `<img src="${placeDetails.photos[0].getUrl(
                                          { maxWidth: 120 }
                                      )}" 
                                    alt="${
                                        placeDetails.name
                                    }" class="w-32 h-32 object-cover rounded-lg" />`
                                    : `<div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No Image</div>`
                            }
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold">${
                                    placeDetails.name
                                }</h3>
                                <p>${
                                    placeDetails.formatted_address ||
                                    "Address not available"
                                }</p>
                                <p>Rating: ${
                                    placeDetails.rating || "No rating"
                                }</p>
                                <p>Contact: ${contact}</p>
                                <p>Opening Hours: ${openingHours}</p>
                                <p>Website: <a href="${website}" target="_blank">${website}</a></p>

                                <button onclick="openGoogleMaps(${placeDetails.geometry.location.lat()}, ${placeDetails.geometry.location.lng()})" 
                                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">
                                    Get Directions
                                </button>

                                   <button onclick="viewOnMap(${place.geometry.location.lat()}, ${place.geometry.location.lng()}, '${
                            place.name
                        }')" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md">
                            View on Map
                        </button>
                            </div>
                        `;

                        locationsList.appendChild(li);
                    }
                });
            });
            resultsContainer.appendChild(locationsList);
        });
    });
});

function viewOnMap(lat, lng, name) {
    const location = new google.maps.LatLng(lat, lng);

    map.setCenter(location);
    map.setZoom(15);

    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: name,
        animation: google.maps.Animation.DROP,
    });

    const infoWindow = new google.maps.InfoWindow({
        content: `<strong>${name}</strong><br><p>Lat: ${lat}, Lng: ${lng}</p>`,
    });

    marker.addListener("click", () => {
        infoWindow.open(map, marker);
    });

    infoWindow.open(map, marker);
}
