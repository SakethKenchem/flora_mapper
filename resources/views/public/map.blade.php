<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecosystem Vulnerability Map - FloraMapper</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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
                @if (auth()->user()->isAdmin())
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
                <input type="text" id="search-input" class="form-control" placeholder="Type region, county..."
                    onkeyup="filterRegions(this.value)">
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

    <!-- MAP -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('map');
            if (!mapElement || typeof L === 'undefined') return;

            // Center map on Kenya
            const kenyaCenter = [0.0236, 37.9062];
            const map = L.map(mapElement, {
                zoomControl: true
            }).setView(kenyaCenter, 5.5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        });
    </script>
    <!-- Region Details Modal -->
    <div id="region-details-modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border: 1px solid #cccccc; border-radius: 6px; width: 100%; max-width: 700px; max-height: 90%; overflow-y: auto; padding: 25px; box-sizing: border-box; position: relative; font-family: Arial, sans-serif; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <button onclick="closeRegionModal()"
                style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #666666;">&times;</button>

            <h2 id="modal-region-name"
                style="margin-top: 0; color: #1e5631; border-bottom: 2px solid #1e5631; padding-bottom: 8px;">Region
                Details</h2>

            <div
                style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; font-size: 13px; background: #f9f9f9; padding: 12px; border-radius: 4px; border: 1px solid #eeeeee;">
                <div><strong>County:</strong> <span id="modal-county"></span></div>
                <div><strong>Ecosystem Type:</strong> <span id="modal-ecosystem"></span></div>
                <div><strong>Coordinates:</strong> <span id="modal-coordinates"></span></div>
                <div><strong>Vulnerability Level:</strong> <span id="modal-vulnerability"
                        style="font-weight: bold;"></span></div>
            </div>

            <h3
                style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">
                Registered Flora Species</h3>
            <div id="modal-flora-list"
                style="font-size: 13px; margin-bottom: 20px; max-height: 120px; overflow-y: auto; background: #fafafa; border: 1px solid #e2e8f0; padding: 8px; border-radius: 4px;">
                <!-- flora list -->
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h3
                        style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">
                        Climate Records (Latest)</h3>
                    <div id="modal-climate-list" style="font-size: 12px; max-height: 150px; overflow-y: auto;">
                        <!-- climate data -->
                    </div>
                </div>
                <div>
                    <h3
                        style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">
                        Vegetation NDVI (Latest)</h3>
                    <div id="modal-vegetation-list" style="font-size: 12px; max-height: 150px; overflow-y: auto;">
                        <!-- vegetation data -->
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eeeeee; padding-top: 15px;">
                <button onclick="closeRegionModal()"
                    style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333;">Close</button>
            </div>
        </div>
    </div>

</body>

</html>
