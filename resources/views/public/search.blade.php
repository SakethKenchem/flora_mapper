<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Registry - FloraMapper</title>
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

        .search-bar-panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .search-input:focus {
            border-color: #1e5631;
            outline: none;
        }

        .btn-search {
            background: #1e5631;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-search:hover {
            background: #153e22;
        }

        .results-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .results-container {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
            min-height: 200px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e5631;
            margin-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 8px;
        }

        .result-item {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .vuln-badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
            margin-top: 5px;
        }

        .badge-high {
            color: #721c24;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .badge-moderate {
            color: #856404;
            background: #fff3cd;
            border: 1px solid #ffeeba;
        }

        .badge-low {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .badge-none {
            color: #383d41;
            background: #e2e3e5;
            border: 1px solid #d6d8db;
        }

        .info-msg {
            text-align: center;
            color: #666666;
            font-style: italic;
            padding: 40px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div>
            <a href="{{ route('home') }}" class="sidebar-brand">FloraMapper</a>

            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('public.dashboard') }}" class="menu-link">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('public.search') }}" class="menu-link active">Search Registry</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('public.observations.create') }}" class="menu-link">Submit Observation</a>
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
            <h1>Search Registry</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Flora & Ecosystems</span>
        </div>

        <div class="search-bar-panel">
            <form action="{{ route('public.search') }}" method="GET" class="search-form">
                <input type="text" name="q" value="{{ $query }}" class="search-input" placeholder="Search by region, county, ecosystem, scientific name, conservation status..." required autofocus>
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>

        @if ($query)
            <div class="results-container">
                <!-- Left Column: Ecosystem Regions Results -->
                <div class="panel">
                    <div class="panel-title">Matched Ecosystem Regions ({{ $regions->count() }})</div>
                    @forelse ($regions as $region)
                        <div class="result-item">
                            <strong style="color: #1e5631; font-size: 15px;">{{ $region->region_name }}</strong>
                            <div style="font-size: 12px; color: #555; margin-top: 3px;">
                                <strong>County:</strong> {{ $region->county ?? 'N/A' }} | 
                                <strong>Ecosystem:</strong> {{ $region->ecosystem_type ?? 'N/A' }}
                            </div>
                            @php
                                $latestAssessment = $region->assessments->first();
                                $badgeClass = 'badge-none';
                                $lvl = 'Not Assessed';
                                if ($latestAssessment) {
                                    $lvl = $latestAssessment->vulnerability_level;
                                    if ($lvl === 'High') $badgeClass = 'badge-high';
                                    elseif ($lvl === 'Moderate') $badgeClass = 'badge-moderate';
                                    elseif ($lvl === 'Low') $badgeClass = 'badge-low';
                                }
                            @endphp
                            <span class="vuln-badge {{ $badgeClass }}">Vulnerability: {{ $lvl }}</span>
                        </div>
                    @empty
                        <div class="info-msg">No matching regions found.</div>
                    @endforelse
                </div>

                <!-- Right Column: Flora Species Results -->
                <div class="panel">
                    <div class="panel-title">Matched Flora Species ({{ $flora->count() }})</div>
                    @forelse ($flora as $species)
                        <div class="result-item">
                            <strong style="color: #1e5631; font-size: 15px;">{{ $species->scientific_name }}</strong> 
                            <span style="font-style: italic; font-size: 13px; color: #666;">({{ $species->common_name ?? 'No common name' }})</span>
                            <div style="font-size: 12px; color: #555; margin-top: 3px;">
                                <strong>Type:</strong> {{ $species->species_type ?? 'N/A' }} | 
                                <strong>Status:</strong> {{ $species->conservation_status ?? 'N/A' }}
                            </div>
                            <div style="font-size: 11px; color: #777; margin-top: 2px;">
                                <strong>Habitat:</strong> {{ $species->habitat_type ?? 'N/A' }}
                            </div>
                            @php
                                $badgeClass = 'badge-none';
                                $lvl = $species->vulnerability_level;
                                if ($lvl === 'High') $badgeClass = 'badge-high';
                                elseif ($lvl === 'Moderate') $badgeClass = 'badge-moderate';
                                elseif ($lvl === 'Low') $badgeClass = 'badge-low';
                            @endphp
                            <span class="vuln-badge {{ $badgeClass }}">Sensitivity: {{ $lvl ?? 'Unknown' }}</span>
                        </div>
                    @empty
                        <div class="info-msg">No matching flora species found.</div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="panel" style="display: flex; align-items: center; justify-content: center;">
                <div class="info-msg">
                    Enter keywords above to search for ecosystem regions, vulnerability ratings, and registered flora species.
                </div>
            </div>
        @endif
    </div>

</body>

</html>
