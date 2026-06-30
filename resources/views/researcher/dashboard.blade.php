<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Dashboard - FloraMapper</title>
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
                    <a href="{{ route('researcher.dashboard') }}" class="menu-link{{ request()->routeIs('researcher.dashboard') ? ' active' : '' }}">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link{{ request()->routeIs('account') ? ' active' : '' }}">My Account</a>
                </li>
            </ul>

            <div class="menu-label">Datasets</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.climate.upload') }}" class="menu-link{{ request()->routeIs('researcher.datasets.climate.upload') ? ' active' : '' }}">Upload Climate Data</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.vegetation.upload') }}" class="menu-link{{ request()->routeIs('researcher.datasets.vegetation.upload') ? ' active' : '' }}">Upload NDVI Data</a>
                </li>
            </ul>

            <div class="menu-label">Assessments</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.analysis') }}" class="menu-link{{ request()->routeIs('researcher.analysis') ? ' active' : '' }}">Run Assessment</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.compare') }}" class="menu-link{{ request()->routeIs('researcher.compare') ? ' active' : '' }}">Compare Regions</a>
                </li>
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.manage') }}" class="menu-link{{ request()->routeIs('researcher.flora.manage') ? ' active' : '' }}">Flora Registry</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.reports') }}" class="menu-link{{ request()->routeIs('researcher.reports') ? ' active' : '' }}">Reports Manager</a>
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
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Researcher
                Console</span>
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
                 
                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 10px; flex-wrap: wrap;">
                     <div style="display: flex; gap: 10px; flex-grow: 1; max-width: 70%;">
                         <input type="text" id="obs-search" placeholder="Search observer, flora, region..." onkeyup="filterObservationsTable()" style="padding: 8px; border-radius: 4px; border: 1px solid #cccccc; font-size: 13px; flex-grow: 1; max-width: 250px;">
                         
                         <select id="obs-status-filter" onchange="filterObservationsTable()" style="padding: 8px; border-radius: 4px; border: 1px solid #cccccc; font-size: 13px; background: white;">
                             <option value="all">All Statuses</option>
                             <option value="Pending">Pending</option>
                             <option value="Approved">Approved</option>
                             <option value="Rejected">Rejected</option>
                         </select>

                         <select id="obs-level-filter" onchange="filterObservationsTable()" style="padding: 8px; border-radius: 4px; border: 1px solid #cccccc; font-size: 13px; background: white;">
                             <option value="all">All Vulnerabilities</option>
                             <option value="High">High</option>
                             <option value="Moderate">Moderate</option>
                             <option value="Low">Low</option>
                             <option value="Not Assessed">Not Assessed</option>
                         </select>
                     </div>
                     <a href="{{ route('researcher.export.observations') }}" class="btn-action" style="background: #1e5631; color: white; border: 1px solid #1e5631; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-weight: bold; font-size: 11px; display: inline-flex; align-items: center; height: 32px; box-sizing: border-box; margin-right: 0;">Export Queue (CSV)</a>
                 </div>

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
                                <tr class="obs-row" data-status="{{ $obs->status }}" data-level="{{ $obs->flora ? $obs->flora->vulnerability_level : 'Not Assessed' }}">
                                    <td>
                                        <strong class="obs-flora-name">{{ $obs->flora_name }}</strong>
                                        @if ($obs->description)
                                            <div style="font-size: 11px; color: #666666; margin-top: 2px;">
                                                {{ $obs->description }}</div>
                                        @endif
                                    </td>
                                    <td class="obs-location">{{ $obs->location }}</td>
                                    <td class="obs-observer">{{ $obs->observer ? $obs->observer->full_name : 'Public Observer' }}</td>
                                    <td>{{ $obs->date_observed ? $obs->date_observed->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        @if ($obs->status === 'Pending')
                                            <span class="status-badge">Pending</span>
                                        @else
                                            <span class="status-badge"
                                                style="background: {{ $obs->status === 'Approved' ? '#d4edda; color: #155724; border: 1px solid #c3e6cb;' : '#f8d7da; color: #721c24; border: 1px solid #f5c6cb;' }}">
                                                {{ $obs->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($obs->status === 'Pending')
                                            <form method="POST"
                                                action="{{ route('researcher.observations.review', $obs->observation_id) }}"
                                                style="margin: 0;">
                                                @csrf
                                                <div style="margin-bottom: 5px;">
                                                    <input type="text" name="review_comment"
                                                        placeholder="Optional review comment..."
                                                        style="font-size: 11px; padding: 4px; width: 100%; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 3px;">
                                                </div>
                                                <div style="display: flex; gap: 4px;">
                                                    <button type="submit" name="status" value="Approved"
                                                        class="btn-action btn-approve"
                                                        style="cursor: pointer; flex-grow: 1;">Approve</button>
                                                    <button type="submit" name="status" value="Rejected"
                                                        class="btn-action btn-reject"
                                                        style="cursor: pointer; flex-grow: 1;">Reject</button>
                                                </div>
                                            </form>
                                        @else
                                            <div style="font-size: 11px; color: #555555; margin-bottom: 5px;">
                                                @if ($obs->review_comment)
                                                    <span
                                                        style="font-style: italic;">"{{ $obs->review_comment }}"</span>
                                                @else
                                                    <span style="color: #999999;">No comments</span>
                                                @endif
                                                @if ($obs->reviewer)
                                                    <div style="font-size: 10px; color: #888888; margin-top: 2px;">By:
                                                        {{ $obs->reviewer->full_name }}</div>
                                                @endif
                                            </div>
                                        @endif
                                        <button type="button"
                                            onclick="openObservationModal({{ $obs->observation_id }})"
                                            class="btn-action"
                                            style="background: #f4f6f4; border: 1px solid #c8d2c8; color: #1e5631; padding: 4px 8px; font-size: 10px; width: 100%; text-align: center; margin-top: 5px; box-sizing: border-box;">View
                                            Full Details & CSV</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #666666;">No public
                                        observation reports available in the queue.</td>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;
        let markers = [];

        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('map');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            map = L.map(mapElement).setView([0.0236, 37.9062], 6);

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
                    color = '#ff0000ff'; // Red
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

            document.getElementById('modal-edit-region-link').href = `/researcher/regions/${regionId}/edit`;

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
                            floraHtml +=
                                `<li><strong>${f.scientific_name}</strong> (${f.common_name || 'N/A'}) - <em>${f.conservation_status || 'Unknown status'}</em></li>`;
                        });
                    }
                    floraHtml += '</ul>';
                    document.getElementById('modal-flora-list').innerHTML = floraHtml;

                    let climateHtml = '';
                    if (data.climate.length === 0) {
                        climateHtml =
                            '<p style="color: #666666; font-style: italic; margin: 0;">No climate records.</p>';
                    } else {
                        climateHtml =
                            '<table style="width:100%; border-collapse:collapse; font-size:11px;"><thead><tr style="border-bottom:1px solid #ddd; text-align:left;"><th>Date</th><th>Temp</th><th>Rain</th><th>Drought</th></tr></thead><tbody>';
                        data.climate.forEach(c => {
                            climateHtml +=
                                `<tr style="border-bottom:1px solid #eee;"><td>${c.record_date}</td><td>${c.temperature_celsius}°C</td><td>${c.rainfall_mm}mm</td><td>${c.drought_index}</td></tr>`;
                        });
                        climateHtml += '</tbody></table>';
                    }
                    document.getElementById('modal-climate-list').innerHTML = climateHtml;

                    let vegHtml = '';
                    if (data.vegetation.length === 0) {
                        vegHtml =
                            '<p style="color: #666666; font-style: italic; margin: 0;">No vegetation records.</p>';
                    } else {
                        vegHtml =
                            '<table style="width:100%; border-collapse:collapse; font-size:11px;"><thead><tr style="border-bottom:1px solid #ddd; text-align:left;"><th>Date</th><th>NDVI</th><th>Cover</th><th>Condition</th></tr></thead><tbody>';
                        data.vegetation.forEach(v => {
                            vegHtml +=
                                `<tr style="border-bottom:1px solid #eee;"><td>${v.record_date}</td><td>${v.ndvi_value}</td><td>${v.vegetation_cover_percent}%</td><td>${v.vegetation_condition}</td></tr>`;
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

        // View Observation Report Details Modal
        function openObservationModal(observationId) {
            const modal = document.getElementById('observation-details-modal');
            if (!modal) return;

            // Reset modal data views
            document.getElementById('obs-modal-content').style.display = 'none';
            document.getElementById('obs-modal-loading').style.display = 'block';
            modal.style.display = 'flex';

            fetch(`/researcher/observations/${observationId}/details`)
                .then(res => res.json())
                .then(data => {
                    const obs = data.observation;

                    document.getElementById('obs-flora-name').innerText = obs.flora_name;
                    document.getElementById('obs-location').innerText = obs.location;
                    document.getElementById('obs-date-observed').innerText = obs.date_observed;
                    document.getElementById('obs-date-submitted').innerText = obs.submission_date;
                    document.getElementById('obs-status').innerText = obs.status;

                    // Style status badge inside modal
                    const statusSpan = document.getElementById('obs-status');
                    if (obs.status === 'Approved') {
                        statusSpan.style.color = '#155724';
                        statusSpan.style.background = '#d4edda';
                        statusSpan.style.border = '1px solid #c3e6cb';
                    } else if (obs.status === 'Rejected') {
                        statusSpan.style.color = '#721c24';
                        statusSpan.style.background = '#f8d7da';
                        statusSpan.style.border = '1px solid #f5c6cb';
                    } else {
                        statusSpan.style.color = '#8a6d3b';
                        statusSpan.style.background = '#fcf8e3';
                        statusSpan.style.border = '1px solid #faf2cc';
                    }

                    document.getElementById('obs-description').innerText = obs.description;

                    // Quantitative metrics population
                    document.getElementById('obs-temp').innerText = obs.temperature_celsius !== null && obs
                        .temperature_celsius !== undefined ? `${obs.temperature_celsius} °C` : 'N/A';
                    document.getElementById('obs-rain').innerText = obs.rainfall_mm !== null && obs.rainfall_mm !==
                        undefined ? `${obs.rainfall_mm} mm` : 'N/A';
                    document.getElementById('obs-humidity').innerText = obs.humidity_percent !== null && obs
                        .humidity_percent !== undefined ? `${obs.humidity_percent} %` : 'N/A';
                    document.getElementById('obs-drought').innerText = obs.drought_index !== null && obs
                        .drought_index !== undefined ? obs.drought_index : 'N/A';
                    document.getElementById('obs-ndvi').innerText = obs.ndvi_value !== null && obs.ndvi_value !==
                        undefined ? obs.ndvi_value : 'N/A';
                    document.getElementById('obs-veg-cover').innerText = obs.vegetation_cover_percent !== null && obs
                        .vegetation_cover_percent !== undefined ? `${obs.vegetation_cover_percent} %` : 'N/A';
                    document.getElementById('obs-veg-condition').innerText = obs.vegetation_condition !== null && obs
                        .vegetation_condition !== undefined ? obs.vegetation_condition : 'N/A';

                    // Observer details
                    if (obs.observer) {
                        document.getElementById('obs-observer-name').innerText = obs.observer.full_name;
                        document.getElementById('obs-observer-email').innerText = obs.observer.email;
                        document.getElementById('obs-observer-phone').innerText = obs.observer.phone_number || 'N/A';
                    } else {
                        document.getElementById('obs-observer-name').innerText = 'Public Observer';
                        document.getElementById('obs-observer-email').innerText = 'N/A';
                        document.getElementById('obs-observer-phone').innerText = 'N/A';
                    }

                    // Render supporting image
                    const imgContainer = document.getElementById('obs-image-container');
                    if (obs.image_url) {
                        imgContainer.innerHTML =
                            `<img src="${obs.image_url}" alt="Observation Image" style="max-width: 100%; max-height: 250px; border-radius: 4px; border: 1px solid #ddd; object-fit: cover;">`;
                    } else {
                        imgContainer.innerHTML =
                            '<div style="background: #f1f5f9; border: 1px dashed #cbd5e1; height: 150px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 12px;">No image uploaded</div>';
                    }

                    // Parse and display CSV Preview Table
                    const csvContainer = document.getElementById('obs-csv-container');
                    if (data.csv_data && data.csv_data.headers && data.csv_data.headers.length > 0) {
                        let tableHtml =
                            '<div style="overflow-x: auto; max-height: 200px; border: 1px solid #e2e8f0; border-radius: 4px;"><table style="width:100%; border-collapse:collapse; font-size:11px; text-align:left;">';

                        // Table Headers
                        tableHtml +=
                            '<thead><tr style="background:#f8fafc; border-bottom:2px solid #cbd5e1; position:sticky; top:0;">';
                        data.csv_data.headers.forEach(header => {
                            tableHtml +=
                                `<th style="padding:8px; border-bottom:1px solid #cbd5e1; font-weight:bold; color:#334155;">${header}</th>`;
                        });
                        tableHtml += '</tr></thead><tbody>';

                        // Table Rows
                        data.csv_data.rows.forEach(row => {
                            tableHtml += '<tr style="border-bottom:1px solid #f1f5f9;">';
                            row.forEach(cell => {
                                tableHtml += `<td style="padding:6px 8px; color:#475569;">${cell}</td>`;
                            });
                            tableHtml += '</tr>';
                        });

                        tableHtml += '</tbody></table></div>';
                        csvContainer.innerHTML = tableHtml;
                    } else {
                        csvContainer.innerHTML =
                            '<div style="background: #f1f5f9; border: 1px dashed #cbd5e1; padding: 15px; border-radius: 4px; text-align: center; color: #64748b; font-size: 12px;">No CSV dataset preview available</div>';
                    }

                    // Action / Review box
                    const actionContainer = document.getElementById('obs-action-container');
                    if (obs.status === 'Pending') {
                        actionContainer.innerHTML = `
                            <form method="POST" action="/researcher/observations/${obs.observation_id}/review" style="margin: 0; background: #fafafa; border: 1px solid #e2e8f0; padding: 15px; border-radius: 4px;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'}">
                                <h4 style="margin: 0 0 10px 0; color: #1e5631; font-size: 13px;">Review Decision</h4>
                                <div style="margin-bottom: 10px;">
                                    <textarea name="review_comment" placeholder="Optional review comment or feedback for the observer..." rows="2" style="width: 100%; padding: 6px; font-size: 12px; border: 1px solid #cccccc; border-radius: 3px; box-sizing: border-box; resize: vertical;"></textarea>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <button type="submit" name="status" value="Approved" class="btn-execute" style="background: #1e5631; margin-top:0; padding: 8px; flex: 1; cursor: pointer;">Approve Report</button>
                                    <button type="submit" name="status" value="Rejected" class="btn-execute" style="background: #a94442; margin-top:0; padding: 8px; flex: 1; cursor: pointer;">Reject Report</button>
                                </div>
                            </form>
                        `;
                    } else {
                        actionContainer.innerHTML = `
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 4px; font-size: 12px;">
                                <strong>Review Comment:</strong> <span style="font-style: italic;">"${obs.review_comment || 'No comment provided'}"</span><br>
                                <span style="font-size: 11px; color: #64748b; margin-top: 5px; display: block;">Reviewed by: ${obs.reviewer ? obs.reviewer.full_name : 'Researcher'}</span>
                            </div>
                        `;
                    }

                    document.getElementById('obs-modal-loading').style.display = 'none';
                    document.getElementById('obs-modal-content').style.display = 'block';
                })
                .catch(err => {
                    console.error("Error loading observation details:", err);
                    document.getElementById('obs-modal-loading').innerHTML =
                        '<span style="color:red; font-weight:bold;">Error loading details. Please close and try again.</span>';
                });
        }

        function closeObservationModal() {
            document.getElementById('observation-details-modal').style.display = 'none';
        }

        function filterObservationsTable() {
            const query = document.getElementById('obs-search').value.trim().toLowerCase();
            const status = document.getElementById('obs-status-filter').value;
            const level = document.getElementById('obs-level-filter').value;

            // Dynamically update export link with active filters
            const exportBtn = document.querySelector('a[href*="/researcher/export/observations"]');
            if (exportBtn) {
                exportBtn.href = `/researcher/export/observations?search=${encodeURIComponent(query)}&status=${status}&level=${level}`;
            }

            const rows = document.querySelectorAll('.obs-row');
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowLevel = row.getAttribute('data-level');
                
                const floraName = row.querySelector('.obs-flora-name').innerText.toLowerCase();
                const location = row.querySelector('.obs-location').innerText.toLowerCase();
                const observer = row.querySelector('.obs-observer').innerText.toLowerCase();

                const matchesQuery = floraName.includes(query) || location.includes(query) || observer.includes(query);
                const matchesStatus = status === 'all' || rowStatus === status;
                const matchesLevel = level === 'all' || rowLevel === level;

                if (matchesQuery && matchesStatus && matchesLevel) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
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

            <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eeeeee; padding-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                <a id="modal-edit-region-link" href="#" style="background: #1e5631; color: white; border: 1px solid #1e5631; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block; box-sizing: border-box;">Edit Region Details</a>
                <button onclick="closeRegionModal()"
                    style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333;">Close</button>
            </div>
        </div>
    </div>

    <!-- Observation Report Details Modal -->
    <div id="observation-details-modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box; backdrop-filter: blur(2px);">
        <div
            style="background: white; border: 1px solid #cccccc; border-radius: 6px; width: 100%; max-width: 800px; max-height: 90%; overflow-y: auto; padding: 25px; box-sizing: border-box; position: relative; font-family: Arial, sans-serif; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <button onclick="closeObservationModal()"
                style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; font-weight: bold; cursor: pointer; color: #666666;">&times;</button>

            <h2
                style="margin-top: 0; color: #1e5631; border-bottom: 2px solid #1e5631; padding-bottom: 8px; font-size: 18px; font-weight: bold;">
                Public Observation Report Details</h2>

            <div id="obs-modal-loading" style="text-align: center; padding: 40px 0; color: #666; font-size: 14px;">
                <div
                    style="display: inline-block; width: 30px; height: 30px; border: 3px solid rgba(30,86,49,0.1); border-radius: 50%; border-top-color: #1e5631; animation: spin 1s ease-in-out infinite; margin-bottom: 10px;">
                </div>
                <style>
                    @keyframes spin {
                        to {
                            transform: rotate(360deg);
                        }
                    }
                </style>
                <div>Loading observation data...</div>
            </div>

            <div id="obs-modal-content" style="display: none;">
                <!-- Main Grid Split -->
                <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <!-- Details Table -->
                        <table style="width: 100%; font-size: 12px; margin-bottom: 15px; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 6px 0; font-weight: bold; color: #64748b; width: 35%;">Flora Name:
                                </td>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e5631; font-size: 14px;"
                                    id="obs-flora-name"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Region / Location:</td>
                                <td style="padding: 6px 0;" id="obs-location"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Date Observed:</td>
                                <td style="padding: 6px 0;" id="obs-date-observed"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Date Submitted:</td>
                                <td style="padding: 6px 0;" id="obs-date-submitted"></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Report Status:</td>
                                <td style="padding: 6px 0;"><span id="obs-status"
                                        style="font-weight: bold; padding: 2px 6px; border-radius: 4px; font-size: 11px;"></span>
                                </td>
                            </tr>
                        </table>

                        <!-- Observer Info -->
                        <h4
                            style="margin: 15px 0 8px 0; color: #1e5631; font-size: 13px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">
                            Observer Information</h4>
                        <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 4px 0; font-weight: bold; color: #64748b; width: 35%;">Full Name:
                                </td>
                                <td style="padding: 4px 0;" id="obs-observer-name"></td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-weight: bold; color: #64748b;">Email Address:</td>
                                <td style="padding: 4px 0;" id="obs-observer-email"></td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; font-weight: bold; color: #64748b;">Phone Number:</td>
                                <td style="padding: 4px 0;" id="obs-observer-phone"></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right Column: Image and Actions -->
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <h4
                            style="margin: 0 0 5px 0; color: #1e5631; font-size: 13px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">
                            Submitted Image</h4>
                        <div id="obs-image-container" style="text-align: center;"></div>
                    </div>
                </div>

                <!-- Quantitative Metrics Table -->
                <div style="margin-bottom: 20px;">
                    <h4
                        style="margin: 15px 0 8px 0; color: #1e5631; font-size: 13px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">
                        Quantitative Field Metrics</h4>
                    <div
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background: #fafdfb; border: 1px solid #e2e8f0; padding: 15px; border-radius: 4px;">
                        <div>
                            <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b; width: 50%;">
                                        Temperature:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-temp">N/A
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Rainfall:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-rain">N/A
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Humidity:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-humidity">
                                        N/A</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Drought Index:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-drought">
                                        N/A</td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b; width: 50%;">NDVI
                                        Value:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-ndvi">N/A
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Veg Cover:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;" id="obs-veg-cover">
                                        N/A</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold; color: #64748b;">Veg Condition:</td>
                                    <td style="padding: 6px 0; font-weight: bold; color: #1e5631;"
                                        id="obs-veg-condition">N/A</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Text-based observations -->
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                    <h4
                        style="margin: 0 0 8px 0; color: #1e5631; font-size: 13px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">
                        Observation Text & Field Notes</h4>
                    <p id="obs-description"
                        style="margin: 0; font-size: 12px; line-height: 1.5; color: #334155; white-space: pre-line;">
                    </p>
                </div>

                <!-- CSV Preview Section -->
                <div style="margin-bottom: 20px;">
                    <h4
                        style="margin: 0 0 8px 0; color: #1e5631; font-size: 13px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">
                        Supporting Dataset Preview (CSV)</h4>
                    <div id="obs-csv-container"></div>
                </div>

                <!-- Action Review Box -->
                <div id="obs-action-container" style="margin-top: 20px;"></div>
            </div>

            <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eeeeee; padding-top: 15px;">
                <button onclick="closeObservationModal()"
                    style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333;">Close
                    Details</button>
            </div>
        </div>
    </div>

</body>

</html>
