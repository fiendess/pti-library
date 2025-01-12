<x-dashboard.layout>
    <x-slot:title>Books</x-slot:title>
    <h2>Books Management</h2><div class="container">
    <h2>Tambah Buku</h2>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <div>
            <label>Judul</label>
            <input type="text" name="title" value="{{ $booksData['volumeInfo']['title'] ?? '' }}" required>
        </div>

        <div>
            <label>Penulis</label>
            <input type="text" name="author" value="{{ implode(', ', $booksData['volumeInfo']['authors'] ?? []) }}" required>
        </div>

        <div>
            <label>Deskripsi</label>
            <textarea name="description">{{ $booksData['volumeInfo']['description'] ?? '' }}</textarea>
        </div>

        <div>
            <label>Penerbit</label>
            <input type="text" name="publisher" value="{{ $booksData['volumeInfo']['publisher'] ?? '' }}">
        </div>

        <div>
            <label>Tanggal Terbit</label>
            <input type="date" name="published_date" value="{{ $booksData['volumeInfo']['publishedDate'] ?? '' }}">
        </div>

        <div>
            <label>ISBN</label>
            <input type="text" name="isbn" value="{{ $booksData['volumeInfo']['industryIdentifiers'][0]['identifier'] ?? '' }}">
        </div>

        <div>
            <label>Cover Image URL</label>
            <input type="text" name="cover_image" value="{{ $booksData['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
        </div>

        <div>
            <label>Kategori</label>
            <input type="text" name="category" value="{{ implode(', ', $booksData['volumeInfo']['categories'] ?? []) }}">
        </div>

        <div>
            <label>Jumlah Stok</label>
            <input type="number" name="quantity" value="0" min="0" required>
        </div>

        <button type="submit">Simpan Buku</button>
    </form>
</div>
</x-dashboard.layout>
