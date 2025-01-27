<x-layout>
    <h1>Search Results for Locations</h1>

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if(count($locations) > 0)
        <ul>
            @foreach($locations as $location)
                <li>
                    <strong>{{ $location['name'] }}</strong><br>
                    Address: {{ $location['formatted_address'] }}<br>
                    Rating: {{ $location['rating'] ?? 'No rating' }}
                </li>
            @endforeach
        </ul>
    @else
        <p>No locations found.</p>
    @endif
</x-layout>