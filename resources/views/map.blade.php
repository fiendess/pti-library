<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Locator</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
    <style>
        #map {
            height: 100%;
            width: 100%;
        }
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>
<body>

    <h1>Find Libraries Near You</h1>

    <div id="map"></div>

    <script>
        let map;
        let userMarker;
        let libraryMarkers = [];

        // Fungsi untuk mendapatkan lokasi pengguna dan menampilkan peta
        function initMap() {
            // Cek jika geolocation tersedia
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // Inisialisasi peta di lokasi pengguna
                    const userLocation = { lat: userLat, lng: userLng };
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 12,
                        center: userLocation
                    });

                    // Menandai lokasi pengguna
                    userMarker = new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "You are here"
                    });

                    // Cari perpustakaan terdekat
                    findLibraries(userLocation);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Fungsi untuk mencari perpustakaan terdekat
        function findLibraries(userLocation) {
            const service = new google.maps.places.PlacesService(map);
            const request = {
                location: userLocation,
                radius: 5000,  // Radius pencarian dalam meter (5 km)
                type: ['library']  // Pencarian jenis tempat 'library'
            };

            service.nearbySearch(request, function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    results.forEach(function(place) {
                        const marker = new google.maps.Marker({
                            position: place.geometry.location,
                            map: map,
                            title: place.name
                        });
                        libraryMarkers.push(marker);
                    });
                } else {
                    alert("No libraries found nearby.");
                }
            });
        }

        
    </script>

</body>
</html>
