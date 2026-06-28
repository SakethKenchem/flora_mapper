<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Region Details - FloraMapper</title>
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
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit:hover {
            background: #153e22;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #333333;
            border: 1px solid #cccccc;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            display: block;
            box-sizing: border-box;
            width: 100%;
            margin-top: 10px;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
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
            <h1>Edit Region Details</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 600px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Update Region Parameters</div>

            <form method="POST" action="{{ route('researcher.regions.update', $region->region_id) }}">
                @csrf

                <div class="form-group">
                    <label for="region_name">Region Name</label>
                    <input type="text" id="region_name" name="region_name" class="form-control" value="{{ old('region_name', $region->region_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="county">County</label>
                    <input type="text" id="county" name="county" class="form-control" value="{{ old('county', $region->county) }}" required>
                </div>

                <div class="form-group">
                    <label for="ecosystem_type">Ecosystem Type</label>
                    <input type="text" id="ecosystem_type" name="ecosystem_type" class="form-control" value="{{ old('ecosystem_type', $region->ecosystem_type) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label for="latitude">Latitude</label>
                        <input type="number" step="any" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', $region->latitude) }}" required>
                    </div>
                    <div>
                        <label for="longitude">Longitude</label>
                        <input type="number" step="any" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', $region->longitude) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Ecosystem Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" style="resize:vertical;">{{ old('description', $region->description) }}</textarea>
                </div>

                <button type="submit" class="btn-submit">Save Region Details</button>
                <a href="{{ route('researcher.dashboard') }}" class="btn-cancel">Cancel & Return</a>
            </form>
        </div>
    </div>

</body>

</html>
