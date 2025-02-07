function addToUserFavorites(locationId) {
    fetch("/user/add-favorite-library", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ location_id: locationId }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("✅ " + data.message);
            } else {
                alert("❌ " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("⚠️ Error: " + error.message);
        });
}

function getFavorites() {
    fetch("/get-favorites")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayFavorites(data.favorites);
            }
        })
        .catch((error) => console.error("Error:", error));
}

function displayFavorites(favorites) {
    let container = document.getElementById("favoritesContainer");
    container.innerHTML = "";

    if (favorites.length === 0) {
        container.innerHTML =
            "<p class='text-gray-500'>Tidak ada perpustakaan favorit.</p>";
        return;
    }

    favorites.forEach((library) => {
        let div = document.createElement("div");
        div.classList.add(
            "p-4",
            "border",
            "rounded-lg",
            "shadow",
            "bg-white",
            "mb-4"
        );

        div.innerHTML = `
            <h3 class="text-lg font-semibold">${library.name}</h3>
            <p class="text-sm text-gray-600">${library.address}</p>
            <p class="text-sm text-gray-600">Rating: ⭐ ${
                library.rating ?? "N/A"
            } (${library.user_ratings_total ?? 0} reviews)</p>
            <button class="mt-2 px-4 py-2 bg-red-500 text-white rounded-md" onclick="removeFavorite('${
                library.place_id
            }')">
                Remove
            </button>
        `;

        container.appendChild(div);
    });
}

function removeFavorite(placeId) {
    fetch(`/remove-favorite/${placeId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("✅ Removed from favorites");
                getFavorites();
            } else {
                alert("❌ " + data.error);
            }
        })
        .catch((error) => console.error("Error:", error));
}

// Load favorites on page load
document.addEventListener("DOMContentLoaded", getFavorites);
