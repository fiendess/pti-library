document.getElementById("title").addEventListener("input", function (e) {
    let title = e.target.value;

    if (title) {
        fetch(`https://api.example.com/getBookDetails?title=${title}`)
            .then((response) => response.json())
            .then((data) => {
                if (data) {
                    // Mengisi form dengan data dari API
                    document.getElementById("author").value = data.author || "";
                    document.getElementById("isbn").value = data.isbn || "";
                    document.getElementById("publisher").value =
                        data.publisher || "";
                    document.getElementById("published_date").value =
                        data.published_date || "";
                    document.getElementById("cover_image").value =
                        data.cover_image || "";
                    document.getElementById("description").value =
                        data.description || "";
                }
            })
            .catch((error) =>
                console.error("Error fetching book data:", error)
            );
    }

    document
        .getElementById("cover_image")
        .addEventListener("input", function (e) {
            let coverImageUrl = e.target.value;
            if (coverImageUrl) {
                document.getElementById("cover_image_preview").src =
                    coverImageUrl;
            }
        });
});
