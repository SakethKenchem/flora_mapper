<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Dashboard - FloraMapper</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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

        .menu-link:hover,
        .menu-link.active {
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
                    <a href="#" onclick="alert('Search functionality coming soon!')" class="menu-link">Search
                        Region</a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="openSubmitModal(event)" class="menu-link">Submit Observation</a>
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
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">General
                Public</span>
        </div>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px; font-size: 13px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                        <div class="metric-val">{{ $myObservationsCount }}</div>
                    </div>

                    <a href="{{ route('map') }}" class="btn-action">Explore Interactive Map</a>
                    <a href="#" onclick="openSubmitModal(event)" class="btn-action" style="background: white; color: #1e5631;">Submit Observation</a>
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

        <!-- Submitted Observations History Log -->
        <div class="panel" style="margin-top: 20px;">
            <div class="panel-title">My Submitted Observations Queue</div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #dddddd; background: #f9f9f9; text-align: left;">
                            <th style="padding: 10px; font-weight: bold; width: 25%;">Flora Name</th>
                            <th style="padding: 10px; font-weight: bold; width: 25%;">Location</th>
                            <th style="padding: 10px; font-weight: bold; width: 15%;">Date Observed</th>
                            <th style="padding: 10px; font-weight: bold; width: 12%;">Status</th>
                            <th style="padding: 10px; font-weight: bold;">Reviewer / Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myObservations as $obs)
                            <tr style="border-bottom: 1px solid #eeeeee;">
                                <td style="padding: 10px; font-weight: bold; color: #1e5631;">{{ $obs->flora_name }}</td>
                                <td style="padding: 10px;">{{ $obs->location }}</td>
                                <td style="padding: 10px;">{{ $obs->date_observed ? $obs->date_observed->format('Y-m-d') : 'N/A' }}</td>
                                <td style="padding: 10px;">
                                    <span style="font-weight: bold; padding: 2px 6px; border-radius: 4px; font-size: 11px; display: inline-block; text-align: center;
                                        @if ($obs->status === 'Approved')
                                            color: #155724; background: #d4edda; border: 1px solid #c3e6cb;
                                        @elseif ($obs->status === 'Rejected')
                                            color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb;
                                        @else
                                            color: #8a6d3b; background: #fcf8e3; border: 1px solid #faf2cc;
                                        @endif
                                    ">
                                        {{ $obs->status }}
                                    </span>
                                </td>
                                <td style="padding: 10px; color: #555;">
                                    @if($obs->status !== 'Pending')
                                        <strong>{{ $obs->reviewer ? $obs->reviewer->full_name : 'Researcher' }}:</strong>
                                        <span style="font-style: italic;">"{{ $obs->review_comment ?? 'No comment provided' }}"</span>
                                    @else
                                        <span style="color: #999;">Awaiting review</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 20px; text-align: center; color: #666;">You haven't submitted any observation reports yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

                        marker.bindPopup(
                            `<strong>${r.region_name}</strong><br>Vulnerability: ${r.vulnerability_level} ${r.overall_score ? `(${r.overall_score}%)` : ''}`
                            );
                    });
                })
                .catch(err => console.error("Error loading vulnerability data:", err));

            setTimeout(() => {
                dashboardMap.invalidateSize();
            }, 200);
        });
    </script>

    <!-- Submit Observation Modal -->
    <div id="submit-observation-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box; backdrop-filter: blur(4px);">
        <div style="background: white; border-radius: 8px; width: 100%; max-width: 600px; max-height: 90%; overflow-y: auto; padding: 25px; box-sizing: border-box; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.2); font-family: Arial, sans-serif;">
            <button type="button" onclick="closeSubmitModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; font-weight: bold; cursor: pointer; color: #666666; transition: color 0.2s; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">&times;</button>
            
            <h2 style="margin-top: 0; color: #1e5631; border-bottom: 2px solid #1e5631; padding-bottom: 10px; font-size: 20px; font-weight: bold;">Submit Observation Report</h2>
            
            <form action="{{ route('public.observations.submit') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                @csrf
                
                <div>
                    <label for="flora_id" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Registered Species (Optional)</label>
                    <select id="flora_id" name="flora_id" onchange="toggleCustomFlora(this)" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; background: #fff; height: 35px;">
                        <option value="">-- Select Registered Species (or enter custom below) --</option>
                        @foreach($registeredFlora as $flora)
                            <option value="{{ $flora->flora_id }}">{{ $flora->scientific_name }} ({{ $flora->common_name ?? 'No common name' }})</option>
                        @endforeach
                        <option value="custom">Other / Custom Species (Type manually)</option>
                    </select>
                </div>
                
                <div id="custom-flora-group">
                    <label for="flora_name_custom" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Flora Species Name <span style="color: red;">*</span></label>
                    <input type="text" id="flora_name_custom" name="flora_name_custom" placeholder="e.g. Ficus sycomorus" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label for="location" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Location / Region <span style="color: red;">*</span></label>
                        <input type="text" id="location" name="location" placeholder="e.g. Mau Forest Complex Block B" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;" required>
                    </div>
                    <div>
                        <label for="date_observed" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Date Observed <span style="color: red;">*</span></label>
                        <input type="date" id="date_observed" name="date_observed" max="{{ date('Y-m-d') }}" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;" required>
                    </div>
                </div>
                
                <div>
                    <label for="description" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Text Observations & Details <span style="color: red;">*</span></label>
                    <textarea id="description" name="description" placeholder="Describe the health status, canopy density, soil conditions, tree damage, or other text observations..." rows="4" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; resize: vertical;" required></textarea>
                </div>

                <!-- Quantitative Metrics (Climate & Vegetation) -->
                <div style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 5px;">
                    <h4 style="margin: 0 0 10px 0; color: #1e5631; font-size: 14px; font-weight: bold;">Quantitative Field Metrics (Optional)</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label for="temperature_celsius" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Temperature (°C)</label>
                            <input type="number" step="0.1" min="-10" max="60" id="temperature_celsius" name="temperature_celsius" placeholder="e.g. 24.5" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                        <div>
                            <label for="rainfall_mm" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Rainfall (mm)</label>
                            <input type="number" step="0.1" min="0" max="5000" id="rainfall_mm" name="rainfall_mm" placeholder="e.g. 150.2" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label for="humidity_percent" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Humidity (%)</label>
                            <input type="number" step="0.1" min="0" max="100" id="humidity_percent" name="humidity_percent" placeholder="e.g. 65.0" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                        <div>
                            <label for="drought_index" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Drought Index</label>
                            <input type="number" step="0.1" min="0" max="10" id="drought_index" name="drought_index" placeholder="e.g. 2.5 (0-10 scale)" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                        <div>
                            <label for="ndvi_value" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">NDVI (-1.0 to 1.0)</label>
                            <input type="number" step="0.001" min="-1" max="1" id="ndvi_value" name="ndvi_value" placeholder="e.g. 0.452" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                        <div>
                            <label for="vegetation_cover_percent" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Veg Cover (%)</label>
                            <input type="number" step="0.1" min="0" max="100" id="vegetation_cover_percent" name="vegetation_cover_percent" placeholder="e.g. 75.5" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; height: 35px;">
                        </div>
                        <div>
                            <label for="vegetation_condition" style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333;">Veg Condition</label>
                            <select id="vegetation_condition" name="vegetation_condition" style="width: 100%; padding: 8px; border: 1px solid #cccccc; border-radius: 4px; font-size: 13px; box-sizing: border-box; background: #fff; height: 35px;">
                                <option value="">-- Select --</option>
                                <option value="Healthy">Healthy</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Stressed">Stressed</option>
                                <option value="Degraded">Degraded</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label for="image_file" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Supporting Image <span style="color: red;">*</span></label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" style="width: 100%; font-size: 12px;" required>
                        <small style="color: #666; font-size: 11px; display: block; margin-top: 3px;">PNG, JPG, JPEG up to 4MB</small>
                    </div>
                    <div>
                        <label for="csv_file" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333;">Supporting CSV Data <span style="color: red;">*</span></label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" style="width: 100%; font-size: 12px;" required>
                        <small style="color: #666; font-size: 11px; display: block; margin-top: 3px;">CSV file with scientific details</small>
                    </div>
                </div>
                
                <div style="margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 15px;">
                    <button type="button" onclick="closeSubmitModal()" style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333; transition: background 0.2s; height: 35px;">Cancel</button>
                    <button type="submit" style="background: #1e5631; border: 1px solid #1e5631; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: white; transition: background 0.2s; height: 35px;">Submit Report</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSubmitModal(event) {
            if(event) event.preventDefault();
            document.getElementById('submit-observation-modal').style.display = 'flex';
        }

        function closeSubmitModal() {
            document.getElementById('submit-observation-modal').style.display = 'none';
        }

        function toggleCustomFlora(selectElement) {
            const customGroup = document.getElementById('custom-flora-group');
            const customInput = document.getElementById('flora_name_custom');
            
            if (selectElement.value === 'custom' || selectElement.value === '') {
                customGroup.style.display = 'block';
                customInput.required = true;
            } else {
                customGroup.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        }
    </script>

</body>

</html>
