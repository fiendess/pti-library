<x-dashboard.layout>
    <x-slot:title>Books</x-slot:title>
    <h2>Books Management</h2><div class="container">
    <h2>Tambah Buku</h2>

   <form action="{{ route('books.addFromAPI') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="query" class="form-label">Search Google Books API</label>
        <input type="text" name="query" id="query" class="form-control" placeholder="Enter book title, author, or keyword" required>
    </div>
    <button type="submit" class="btn btn-primary">Search and Add Books</button>
</form>

</div>
</x-dashboard.layout>
