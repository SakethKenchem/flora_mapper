<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Observation - FloraMapper</title>
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

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 25px;
            max-width: 700px;
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
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 13px;
            box-sizing: border-box;
            background: white;
        }

        .form-control:focus {
            border-color: #1e5631;
            outline: none;
        }

        .btn-submit {
            background: #1e5631;
            border: 1px solid #1e5631;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            color: white;
            transition: background 0.2s;
            height: 35px;
        }

        .btn-submit:hover {
            background: #153e22;
        }

        .btn-cancel {
            background: #e2e8f0;
            border: 1px solid #cccccc;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            color: #333333;
            text-decoration: none;
            display: inline-block;
            line-height: 1.4;
            height: 35px;
            box-sizing: border-box;
            margin-left: 10px;
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

            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('public.dashboard') }}" class="menu-link">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('map') }}" class="menu-link">Map</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('public.observations.create') }}" class="menu-link active">Submit Observation</a>
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
            <h1>Submit Observation Report</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 700px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Submit Field Observation Report</div>

            <form action="{{ route('public.observations.submit') }}" method="POST" enctype="multipart/form-data"
                style="display: flex; flex-direction: column; gap: 15px;">
                @csrf

                <div class="form-group">
                    <label for="flora_id">Registered Species (Optional)</label>
                    <select id="flora_id" name="flora_id" onchange="toggleCustomFlora(this)" class="form-control"
                        style="height: 35px;">
                        <option value="">-- Select Registered Species (or enter custom below) --</option>
                        @foreach ($registeredFlora as $flora)
                            <option value="{{ $flora->flora_id }}"
                                {{ old('flora_id') == $flora->flora_id ? 'selected' : '' }}>
                                {{ $flora->scientific_name }} ({{ $flora->common_name ?? 'No common name' }})
                            </option>
                        @endforeach
                        <option value="custom" {{ old('flora_id') === 'custom' ? 'selected' : '' }}>Other / Custom
                            Species (Type manually)</option>
                    </select>
                </div>

                <div id="custom-flora-group" class="form-group">
                    <label for="flora_name_custom">Flora Species Name <span style="color: red;">*</span></label>
                    <input type="text" id="flora_name_custom" name="flora_name_custom"
                        placeholder="e.g. Ficus sycomorus" class="form-control" style="height: 35px;"
                        value="{{ old('flora_name_custom') }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="location">Location / Region <span style="color: red;">*</span></label>
                        <input type="text" id="location" name="location"
                            placeholder="e.g. Mau Forest Complex Block B" class="form-control" style="height: 35px;"
                            value="{{ old('location') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date_observed">Date Observed <span style="color: red;">*</span></label>
                        <input type="date" id="date_observed" name="date_observed" max="{{ date('Y-m-d') }}"
                            class="form-control" style="height: 35px;" value="{{ old('date_observed') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Text Observations & Details <span style="color: red;">*</span></label>
                    <textarea id="description" name="description"
                        placeholder="Describe the health status, canopy density, soil conditions, tree damage, or other text observations..."
                        rows="4" class="form-control" style="resize: vertical;" required>{{ old('description') }}</textarea>
                </div>

                <!-- Quantitative Metrics (Climate & Vegetation) -->
                <div style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 5px;">
                    <h4 style="margin: 0 0 10px 0; color: #1e5631; font-size: 14px; font-weight: bold;">Quantitative
                        Field Metrics (Optional)</h4>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="temperature_celsius">Temperature (°C)</label>
                            <input type="number" step="0.1" min="-10" max="60" id="temperature_celsius"
                                name="temperature_celsius" placeholder="e.g. 24.5" class="form-control"
                                style="height: 35px;" value="{{ old('temperature_celsius') }}">
                        </div>
                        <div class="form-group">
                            <label for="rainfall_mm">Rainfall (mm)</label>
                            <input type="number" step="0.1" min="0" max="5000" id="rainfall_mm"
                                name="rainfall_mm" placeholder="e.g. 150.2" class="form-control"
                                style="height: 35px;" value="{{ old('rainfall_mm') }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="humidity_percent">Humidity (%)</label>
                            <input type="number" step="0.1" min="0" max="100"
                                id="humidity_percent" name="humidity_percent" placeholder="e.g. 65.0"
                                class="form-control" style="height: 35px;" value="{{ old('humidity_percent') }}">
                        </div>
                        <div class="form-group">
                            <label for="drought_index">Drought Index</label>
                            <input type="number" step="0.1" min="0" max="10" id="drought_index"
                                name="drought_index" placeholder="e.g. 2.5 (0-10 scale)" class="form-control"
                                style="height: 35px;" value="{{ old('drought_index') }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="ndvi_value">NDVI (-1.0 to 1.0)</label>
                            <input type="number" step="0.001" min="-1" max="1" id="ndvi_value"
                                name="ndvi_value" placeholder="e.g. 0.452" class="form-control"
                                style="height: 35px;" value="{{ old('ndvi_value') }}">
                        </div>
                        <div class="form-group">
                            <label for="vegetation_cover_percent">Veg Cover (%)</label>
                            <input type="number" step="0.1" min="0" max="100"
                                id="vegetation_cover_percent" name="vegetation_cover_percent" placeholder="e.g. 75.5"
                                class="form-control" style="height: 35px;"
                                value="{{ old('vegetation_cover_percent') }}">
                        </div>
                        <div class="form-group">
                            <label for="vegetation_condition">Veg Condition</label>
                            <select id="vegetation_condition" name="vegetation_condition" class="form-control"
                                style="background: #fff; height: 35px;">
                                <option value="">-- Select --</option>
                                <option value="Healthy"
                                    {{ old('vegetation_condition') === 'Healthy' ? 'selected' : '' }}>Healthy</option>
                                <option value="Moderate"
                                    {{ old('vegetation_condition') === 'Moderate' ? 'selected' : '' }}>Moderate
                                </option>
                                <option value="Stressed"
                                    {{ old('vegetation_condition') === 'Stressed' ? 'selected' : '' }}>Stressed
                                </option>
                                <option value="Degraded"
                                    {{ old('vegetation_condition') === 'Degraded' ? 'selected' : '' }}>Degraded
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                    <div class="form-group">
                        <label for="image_file">Supporting Image <span style="color: red;">*</span></label>
                        <input type="file" id="image_file" name="image_file" accept="image/*"
                            style="width: 100%; font-size: 12px;" required>
                        <small style="color: #666; font-size: 11px; display: block; margin-top: 3px;">PNG, JPG, JPEG up
                            to 4MB</small>
                    </div>
                    <div class="form-group">
                        <label for="csv_file">Supporting CSV Data <span style="color: red;">*</span></label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt"
                            style="width: 100%; font-size: 12px;" required>
                        <small style="color: #666; font-size: 11px; display: block; margin-top: 3px;">CSV file with
                            scientific details</small>
                    </div>
                </div>

                <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                    <button type="submit" class="btn-submit">Submit Report</button>
                    <a href="{{ route('public.dashboard') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCustomFlora(selectElement) {
            const customGroup = document.getElementById('custom-flora-group');
            const customInput = document.getElementById('flora_name_custom');

            if (selectElement.value === 'custom' || selectElement.value === '') {
                customGroup.style.display = 'block';
                customInput.required = true;
            } else {
                customGroup.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        }

        // Initialize visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('flora_id');
            if (selectElement) {
                toggleCustomFlora(selectElement);
            }
        });
    </script>

</body>

</html>
