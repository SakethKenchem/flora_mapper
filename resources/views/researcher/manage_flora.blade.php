<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Registry - FloraMapper</title>
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

        .registry-container {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .registry-container {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
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
            background: white;
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
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.manage') }}" class="menu-link active">Flora Registry</a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">Reports Manager</a>
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
            <h1>Flora Registry</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Management Console</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="registry-container">
            <!-- Left Column: Manual Single Registration -->
            <div class="panel">
                <div class="panel-title">Add Flora Species Manually</div>
                <form method="POST" action="{{ route('researcher.flora.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="scientific_name">Scientific Name (Required)</label>
                        <input type="text" id="scientific_name" name="scientific_name" class="form-control"
                            placeholder="e.g. Rhizophora mucronata" value="{{ old('scientific_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="common_name">Common Name</label>
                        <input type="text" id="common_name" name="common_name" class="form-control"
                            placeholder="e.g. Red Mangrove" value="{{ old('common_name') }}">
                    </div>

                    <div class="form-group">
                        <label for="region_id">Ecosystem Region (Required)</label>
                        <select id="region_id" name="region_id" class="form-control" required>
                            <option value="">-- Select Region --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->region_id }}"
                                    {{ old('region_id') == $region->region_id ? 'selected' : '' }}>
                                    {{ $region->region_name }} ({{ $region->county }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="species_type">Species Type</label>
                        <input type="text" id="species_type" name="species_type" class="form-control"
                            placeholder="e.g. Mangrove tree, Shrub" value="{{ old('species_type') }}">
                    </div>

                    <div class="form-group">
                        <label for="conservation_status">Conservation Status</label>
                        <input type="text" id="conservation_status" name="conservation_status" class="form-control"
                            placeholder="e.g. Vulnerable, Endangered, Least Concern" value="{{ old('conservation_status') }}">
                    </div>

                    <div class="form-group">
                        <label for="habitat_type">Habitat Type</label>
                        <input type="text" id="habitat_type" name="habitat_type" class="form-control"
                            placeholder="e.g. Saline muddy shores, Dry woodland" value="{{ old('habitat_type') }}">
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
                    <a href="{{ route('researcher.dashboard') }}" class="btn-cancel">Cancel</a>
                </form>
            </div>

            <!-- Right Column: Bulk CSV Import -->
            <div>
                <div class="panel">
                    <div class="panel-title">Bulk CSV Flora Dataset Upload</div>
                    <form method="POST" action="{{ route('researcher.datasets.flora.upload.submit') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="dataset_name">Dataset Title/Name</label>
                            <input type="text" id="dataset_name" name="dataset_name" class="form-control"
                                placeholder="e.g. Flora Species Distribution 2026" value="{{ old('dataset_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="source_name">Data Source</label>
                            <input type="text" id="source_name" name="source_name" class="form-control"
                                placeholder="e.g. KEFRI, GBIF, Kenya Wildlife Service" value="{{ old('source_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" class="form-control" rows="3"
                                placeholder="Brief details about the flora data survey, etc.">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="csv_file">Select CSV Dataset File</label>
                            <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                        </div>

                        <button type="submit" class="btn-submit">Import Dataset</button>
                    </form>

                    <div class="info-box">
                        <div style="margin-bottom: 10px;">
                            <a href="{{ asset('flora_sample.csv') }}" download
                                style="color: #1e5631; font-weight: bold; text-decoration: underline;">
                                Download Template Flora CSV File
                            </a>
                        </div>
                        <strong>CSV File Format Requirements:</strong>
                        <p>The first line of the file must be the header. Required columns are case-insensitive:</p>
                        <ul>
                            <li><code>scientific_name</code>: The scientific classification of the species (Required).</li>
                            <li><code>region_name</code>: Optional. If it matches one of our regions exactly (e.g. <strong>Mau Forest Complex</strong>, <strong>Tana Delta</strong>, <strong>Mt. Kenya region</strong>, <strong>Turkana</strong>), the record will be linked to that region.</li>
                            <li>Optional columns: <code>common_name</code>, <code>species_type</code>, <code>conservation_status</code>, <code>habitat_type</code>, <code>vulnerability_level</code>.</li>
                        </ul>
                        <strong>Example Format:</strong>
                        <pre>region_name,scientific_name,common_name,species_type,conservation_status,habitat_type,vulnerability_level
Mau Forest Complex,Ficus sycomorus,Sycamore Fig,Tree,Least Concern,Montane Forest,Low
Tana Delta,Rhizophora mucronata,Red Mangrove,Mangrove,Near Threatened,Estuary/Mangrove,High</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
