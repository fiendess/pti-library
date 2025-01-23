<!-- resources/views/locations/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>
</head>
<body>
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
</body>
</html>
