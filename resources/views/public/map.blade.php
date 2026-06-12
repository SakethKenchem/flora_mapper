<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecosystem Vulnerability Map - FloraMapper</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f4;
            color: #333333;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .navbar {
            background: #1e5631;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .main-container {
            flex-grow: 1;
            display: flex;
            position: relative;
        }

        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .control-panel {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: white;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .control-panel h2 {
            margin-top: 0;
            color: #1e5631;
            font-size: 18px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 13px;
        }

        .legend {
            margin-top: 15px;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }

        .legend-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #666666;
        }

        .legend-item {
            margin-bottom: 8px;
            font-size: 12px;
            display: flex;
            align-items: center;
        }

        .dot {
            height: 12px;
            width: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <a href="{{ route('home') }}" style="font-size: 20px;">Flora Vulnerability System</a>
        <div class="navbar-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('map') }}" style="border-bottom: 2px solid white; padding-bottom: 4px;">Map</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @elseif(auth()->user()->isResearcher())
                    <a href="{{ route('researcher.dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('public.dashboard') }}">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>

    <div class="main-container">
        <div class="control-panel">
            <h2>Search & Filter</h2>
            
            <div class="form-group">
                <label for="search-input">Search Region</label>
                <input type="text" id="search-input" class="form-control" placeholder="Type region, county..." onkeyup="filterRegions(this.value)">
            </div>

            <div class="legend">
                <div class="legend-title">Vulnerability Rating</div>
                <div class="legend-item">
                    <span class="dot" style="background: #ef4444;"></span>
                    <strong>High Vulnerability</strong>
                </div>
                <div class="legend-item">
                    <span class="dot" style="background: #f59e0b;"></span>
                    <strong>Moderate Vulnerability</strong>
                </div>
                <div class="legend-item">
                    <span class="dot" style="background: #10b981;"></span>
                    <strong>Low Vulnerability</strong>
                </div>
                <div class="legend-item">
                    <span class="dot" style="background: #9ca3af;"></span>
                    <strong>Not Assessed</strong>
                </div>
            </div>
        </div>

        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;
        let markers = [];
        let regionsData = [];

        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('map');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            map = L.map(mapElement).setView([-1.2921, 36.8219], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Fetch dynamic assessment data
            fetch("{{ route('api.vulnerability_data') }}")
                .then(response => response.json())
                .then(data => {
                    regionsData = data;
                    renderMarkers(data);
                })
                .catch(err => {
                    console.error("Error loading vulnerability data:", err);
                });
        });

        function renderMarkers(data) {
            // Clear existing markers
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            data.forEach(r => {
                let color = '#9ca3af'; // Not Assessed (Gray)
                if (r.vulnerability_level === 'High') {
                    color = '#ef4444'; // Red
                } else if (r.vulnerability_level === 'Moderate') {
                    color = '#f59e0b'; // Yellow/Orange
                } else if (r.vulnerability_level === 'Low') {
                    color = '#10b981'; // Green
                }

                const marker = L.circleMarker([r.latitude, r.longitude], {
                    radius: 10,
                    fillColor: color,
                    color: "#ffffff",
                    weight: 1.5,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map);

                const popupContent = `
                    <div style="font-family: Arial, sans-serif; font-size: 13px;">
                        <strong style="font-size: 14px; color: #1e5631;">${r.region_name}</strong><br>
                        <strong>County:</strong> ${r.county || 'N/A'}<br>
                        <strong>Ecosystem:</strong> ${r.ecosystem_type || 'N/A'}<br>
                        <strong>Vulnerability Level:</strong> 
                        <span style="font-weight: bold; color: ${color};">${r.vulnerability_level}</span> 
                        ${r.overall_score ? `(${r.overall_score}%)` : ''}<br><br>
                        <p style="margin: 0; color: #555555; line-height: 1.4;">${r.interpretation}</p>
                    </div>
                `;
                marker.bindPopup(popupContent);
                marker.region_name = r.region_name.toLowerCase();
                marker.county = (r.county || '').toLowerCase();
                markers.push(marker);
            });
        }

        function filterRegions(query) {
            const cleanQuery = query.trim().toLowerCase();
            markers.forEach(marker => {
                if (marker.region_name.includes(cleanQuery) || marker.county.includes(cleanQuery)) {
                    marker.setStyle({ opacity: 1, fillOpacity: 0.8 });
                } else {
                    marker.setStyle({ opacity: 0.1, fillOpacity: 0.1 });
                }
            });
        }
    </script>

</body>

</html>