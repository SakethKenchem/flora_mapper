<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Regions - FloraMapper</title>
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
            border-bottom: 1px solid #dcdcdc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1e5631;
            font-size: 24px;
        }

        .selection-panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-weight: bold;
            font-size: 13px;
        }

        .form-control {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            font-size: 13px;
            background: white;
            height: 36px;
            box-sizing: border-box;
        }

        .btn-compare {
            background: #1e5631;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            height: 36px;
            display: flex;
            align-items: center;
        }

        .btn-compare:hover {
            background: #153e22;
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .region-card {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
        }

        .region-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e5631;
            border-bottom: 2px solid #1e5631;
            padding-bottom: 8px;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .meta-table tr {
            border-bottom: 1px solid #eeeeee;
        }

        .meta-table td {
            padding: 8px 5px;
        }

        .meta-label {
            font-weight: bold;
            color: #666666;
            width: 40%;
        }

        .badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }

        .metric-comparison {
            margin-top: 20px;
        }

        .metric-header {
            font-weight: bold;
            color: #1e5631;
            font-size: 14px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .metric-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .progress-bar-container {
            width: 60%;
            background: #e2e8f0;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 5px;
        }

        .score-value {
            font-weight: bold;
            width: 12%;
            text-align: right;
        }

        .interpretation-text {
            background: #fcfcfc;
            border: 1px solid #e5e5e5;
            padding: 12px;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.5;
            color: #555555;
            margin-top: 15px;
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
                    <a href="{{ route('researcher.dashboard') }}" class="menu-link">Dashboard</a>
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
            </ul>

            <div class="menu-label">Assessments</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.analysis') }}" class="menu-link">Run Assessment</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.compare') }}" class="menu-link active">Compare Regions</a>
                </li>
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.manage') }}" class="menu-link">Flora Registry</a>
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
            <h1>Compare Regional Vulnerabilities</h1>
        </div>

        <div class="selection-panel">
            <form method="GET" action="{{ route('researcher.compare') }}">
                <div class="form-row">
                    <div class="form-group">
                        <label for="region_a">Region A</label>
                        <select id="region_a" name="region_a" class="form-control" required>
                            <option value="">Select Region A</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->region_id }}" {{ $regionAId == $r->region_id ? 'selected' : '' }}>{{ $r->region_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="region_b">Region B</label>
                        <select id="region_b" name="region_b" class="form-control" required>
                            <option value="">Select Region B</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->region_id }}" {{ $regionBId == $r->region_id ? 'selected' : '' }}>{{ $r->region_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-compare">Compare Ecosystems</button>
                </div>
            </form>
        </div>

        <div class="comparison-grid">
            <!-- Region A Profile Card -->
            <div class="region-card">
                @if($regionA)
                    <h3 class="region-title">{{ $regionA->region_name }}</h3>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">County:</td>
                            <td>{{ $regionA->county }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Ecosystem Type:</td>
                            <td>{{ $regionA->ecosystem_type }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Coordinates:</td>
                            <td>{{ $regionA->latitude }}, {{ $regionA->longitude }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Description:</td>
                            <td>{{ $regionA->description ?? 'No description available.' }}</td>
                        </tr>
                    </table>

                    @if($regionA->assessments->count() > 0)
                        @php
                            $latestA = $regionA->assessments->first();
                            $overallA = floatval($latestA->overall_score);
                            $tempA = floatval($latestA->temperature_score);
                            $rainA = floatval($latestA->rainfall_score);
                            $ndviA = floatval($latestA->ndvi_score);
                            
                            $lvlColorA = '#10b981'; // Green
                            if($latestA->vulnerability_level === 'High') $lvlColorA = '#ef4444';
                            elseif($latestA->vulnerability_level === 'Moderate') $lvlColorA = '#f59e0b';
                        @endphp

                        <div class="metric-comparison">
                            <div class="metric-header">Latest Vulnerability Assessment</div>
                            <div class="metric-row">
                                <span style="font-weight: bold;">vulnerability Level</span>
                                <span class="badge" style="background: {{ $lvlColorA }}22; color: {{ $lvlColorA }}; border: 1px solid {{ $lvlColorA }};">{{ $latestA->vulnerability_level }}</span>
                            </div>
                            
                            <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; margin-top: 15px;">Indicator Breakdown:</div>
                            
                            <div class="metric-row">
                                <span style="width: 28%;">Overall Index</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $overallA }}%; background: #1e5631;"></div>
                                </div>
                                <span class="score-value">{{ $overallA }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">Temperature Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $tempA }}%; background: #f59e0b;"></div>
                                </div>
                                <span class="score-value">{{ $tempA }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">Rainfall Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $rainA }}%; background: #3b82f6;"></div>
                                </div>
                                <span class="score-value">{{ $rainA }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">NDVI Cover Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $ndviA }}%; background: #10b981;"></div>
                                </div>
                                <span class="score-value">{{ $ndviA }}%</span>
                            </div>
                        </div>

                        <div class="interpretation-text">
                            <strong>Interpretation Note:</strong><br>
                            {{ $latestA->interpretation }}
                        </div>
                    @else
                        <div class="interpretation-text" style="text-align: center; font-style: italic;">
                            No assessments have been run for this region yet.
                        </div>
                    @endif
                @else
                    <div style="text-align: center; color: #777777; padding-top: 50px;">
                        Select Region A from the dropdown list.
                    </div>
                @endif
            </div>

            <!-- Region B Profile Card -->
            <div class="region-card">
                @if($regionB)
                    <h3 class="region-title">{{ $regionB->region_name }}</h3>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">County:</td>
                            <td>{{ $regionB->county }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Ecosystem Type:</td>
                            <td>{{ $regionB->ecosystem_type }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Coordinates:</td>
                            <td>{{ $regionB->latitude }}, {{ $regionB->longitude }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Description:</td>
                            <td>{{ $regionB->description ?? 'No description available.' }}</td>
                        </tr>
                    </table>

                    @if($regionB->assessments->count() > 0)
                        @php
                            $latestB = $regionB->assessments->first();
                            $overallB = floatval($latestB->overall_score);
                            $tempB = floatval($latestB->temperature_score);
                            $rainB = floatval($latestB->rainfall_score);
                            $ndviB = floatval($latestB->ndvi_score);
                            
                            $lvlColorB = '#10b981'; // Green
                            if($latestB->vulnerability_level === 'High') $lvlColorB = '#ef4444';
                            elseif($latestB->vulnerability_level === 'Moderate') $lvlColorB = '#f59e0b';
                        @endphp

                        <div class="metric-comparison">
                            <div class="metric-header">Latest Vulnerability Assessment</div>
                            <div class="metric-row">
                                <span style="font-weight: bold;">vulnerability Level</span>
                                <span class="badge" style="background: {{ $lvlColorB }}22; color: {{ $lvlColorB }}; border: 1px solid {{ $lvlColorB }};">{{ $latestB->vulnerability_level }}</span>
                            </div>
                            
                            <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; margin-top: 15px;">Indicator Breakdown:</div>
                            
                            <div class="metric-row">
                                <span style="width: 28%;">Overall Index</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $overallB }}%; background: #1e5631;"></div>
                                </div>
                                <span class="score-value">{{ $overallB }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">Temperature Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $tempB }}%; background: #f59e0b;"></div>
                                </div>
                                <span class="score-value">{{ $tempB }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">Rainfall Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $rainB }}%; background: #3b82f6;"></div>
                                </div>
                                <span class="score-value">{{ $rainB }}%</span>
                            </div>

                            <div class="metric-row">
                                <span style="width: 28%;">NDVI Cover Score</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $ndviB }}%; background: #10b981;"></div>
                                </div>
                                <span class="score-value">{{ $ndviB }}%</span>
                            </div>
                        </div>

                        <div class="interpretation-text">
                            <strong>Interpretation Note:</strong><br>
                            {{ $latestB->interpretation }}
                        </div>
                    @else
                        <div class="interpretation-text" style="text-align: center; font-style: italic;">
                            No assessments have been run for this region yet.
                        </div>
                    @endif
                @else
                    <div style="text-align: center; color: #777777; padding-top: 50px;">
                        Select Region B from the dropdown list.
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>

</html>
