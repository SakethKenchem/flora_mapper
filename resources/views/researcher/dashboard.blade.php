<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Dashboard - FloraMapper</title>
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
            background: #233225;
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

        .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            margin: 20px 0 10px 0;
            font-weight: bold;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            margin-bottom: 8px;
        }

        .menu-link {
            display: block;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            font-size: 13px;
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

        .metrics-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .metric-card {
            background: white;
            border: 1px solid #dcdcdc;
            padding: 15px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .metric-label {
            font-size: 11px;
            color: #666666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .metric-value {
            font-size: 22px;
            font-weight: bold;
            color: #1e5631;
        }

        .workspace-row {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
        }

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e5631;
            margin-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #f9f9f9;
            border-bottom: 2px solid #dddddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
        }

        .status-badge {
            background: #fcf8e3;
            color: #8a6d3b;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .btn-action {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #cccccc;
            background: white;
            cursor: pointer;
            margin-right: 4px;
        }

        .btn-approve:hover {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .btn-reject:hover {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .btn-execute {
            width: 100%;
            padding: 10px;
            background: #1e5631;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            margin-top: 5px;
            text-align: center;
            text-decoration: none;
            display: block;
            box-sizing: border-box;
        }

        .btn-execute:hover {
            background: #153e22;
        }

        .console-box {
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div>
            <a href="{{ route('home') }}" class="sidebar-brand">FloraMapper</a>

            <div class="menu-label">Account</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.dashboard') }}" class="menu-link active">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
            </ul>

            <div class="menu-label">Datasets</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.climate.upload') }}" class="menu-link">Upload Climate Data</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.vegetation.upload') }}" class="menu-link">Upload NDVI Data</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.flora.upload') }}" class="menu-link">Upload Flora Data</a>
                </li>
            </ul>

            <div class="menu-label">Assessments</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.analysis') }}" class="menu-link">Run Assessment</a>
                </li>
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.create') }}" class="menu-link">Add Flora Record</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.reports') }}" class="menu-link">Reports Manager</a>
                </li>
            </ul>
        </div>

        <div class="user-panel">
            <strong>{{ Auth::user()->full_name }}</strong><br>
            <span style="font-size: 11px; color: #a3e635;">{{ Auth::user()->institution ?? 'KEFRI Researcher' }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Researcher Dashboard</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Researcher Console</span>
        </div>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="metrics-row">
            <div class="metric-card">
                <span class="metric-label">Datasets Uploaded</span>
                <span class="metric-value">{{ $datasetsCount }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Climate Records</span>
                <span class="metric-value">{{ $climateCount }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">NDVI Indexes</span>
                <span class="metric-value">{{ $vegCount }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Flora Records</span>
                <span class="metric-value">{{ $floraCount }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Assessments Run</span>
                <span class="metric-value">{{ $assessmentsCount }}</span>
            </div>
        </div>

        <div class="panel" style="margin-bottom: 25px; z-index: 1;">
            <div class="panel-title">Ecosystem Vulnerability Map</div>
            <div id="map" style="height: 380px; border-radius: 4px; border: 1px solid #cccccc;"></div>
        </div>

        <div class="workspace-row">
            <div class="panel">
                <div class="panel-title">Public Observations Queue</div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Flora Name</th>
                                <th>Region</th>
                                <th>Observer</th>
                                <th>Date Observed</th>
                                <th>Status</th>
                                <th>Action / Review Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($observations as $obs)
                            <tr>
                                <td>
                                    <strong>{{ $obs->flora_name }}</strong>
                                    @if($obs->description)
                                        <div style="font-size: 11px; color: #666666; margin-top: 2px;">{{ $obs->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $obs->location }}</td>
                                <td>{{ $obs->observer ? $obs->observer->full_name : 'Public Observer' }}</td>
                                <td>{{ $obs->date_observed ? $obs->date_observed->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    @if($obs->status === 'Pending')
                                        <span class="status-badge">Pending</span>
                                    @else
                                        <span class="status-badge" style="background: {{ $obs->status === 'Approved' ? '#d4edda; color: #155724; border: 1px solid #c3e6cb;' : '#f8d7da; color: #721c24; border: 1px solid #f5c6cb;' }}">
                                            {{ $obs->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($obs->status === 'Pending')
                                        <form method="POST" action="{{ route('researcher.observations.review', $obs->observation_id) }}" style="margin: 0;">
                                            @csrf
                                            <div style="margin-bottom: 5px;">
                                                <input type="text" name="review_comment" placeholder="Optional review comment..." style="font-size: 11px; padding: 4px; width: 100%; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 3px;">
                                            </div>
                                            <div>
                                                <button type="submit" name="status" value="Approved" class="btn-action btn-approve" style="cursor: pointer;">Approve</button>
                                                <button type="submit" name="status" value="Rejected" class="btn-action btn-reject" style="cursor: pointer;">Reject</button>
                                            </div>
                                        </form>
                                    @else
                                        <div style="font-size: 11px; color: #555555;">
                                            @if($obs->review_comment)
                                                <span style="font-style: italic;">"{{ $obs->review_comment }}"</span>
                                            @else
                                                <span style="color: #999999;">No comments</span>
                                            @endif
                                            @if($obs->reviewer)
                                                <div style="font-size: 10px; color: #888888; margin-top: 2px;">By: {{ $obs->reviewer->full_name }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #666666;">No public observation reports available in the queue.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title">Analysis Console</div>
                <div class="console-box">
                    <p>To run vulnerability modeling and classify ecosystems as Low, Moderate, or High sensitivity:</p>
                    <ol style="padding-left: 20px; margin-bottom: 15px;">
                        <li>Upload Climate dataset (CSV)</li>
                        <li>Upload NDVI dataset (CSV)</li>
                        <li>Click the button below to select region and execute</li>
                    </ol>
                    <a href="{{ route('researcher.analysis') }}" class="btn-execute">Open Evaluation Panel</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script removed as observations are processed server-side -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;
        let markers = [];

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

            // Fetch dynamic vulnerability data from the API endpoint
            fetch("{{ route('api.vulnerability_data') }}")
                .then(response => response.json())
                .then(data => {
                    renderMarkers(data);
                })
                .catch(err => {
                    console.error("Error loading vulnerability data:", err);
                });
        });

        function renderMarkers(data) {
            // Remove existing markers
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
                        <p style="margin: 0 0 8px 0; color: #555555; line-height: 1.4;">${r.interpretation}</p>
                        <button onclick="openRegionDetails(${r.region_id})" style="background: #1e5631; color: white; border: none; padding: 6px 10px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 11px; width: 100%;">View Regional Datasets</button>
                    </div>
                `;
                marker.bindPopup(popupContent);
                markers.push(marker);
            });
        }

        function openRegionDetails(regionId) {
            const modal = document.getElementById('region-details-modal');
            if (!modal) return;
            
            document.getElementById('modal-region-name').innerText = "Loading...";
            document.getElementById('modal-county').innerText = "";
            document.getElementById('modal-ecosystem').innerText = "";
            document.getElementById('modal-coordinates').innerText = "";
            document.getElementById('modal-vulnerability').innerText = "";
            document.getElementById('modal-flora-list').innerHTML = "Loading species...";
            document.getElementById('modal-climate-list').innerHTML = "Loading climate data...";
            document.getElementById('modal-vegetation-list').innerHTML = "Loading NDVI data...";
            
            modal.style.display = 'flex';

            fetch(`/api/regions/${regionId}/details`)
                .then(res => res.json())
                .then(data => {
                    const r = data.region;
                    document.getElementById('modal-region-name').innerText = r.region_name;
                    document.getElementById('modal-county').innerText = r.county;
                    document.getElementById('modal-ecosystem').innerText = r.ecosystem_type;
                    document.getElementById('modal-coordinates').innerText = `${r.latitude}, ${r.longitude}`;
                    
                    let vulnText = "Not Assessed";
                    let vulnColor = "#9ca3af";
                    if (data.assessments.length > 0) {
                       vulnText = `${data.assessments[0].vulnerability_level} (${data.assessments[0].overall_score}%)`;
                       if (data.assessments[0].vulnerability_level === 'High') vulnColor = '#ef4444';
                       else if (data.assessments[0].vulnerability_level === 'Moderate') vulnColor = '#f59e0b';
                       else if (data.assessments[0].vulnerability_level === 'Low') vulnColor = '#10b981';
                    }
                    const vulnSpan = document.getElementById('modal-vulnerability');
                    vulnSpan.innerText = vulnText;
                    vulnSpan.style.color = vulnColor;

                    let floraHtml = '<ul style="margin: 0; padding-left: 20px;">';
                    if (data.flora.length === 0) {
                        floraHtml += '<li>No registered species in this region.</li>';
                    } else {
                        data.flora.forEach(f => {
                            floraHtml += `<li><strong>${f.scientific_name}</strong> (${f.common_name || 'N/A'}) - <em>${f.conservation_status || 'Unknown status'}</em></li>`;
                        });
                    }
                    floraHtml += '</ul>';
                    document.getElementById('modal-flora-list').innerHTML = floraHtml;

                    let climateHtml = '';
                    if (data.climate.length === 0) {
                        climateHtml = '<p style="color: #666666; font-style: italic; margin: 0;">No climate records.</p>';
                    } else {
                        climateHtml = '<table style="width:100%; border-collapse:collapse; font-size:11px;"><thead><tr style="border-bottom:1px solid #ddd; text-align:left;"><th>Date</th><th>Temp</th><th>Rain</th><th>Drought</th></tr></thead><tbody>';
                        data.climate.forEach(c => {
                            climateHtml += `<tr style="border-bottom:1px solid #eee;"><td>${c.record_date}</td><td>${c.temperature_celsius}°C</td><td>${c.rainfall_mm}mm</td><td>${c.drought_index}</td></tr>`;
                        });
                        climateHtml += '</tbody></table>';
                    }
                    document.getElementById('modal-climate-list').innerHTML = climateHtml;

                    let vegHtml = '';
                    if (data.vegetation.length === 0) {
                        vegHtml = '<p style="color: #666666; font-style: italic; margin: 0;">No vegetation records.</p>';
                    } else {
                        vegHtml = '<table style="width:100%; border-collapse:collapse; font-size:11px;"><thead><tr style="border-bottom:1px solid #ddd; text-align:left;"><th>Date</th><th>NDVI</th><th>Cover</th><th>Condition</th></tr></thead><tbody>';
                        data.vegetation.forEach(v => {
                            vegHtml += `<tr style="border-bottom:1px solid #eee;"><td>${v.record_date}</td><td>${v.ndvi_value}</td><td>${v.vegetation_cover_percent}%</td><td>${v.vegetation_condition}</td></tr>`;
                        });
                        vegHtml += '</tbody></table>';
                    }
                    document.getElementById('modal-vegetation-list').innerHTML = vegHtml;
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('modal-region-name').innerText = "Error Loading Details";
                });
        }

        function closeRegionModal() {
            document.getElementById('region-details-modal').style.display = 'none';
        }
    </script>

    <!-- Region Details Modal -->
    <div id="region-details-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
        <div style="background: white; border: 1px solid #cccccc; border-radius: 6px; width: 100%; max-width: 700px; max-height: 90%; overflow-y: auto; padding: 25px; box-sizing: border-box; position: relative; font-family: Arial, sans-serif; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <button onclick="closeRegionModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #666666;">&times;</button>
            
            <h2 id="modal-region-name" style="margin-top: 0; color: #1e5631; border-bottom: 2px solid #1e5631; padding-bottom: 8px;">Region Details</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; font-size: 13px; background: #f9f9f9; padding: 12px; border-radius: 4px; border: 1px solid #eeeeee;">
                <div><strong>County:</strong> <span id="modal-county"></span></div>
                <div><strong>Ecosystem Type:</strong> <span id="modal-ecosystem"></span></div>
                <div><strong>Coordinates:</strong> <span id="modal-coordinates"></span></div>
                <div><strong>Vulnerability Level:</strong> <span id="modal-vulnerability" style="font-weight: bold;"></span></div>
            </div>

            <h3 style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">Registered Flora Species</h3>
            <div id="modal-flora-list" style="font-size: 13px; margin-bottom: 20px; max-height: 120px; overflow-y: auto; background: #fafafa; border: 1px solid #e2e8f0; padding: 8px; border-radius: 4px;">
                <!-- flora list -->
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h3 style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">Climate Records (Latest)</h3>
                    <div id="modal-climate-list" style="font-size: 12px; max-height: 150px; overflow-y: auto;">
                        <!-- climate data -->
                    </div>
                </div>
                <div>
                    <h3 style="color: #1e5631; font-size: 15px; border-bottom: 1px solid #eeeeee; padding-bottom: 4px; margin-top: 0;">Vegetation NDVI (Latest)</h3>
                    <div id="modal-vegetation-list" style="font-size: 12px; max-height: 150px; overflow-y: auto;">
                        <!-- vegetation data -->
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eeeeee; padding-top: 15px;">
                <button onclick="closeRegionModal()" style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333;">Close</button>
            </div>
        </div>
    </div>

</body>

</html>
