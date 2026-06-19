<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerability Analysis Console - FloraMapper</title>
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

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
            max-width: 600px;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e5631;
            margin-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            box-sizing: border-box;
            font-size: 13px;
        }

        .form-control:focus {
            border-color: #1e5631;
            outline: none;
        }

        .btn-submit {
            background: #1e5631;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            width: 100%;
        }

        .btn-submit:hover {
            background: #153e22;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .info-box {
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 12px;
            line-height: 1.5;
            color: #555555;
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
                    <a href="{{ route('researcher.datasets.climate.upload') }}" class="menu-link">Upload Climate
                        Data</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.vegetation.upload') }}" class="menu-link">Upload NDVI
                        Data</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.datasets.flora.upload') }}" class="menu-link">Upload Flora Data</a>
                </li>
            </ul>

            <div class="menu-label">Assessments</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.analysis') }}" class="menu-link active">Run Assessment</a>
                </li>
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.create') }}" class="menu-link">Add Flora Record</a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">Reports Manager</a>
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
            <h1>Vulnerability Analysis Console</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 600px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Execute Vulnerability Assessment</div>

            <form method="POST" action="{{ route('researcher.analysis.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="region_id">Target Region/Ecosystem</label>
                    <select id="region_id" name="region_id" class="form-control" required>
                        <option value="">Select Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->region_id }}">{{ $region->region_name }}
                                ({{ $region->county }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="climate_dataset_id">Select Climate Reference Dataset</label>
                    <select id="climate_dataset_id" name="climate_dataset_id" class="form-control" required>
                        <option value="">Select Climate Dataset</option>
                        @foreach ($climateDatasets as $dataset)
                            <option value="{{ $dataset->dataset_id }}">{{ $dataset->dataset_name }}
                                ({{ $dataset->source_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="vegetation_dataset_id">Select Vegetation (NDVI) Reference Dataset</label>
                    <select id="vegetation_dataset_id" name="vegetation_dataset_id" class="form-control" required>
                        <option value="">Select Vegetation Dataset</option>
                        @foreach ($vegDatasets as $dataset)
                            <option value="{{ $dataset->dataset_id }}">{{ $dataset->dataset_name }}
                                ({{ $dataset->source_name }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">Execute Vulnerability Algorithm</button>
            </form>

            <div class="info-box">
                <strong>Vulnerability Evaluation Logic:</strong>
                <p>When running this evaluation, the system averages the uploaded temperature, rainfall, and NDVI
                    parameters for the selected region and compares them using the following indicators:</p>
                <ul>
                    <li><strong>Temperature Index</strong>: Measures deviation from the ideal forest temperature
                        threshold (20°C). Higher deviations indicate high climate sensitivity.</li>
                    <li><strong>Rainfall Index</strong>: Measures scarcity of rain (inverse scale relative to a 200mm
                        base target).</li>
                    <li><strong>NDVI Index</strong>: Measures vegetation thinness (inverse scale relative to a healthy
                        0.8 NDVI density).</li>
                </ul>
                <p>The averaged score is checked against the configured low/moderate/high thresholds to save the
                    regional rating.</p>
            </div>
        </div>
    </div>

</body>

</html>
