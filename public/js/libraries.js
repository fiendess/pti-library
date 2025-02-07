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

                    <button onclick="openGoogleMaps(${place.geometry.location.lat()}, ${place.geometry.location.lng()})" 
                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">
                        Get Directions
                    </button>

                    <button onclick="addToFavorites('${place.name}', '${
                    place.formatted_address
                }', ${place.geometry.location.lat()}, ${place.geometry.location.lng()}, '${contact}', '${openingHours}', '${website}', '${type}')" 
                            class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md">
                        Add to Favorites
                    </button>
                  </div>
                `;

                locationsList.appendChild(li);
            }
        });
    });

    resultsContainer.appendChild(locationsList);
}

// Fungsi untuk menambahkan lokasi ke favorit (tabel 'location')
function addToFavorites(
    name,
    address,
    lat,
    lng,
    contact,
    openingHours,
    website,
    type
) {
    console.log("Sending to backend:", {
        name,
        address,
        lat,
        lng,
        contact,
        openingHours,
        website,
        type,
    });

    fetch("/add-to-favorites", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            name: name,
            address: address,
            latitude: lat,
            longitude: lng,
            contact_number: contact || "Not Available",
            opening_hours: openingHours || "Not Available",
            website: website || "Not Available",
            type: type || "Library",
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Response from backend:", data);
            alert(data.message);
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Error: " + error.message);
        });
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
        event.preventDefault(); // Mencegah reload halaman

        let location = searchInput.value.trim();
        if (!location) return;

        fetch(`/search-locations?location=${encodeURIComponent(location)}`)
            .then((response) => response.json())
            .then((data) => {
                resultsContainer.innerHTML = "";
                if (data.error) {
                    resultsContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    return;
                }

                const locationsList = document.createElement("ul");
                locationsList.className = "space-y-4";

                data.forEach((place) => {
                    const li = document.createElement("li");
                    li.className =
                        "flex flex-col sm:flex-row items-start space-x-4 p-4 bg-gray-100 rounded-lg shadow-md";
                    li.innerHTML = `
                        ${
                            place.photos
                                ? `<img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=${place.photos[0].photo_reference}&key=AIzaSyBiSRGSp74RDmzNbf9fJUGzg6iNOu8oVQA" 
                                 alt="${place.name}" class="w-32 h-32 object-cover rounded-lg" />`
                                : `<div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No Image</div>`
                        }
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">${place.name}</h3>
                            <p>${
                                place.formatted_address ||
                                "Address not available"
                            }</p>
                            <p>Rating: ${place.rating || "No rating"}</p>
                           <button onclick="openGoogleMaps(${
                               place.geometry.location.lat
                           }, ${place.geometry.location.lng})"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">
                        Get Directions
                        </button>

                    <button onclick="addToFavorites('${place.place_id}')"
                            class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md">
                    Add to Favorites
                    </button>

                        </div>
                    `;

                    locationsList.appendChild(li);
                });

                resultsContainer.appendChild(locationsList);
            })
            .catch((error) => {
                resultsContainer.innerHTML = `<p class="text-red-500">Error fetching data. Try again later.</p>`;
                console.error("Error fetching locations:", error);
            });
    });
});
