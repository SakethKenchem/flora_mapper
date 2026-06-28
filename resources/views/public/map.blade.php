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
                <input type="text" id="search-input" class="form-control" placeholder="Type region, county..." onkeyup="filterRegionsList()">
            </div>

            <div class="form-group">
                <label for="indicator-select">Climate Indicator</label>
                <select id="indicator-select" class="form-control" onchange="changeIndicator(this.value)">
                    <option value="overall">Overall Vulnerability Index</option>
                    <option value="temperature">Average Temperature Score</option>
                    <option value="rainfall">Average Monthly Rainfall Score</option>
                    <option value="ndvi">Average NDVI Vegetation Score</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ecosystem-select">Ecosystem Type</label>
                <select id="ecosystem-select" class="form-control" onchange="filterRegionsList()">
                    <option value="">All Ecosystems</option>
                    <option value="Montane Forest">Montane Forest</option>
                    <option value="Wetland/Mangrove">Wetland/Mangrove</option>
                    <option value="Alpine/Montane Forest">Alpine/Montane Forest</option>
                    <option value="Savannah">Savannah</option>
                </select>
            </div>

            <div class="form-group">
                <label for="vulnerability-select">Vulnerability Level</label>
                <select id="vulnerability-select" class="form-control" onchange="filterRegionsList()">
                    <option value="">All Levels</option>
                    <option value="High">High</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Low">Low</option>
                    <option value="Not Assessed">Not Assessed</option>
                </select>
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

        let currentIndicator = 'overall';

        function renderMarkers(data, indicator = 'overall') {
            // Clear existing markers
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            data.forEach(r => {
                let score = null;
                let activeLevel = 'Not Assessed';
                let scoreText = '';

                if (indicator === 'overall') {
                    score = r.overall_score;
                    activeLevel = r.vulnerability_level;
                    scoreText = score ? `${score}%` : 'Not Assessed';
                } else if (indicator === 'temperature') {
                    score = r.temperature_score;
                    activeLevel = score >= 61 ? 'High' : (score >= 31 ? 'Moderate' : 'Low');
                    scoreText = score ? `${score}%` : 'Not Assessed';
                } else if (indicator === 'rainfall') {
                    score = r.rainfall_score;
                    activeLevel = score >= 61 ? 'High' : (score >= 31 ? 'Moderate' : 'Low');
                    scoreText = score ? `${score}%` : 'Not Assessed';
                } else if (indicator === 'ndvi') {
                    score = r.ndvi_score;
                    activeLevel = score >= 61 ? 'High' : (score >= 31 ? 'Moderate' : 'Low');
                    scoreText = score ? `${score}%` : 'Not Assessed';
                }

                if (score === null) {
                    activeLevel = 'Not Assessed';
                }

                let color = '#9ca3af'; // Not Assessed (Gray)
                if (score !== null) {
                    if (score >= 61) {
                        color = '#ef4444'; // Red
                    } else if (score >= 31) {
                        color = '#f59e0b'; // Yellow/Orange
                    } else {
                        color = '#10b981'; // Green
                    }
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
                        <span style="font-weight: bold; color: ${color};">${activeLevel}</span> 
                        ${score !== null ? `(${scoreText})` : ''}<br><br>
                        <p style="margin: 0 0 8px 0; color: #555555; line-height: 1.4;">${r.interpretation}</p>
                        <button onclick="openRegionDetails(${r.region_id})" style="background: #1e5631; color: white; border: none; padding: 6px 10px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 11px; width: 100%;">View Regional Datasets</button>
                    </div>
                `;
                marker.bindPopup(popupContent);
                marker.region_name = r.region_name.toLowerCase();
                marker.county = (r.county || '').toLowerCase();
                marker.ecosystem_type = r.ecosystem_type;
                marker.vulnerability_level = activeLevel;
                markers.push(marker);
            });
        }

        function filterRegionsList() {
            const query = document.getElementById('search-input').value.trim().toLowerCase();
            const ecosystem = document.getElementById('ecosystem-select').value;
            const vulnerability = document.getElementById('vulnerability-select').value;

            markers.forEach(marker => {
                const matchesQuery = marker.region_name.includes(query) || marker.county.includes(query);
                const matchesEcosystem = !ecosystem || marker.ecosystem_type === ecosystem;
                const matchesVulnerability = !vulnerability || marker.vulnerability_level === vulnerability;

                if (matchesQuery && matchesEcosystem && matchesVulnerability) {
                    marker.setStyle({ opacity: 1, fillOpacity: 0.8 });
                } else {
                    marker.setStyle({ opacity: 0.1, fillOpacity: 0.1 });
                }
            });
        }

        function changeIndicator(indicator) {
            currentIndicator = indicator;
            renderMarkers(regionsData, indicator);
            filterRegionsList(); // Re-apply current searches/filters
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