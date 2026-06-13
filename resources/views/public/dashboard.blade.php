<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Dashboard - FloraMapper</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f4;
            color: #333333;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #2e3d30;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
        }

        .sidebar-brand {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            display: block;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }

        .menu-item {
            margin-bottom: 10px;
        }

        .menu-link {
            display: block;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            font-size: 14px;
        }

        .menu-link:hover, .menu-link.active {
            background: rgba(255, 255, 255, 0.15);
        }

        .user-panel {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
        }

        .btn-logout {
            width: 100%;
            background: #a94442;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }

        .btn-logout:hover {
            background: #843534;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dcdcdc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1e5631;
            font-size: 24px;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .dashboard-body {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            flex-grow: 1;
        }

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .panel-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e5631;
            margin-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 8px;
        }

        #dashboard-kenya-map {
            width: 100%;
            height: 400px;
            border-radius: 4px;
            border: 1px solid #cccccc;
        }

        .metric-box {
            background: #fcfcfc;
            border: 1px solid #e5e5e5;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 15px;
        }

        .metric-val {
            font-size: 24px;
            font-weight: bold;
            color: #1e5631;
        }

        .btn-action {
            display: block;
            text-align: center;
            background: #1e5631;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
            border: 1px solid #1e5631;
        }

        .btn-action:hover {
            background: #153e22;
        }

        .legend-item {
            margin-bottom: 10px;
            font-size: 13px;
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

    <div class="sidebar">
        <div>
            <a href="{{ route('home') }}" class="sidebar-brand">FloraMapper</a>

            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('public.dashboard') }}" class="menu-link active">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="alert('Map filtering is active on the main map page!')" class="menu-link">Search Region</a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="alert('Observation submission form is under development!')" class="menu-link">Submit Observation</a>
                </li>
            </ul>
        </div>

        <div class="user-panel">
            <strong>{{ Auth::user()->full_name }}</strong><br>
            <span>Observer Account</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Public observer dashboard</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">General Public</span>
        </div>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-body">
            <div class="panel">
                <div class="panel-title">Regional Flora Vulnerability Map</div>
                <div id="dashboard-kenya-map"></div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="panel">
                    <div class="panel-title">My Observations</div>
                    <div class="metric-box">
                        <div style="font-size: 12px; color: #666666;">Total Submitted Reports</div>
                        <div class="metric-val">0</div>
                    </div>

                    <a href="{{ route('map') }}" class="btn-action">Explore Interactive Map</a>
                    <a href="#" onclick="alert('Submission form coming soon!')" class="btn-action" style="background: white; color: #1e5631;">Submit Observation</a>
                </div>

                <div class="panel">
                    <div class="panel-title">Vulnerability Legend</div>
                    <div class="legend-item">
                        <span class="dot" style="background: #ef4444;"></span>
                        <strong>High Vulnerability</strong> (Score 61-100)
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background: #f59e0b;"></span>
                        <strong>Moderate Vulnerability</strong> (Score 31-60)
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background: #10b981;"></span>
                        <strong>Low Vulnerability</strong> (Score 0-30)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('dashboard-kenya-map');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            const dashboardMap = L.map(mapElement).setView([-1.2921, 36.8219], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(dashboardMap);

            // Fetch dynamic assessment data
            fetch("{{ route('api.vulnerability_data') }}")
                .then(response => response.json())
                .then(data => {
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
                            radius: 8,
                            fillColor: color,
                            color: "#ffffff",
                            weight: 1.5,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(dashboardMap);

                        marker.bindPopup(`<strong>${r.region_name}</strong><br>Vulnerability: ${r.vulnerability_level} ${r.overall_score ? `(${r.overall_score}%)` : ''}`);
                    });
                })
                .catch(err => console.error("Error loading vulnerability data:", err));

            setTimeout(() => {
                dashboardMap.invalidateSize();
            }, 200);
        });
    </script>

</body>

</html>