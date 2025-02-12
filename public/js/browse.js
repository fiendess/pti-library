const googleBooksAPI = "https://www.googleapis.com/books/v1/volumes";

document
    .getElementById("bookSearchForm")
    .addEventListener("submit", async (e) => {
        e.preventDefault();
        const bookTitle = document.getElementById("bookTitle").value;
        const bookResponse = await fetch(
            `${googleBooksAPI}?q=${encodeURIComponent(bookTitle)}`
        );
        const bookData = await bookResponse.json();

        if (!bookData.items || bookData.items.length === 0) {
            alert("No books found!");
            return;
        }

        displayBookDetails(bookData.items[0]);
    });

function displayBookDetails(book) {
    const bookInfo = book.volumeInfo;
    const bookResults = document.getElementById("bookResults");
    const bookId = book.id;
    const bookDetailsURL = `/books-detail/${bookId}`;

    bookResults.innerHTML = `
        <a href="${bookDetailsURL}" class="block bg-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row space-x-4 relative hover:shadow-xl transition">
          <!-- Books Image  -->
          ${
              bookInfo.imageLinks
                  ? `<img src="${bookInfo.imageLinks.thumbnail}" 
                    alt="${bookInfo.title}" 
                    class="w-40 h-60 object-contain rounded-lg mx-auto md:mx-0">`
                  : `<div class="w-40 h-60 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 mx-auto md:mx-0">
                  No Image
                </div>`
          }
          <!-- Books Info -->
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
