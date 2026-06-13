<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flora Record - FloraMapper</title>
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
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-container {
            background: white;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 25px;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #1e5631;
        }

        .btn-submit {
            background: #1e5631;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-submit:hover {
            background: #153e22;
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
                    <a href="{{ route('researcher.flora.create') }}" class="menu-link active">Add Flora Record</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.reports') }}" class="menu-link">Reports Manager</a>
                </li>
            </ul>
        </div>

        <div class="user-panel">
            <strong>{{ Auth::user()->full_name }}</strong><br>
            <span style="font-size: 11px; color: #a3e635;">{{ Auth::user()->institution ?? 'Researcher' }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Register Flora Species</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Flora Registry</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="form-container">
            <form method="POST" action="{{ route('researcher.flora.store') }}">
                @csrf

                <div class="form-group">
                    <label for="scientific_name">Scientific Name (Required)</label>
                    <input type="text" id="scientific_name" name="scientific_name" class="form-control" placeholder="e.g. Rhizophora mucronata" value="{{ old('scientific_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="common_name">Common Name</label>
                    <input type="text" id="common_name" name="common_name" class="form-control" placeholder="e.g. Red Mangrove" value="{{ old('common_name') }}">
                </div>

                <div class="form-group">
                    <label for="region_id">Ecosystem Region (Required)</label>
                    <select id="region_id" name="region_id" class="form-control" required>
                        <option value="">-- Select Region --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region_id }}" {{ old('region_id') == $region->region_id ? 'selected' : '' }}>
                                {{ $region->region_name }} ({{ $region->county }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="species_type">Species Type</label>
                    <input type="text" id="species_type" name="species_type" class="form-control" placeholder="e.g. Mangrove tree, Shrub" value="{{ old('species_type') }}">
                </div>

                <div class="form-group">
                    <label for="conservation_status">Conservation Status</label>
                    <input type="text" id="conservation_status" name="conservation_status" class="form-control" placeholder="e.g. Vulnerable, Endangered, Least Concern" value="{{ old('conservation_status') }}">
                </div>

                <div class="form-group">
                    <label for="habitat_type">Habitat Type</label>
                    <input type="text" id="habitat_type" name="habitat_type" class="form-control" placeholder="e.g. Saline muddy shores, Dry woodland" value="{{ old('habitat_type') }}">
                </div>

                <div class="form-group">
                    <label for="vulnerability_level">Ecosystem Vulnerability Level</label>
                    <select id="vulnerability_level" name="vulnerability_level" class="form-control" required>
                        <option value="Low" {{ old('vulnerability_level') == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Moderate" {{ old('vulnerability_level') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="High" {{ old('vulnerability_level') == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Add Species to Registry</button>
            </form>
        </div>
    </div>

</body>

</html>
