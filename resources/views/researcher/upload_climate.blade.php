<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Climate Dataset - FloraMapper</title>
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
            max-width: 700px;
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
        }

        .btn-submit:hover {
            background: #153e22;
        }

        .btn-cancel {
            background: #ffffff;
            color: #666666;
            border: 1px solid #cccccc;
            padding: 9px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
            margin-left: 10px;
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
        }

        pre {
            background: #eeeeee;
            padding: 8px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 11px;
            margin: 5px 0 0 0;
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
                    <a href="{{ route('researcher.datasets.climate.upload') }}" class="menu-link active">Upload Climate Data</a>
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
            <h1>Upload Climate Dataset</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 700px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Climate File Ingestion Form</div>

            <form method="POST" action="{{ route('researcher.datasets.climate.upload.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="dataset_name">Dataset Title/Name</label>
                    <input type="text" id="dataset_name" name="dataset_name" class="form-control" placeholder="e.g. Kenya Meteorological Station Data 2026" value="{{ old('dataset_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="source_name">Data Source</label>
                    <input type="text" id="source_name" name="source_name" class="form-control" placeholder="e.g. KMD, WorldClim" value="{{ old('source_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Brief summary of dates covered, parameters, etc.">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="csv_file">Select CSV Dataset File</label>
                    <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                </div>

                <button type="submit" class="btn-submit">Import Dataset</button>
                <a href="{{ route('researcher.dashboard') }}" class="btn-cancel">Cancel</a>
            </form>

            <div class="info-box">
                <strong>CSV File Format Requirements:</strong>
                <p>The first line of the file must be the header. Required columns are case-insensitive:</p>
                <ul>
                    <li><code>region_name</code>: Must match one of our seeded regions exactly (e.g. <strong>Mau Forest Complex</strong>, <strong>Tana Delta</strong>, <strong>Mt. Kenya region</strong>, <strong>Turkana</strong>).</li>
                    <li><code>record_date</code>: In YYYY-MM-DD format.</li>
                    <li><code>temperature_celsius</code>: Temperature value (decimal).</li>
                    <li><code>rainfall_mm</code>: Rainfall amount (decimal).</li>
                    <li>Optional columns: <code>humidity_percent</code>, <code>drought_index</code>, <code>flood_risk_level</code>.</li>
                </ul>
                <strong>Example Format:</strong>
                <pre>region_name,record_date,temperature_celsius,rainfall_mm,humidity_percent,drought_index,flood_risk_level
Mau Forest Complex,2026-01-01,21.40,132.50,78.00,0.25,Low
Tana Delta,2026-01-01,28.60,61.30,69.10,0.65,High</pre>
            </div>
        </div>
    </div>

</body>

</html>
