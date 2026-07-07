<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Public Observation - FloraMapper</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            color: #1e5631;
            font-size: 24px;
        }

        .btn-back {
            background: #e2e8f0;
            color: #333333;
            border: 1px solid #cccccc;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-back:hover {
            background: #cbd5e1;
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

        .detail-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 25px;
        }

        .table-meta {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-meta tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .table-meta td {
            padding: 10px 0;
        }

        .meta-label {
            font-weight: bold;
            color: #64748b;
            width: 35%;
        }

        .meta-value {
            color: #1e5631;
            font-weight: 500;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background: #fafdfb;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
        }

        .metrics-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .metrics-table td {
            padding: 8px 0;
        }

        .img-preview {
            max-width: 100%;
            max-height: 250px;
            border-radius: 4px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .badge-status {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
        }

        .decision-box {
            background: #fafafa;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 4px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            box-sizing: border-box;
            font-size: 13px;
            margin-bottom: 15px;
            resize: vertical;
        }

        .btn-execute {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 13px;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-approve {
            background: #1e5631;
        }

        .btn-approve:hover {
            background: #153e22;
        }

        .btn-reject {
            background: #a94442;
        }

        .btn-reject:hover {
            background: #843534;
        }

        .csv-table-wrapper {
            overflow-x: auto;
            max-height: 250px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .csv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: left;
        }

        .csv-table th {
            background: #f8fafc;
            border-bottom: 2px solid #cbd5e1;
            padding: 10px;
            font-weight: bold;
            color: #334155;
            position: sticky;
            top: 0;
        }

        .csv-table td {
            padding: 8px 10px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div>
            <span class="sidebar-brand">FloraMapper</span>
            
            <div class="menu-label">Account</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.dashboard') }}" class="menu-link">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('public.search') }}" class="menu-link">Search Registry</a>
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
                    <a href="{{ route('researcher.compare') }}" class="menu-link">Compare Regions</a>
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
            <h1>Review Public Observation</h1>
            <a href="{{ route('researcher.dashboard') }}" class="btn-back">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:12px; border-radius:4px; margin-bottom:20px; font-size:14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Observation Details</div>
            
            <div class="detail-grid">
                <div>
                    <table class="table-meta">
                        <tr>
                            <td class="meta-label">Flora Species Name:</td>
                            <td class="meta-value" style="font-size: 16px;">
                                <a href="https://www.google.com/search?q={{ urlencode($observation->flora_name) }}" target="_blank" rel="noopener noreferrer">
                                    {{ $observation->flora_name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="meta-label">Location / Landmark:</td>
                            <td>{{ $observation->location }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Date Observed:</td>
                            <td>{{ $observation->date_observed ? $observation->date_observed->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Date Submitted:</td>
                            <td>{{ $observation->submission_date ? $observation->submission_date->format('Y-m-d H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Current Status:</td>
                            <td>
                                @if($observation->status === 'Approved')
                                    <span class="badge-status" style="color: #155724; background: #d4edda; border: 1px solid #c3e6cb;">Approved</span>
                                @elseif($observation->status === 'Rejected')
                                    <span class="badge-status" style="color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb;">Rejected</span>
                                @else
                                    <span class="badge-status" style="color: #8a6d3b; background: #fcf8e3; border: 1px solid #faf2cc;">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <h4 style="margin: 20px 0 8px 0; color: #1e5631; font-size: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">Observer Contact</h4>
                    <table class="table-meta">
                        <tr>
                            <td class="meta-label">Full Name:</td>
                            <td>{{ $observation->observer ? $observation->observer->full_name : 'Anonymous Public Observer' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Email Address:</td>
                            <td>{{ $observation->observer ? $observation->observer->email : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Phone Number:</td>
                            <td>{{ $observation->observer ? $observation->observer->phone_number : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 10px;">
                    <h4 style="margin: 0 0 10px 0; color: #1e5631; font-size: 14px; align-self: flex-start; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; width: 100%;">Submitted Photo</h4>
                    @if($observation->image_path)
                        <img src="{{ asset('storage/' . $observation->image_path) }}" alt="Observation photo" class="img-preview">
                    @else
                        <div style="background: #f1f5f9; border: 1px dashed #cbd5e1; height: 160px; width: 100%; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 13px;">
                            No image uploaded by the observer.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title">Environmental Field Parameters</div>
            <div class="metrics-grid">
                <div>
                    <table class="metrics-table">
                        <tr>
                            <td style="font-weight: bold; color: #64748b; width: 50%;">Temperature:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->temperature_celsius !== null ? $observation->temperature_celsius . ' °C' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #64748b;">Rainfall:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->rainfall_mm !== null ? $observation->rainfall_mm . ' mm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #64748b;">Humidity:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->humidity_percent !== null ? $observation->humidity_percent . ' %' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #64748b;">Drought Index:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->drought_index !== null ? $observation->drought_index : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="metrics-table">
                        <tr>
                            <td style="font-weight: bold; color: #64748b; width: 50%;">NDVI Value:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->ndvi_value !== null ? $observation->ndvi_value : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #64748b;">Vegetation Cover:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->vegetation_cover_percent !== null ? $observation->vegetation_cover_percent . ' %' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #64748b;">Vegetation Condition:</td>
                            <td style="font-weight: bold; color: #1e5631;">{{ $observation->vegetation_condition !== null ? $observation->vegetation_condition : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title">Observation Field Notes</div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; font-size: 13px; line-height: 1.6; color: #334155; white-space: pre-line;">{{ $observation->description ?? 'No field notes provided.' }}</div>
        </div>

        @if($observation->csv_path)
            <div class="panel">
                <div class="panel-title">Supporting Dataset Preview (CSV)</div>
                @if(isset($csvData['headers']) && count($csvData['headers']) > 0)
                    <div class="csv-table-wrapper">
                        <table class="csv-table">
                            <thead>
                                <tr>
                                    @foreach($csvData['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($csvData['rows'] as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td>{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="background: #f1f5f9; border: 1px dashed #cbd5e1; padding: 15px; border-radius: 4px; text-align: center; color: #64748b; font-size: 13px;">
                        No CSV dataset preview available or file has been deleted.
                    </div>
                @endif
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Audit Decision Form</div>
            @if($observation->status === 'Pending')
                <div class="decision-box">
                    <form method="POST" action="{{ route('researcher.observations.review', $observation->observation_id) }}">
                        @csrf
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="review_comment" style="display: block; font-weight: bold; font-size: 13px; margin-bottom: 5px;">Review Comments / Feedback for Observer</label>
                            <textarea id="review_comment" name="review_comment" rows="4" class="form-control" placeholder="Provide evaluation comments, validation details or rejection reasons here..."></textarea>
                        </div>
                        <div style="display: flex; gap: 12px;">
                            <button type="submit" name="status" value="Approved" class="btn-execute btn-approve">Approve Report</button>
                            <button type="submit" name="status" value="Rejected" class="btn-execute btn-reject">Reject Report</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="decision-box" style="background: #f8fafc;">
                    <div style="font-size: 13px; margin-bottom: 10px;">
                        <strong>Review Action Comment:</strong><br>
                        <span style="font-style: italic; color: #555;">"{{ $observation->review_comment ?? 'No comment provided.' }}"</span>
                    </div>
                    <div style="font-size: 11px; color: #64748b;">
                        Reviewed by: <strong>{{ $observation->reviewer ? $observation->reviewer->full_name : 'Researcher' }}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

</body>

</html>
