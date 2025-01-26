let map;
let service;
let markers = [];
const googleBooksAPI = "https://www.googleapis.com/books/v1/volumes";

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -6.9147, lng: 107.6098 },
        zoom: 12,
    });
}

// Book Search Handler
document
    .getElementById("bookSearchForm")
    .addEventListener("submit", async (e) => {
        e.preventDefault();
        const bookTitle = document.getElementById("bookTitle").value;

        try {
            // 1. Search Book using Google Books API
            const bookResponse = await fetch(
                `${googleBooksAPI}?q=${encodeURIComponent(bookTitle)}`
            );
            const bookData = await bookResponse.json();

            if (!bookData.items || bookData.items.length === 0) {
                alert("No books found!");
                return;
            }

            // Display Book Details
            displayBookDetails(bookData.items[0]);

            // 2. Search Libraries with Place API
            searchLibrariesNearby(bookData.items[0]);
        } catch (error) {
            console.error("Error:", error);
            alert("Error searching for books");
        }
    });

function displayBookDetails(book) {
    const bookInfo = book.volumeInfo;
    const bookResults = document.getElementById("bookResults");

    // Misalkan ID buku ada di book.id, sesuaikan dengan struktur data Anda
    const bookId = book.id; // ID Buku dari API atau database Anda

    // Tentukan URL untuk halaman detail buku
    const bookDetailsURL = `/books-detail/${bookId}`;

    bookResults.innerHTML = `
        <a href="${bookDetailsURL}" class="block bg-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row space-x-4 relative hover:shadow-xl transition">
          <!-- Gambar Buku -->
          ${
              bookInfo.imageLinks
                  ? `<img src="${bookInfo.imageLinks.thumbnail}" 
                    alt="${bookInfo.title}" 
                    class="w-40 h-60 object-contain rounded-lg mx-auto md:mx-0">`
                  : `<div class="w-40 h-60 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 mx-auto md:mx-0">
                  No Image
                </div>`
          }

          <!-- Informasi Buku -->
          <div class="flex-1">
            <h2 class="text-2xl font-bold mb-4">${bookInfo.title}</h2>
            <ul class="space-y-2">
              ${
                  bookInfo.authors
                      ? `<li class="text-gray-600"><strong>Author:</strong> ${bookInfo.authors.join(
                            ", "
                        )}</li>`
                      : ""
              }
              ${
                  bookInfo.publishedDate
                      ? `<li class="text-gray-600"><strong>Published:</strong> ${bookInfo.publishedDate}</li>`
                      : ""
              }
              ${
                  bookInfo.industryIdentifiers
                      ? `<li class="text-gray-600"><strong>ISBN:</strong> ${bookInfo.industryIdentifiers
                            .map((id) => `${id.type} ${id.identifier}`)
                            .join(", ")}</li>`
                      : ""
              }
            </ul>
          </div>
        </a>
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
            lng: position.coords.longitude,
        };

        // Configure Places API request
        const request = {
            location: pos,
            radius: 8000,
            type: ["library", "book_store"],
            keyword: `"${book.volumeInfo.title}" book`, // Search libraries with book title
        };

        // Search libraries
        service = new google.maps.places.PlacesService(map);
        service.nearbySearch(request, (results, status) => {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                updateMapAndList(results, book);
            } else {
                alert("No libraries found with this book");
            }
        });
    } catch (error) {
        handleLocationError(error);
    }
}

function updateMapAndList(libraries, book) {
    // Clear previous markers
    markers.forEach((marker) => marker.setMap(null));
    markers = [];

    // Update Map
    libraries.forEach((library) => {
        const marker = new google.maps.Marker({
            position: library.geometry.location,
            map: map,
            title: `${library.name} - ${book.volumeInfo.title}`,
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
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
          `,
        });

        marker.addListener("click", () => infowindow.open(map, marker));
        markers.push(marker);
    });

    // Update List
    const resultsContainer = document.getElementById("locations-results");
    resultsContainer.innerHTML = libraries
        .map(
            (library) => `
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
      `
        )
        .join("");
}

function handleLocationError(error) {
    // ... (same error handling as previous code)
}
