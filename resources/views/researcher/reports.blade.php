<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Manager - FloraMapper</title>
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .workspace-row {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 20px;
        }

        .panel {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
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
            width: 100%;
        }

        .btn-submit:hover {
            background: #153e22;
        }

        .report-item {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .report-title {
            font-weight: bold;
            font-size: 15px;
            color: #1e5631;
        }

        .report-meta {
            font-size: 11px;
            color: #666666;
        }

        .report-content {
            font-size: 13px;
            white-space: pre-wrap;
            line-height: 1.5;
            color: #4a5568;
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
                    <a href="{{ route('researcher.reports') }}" class="menu-link active">Reports Manager</a>
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
            <h1>Reports Manager</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">Researcher Reports</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="workspace-row">
            <div class="panel">
                <div class="panel-title">Saved Analytical Reports</div>
                
                @forelse($reports as $report)
                    <div class="report-item">
                        <div class="report-header">
                            <div>
                                <div class="report-title">{{ $report->report_title }}</div>
                                <div class="report-meta">
                                    Type: <strong>{{ $report->report_type }}</strong> | By: <strong>{{ $report->creator ? $report->creator->full_name : 'System' }}</strong>
                                </div>
                            </div>
                            <div class="report-meta" style="text-align: right;">
                                {{ $report->created_at ? $report->created_at->format('Y-m-d H:i') : 'N/A' }}
                            </div>
                        </div>
                        <div class="report-content">{{ $report->content }}</div>
                    </div>
                @empty
                    <p style="text-align: center; color: #666666; font-size: 13px; margin: 30px 0;">No compiled reports found. Use the panel on the right to compile a new report.</p>
                @endforelse
            </div>

            <div class="panel">
                <div class="panel-title">Compile New Report</div>
                <form method="POST" action="{{ route('researcher.reports.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="report_title">Report Title</label>
                        <input type="text" id="report_title" name="report_title" class="form-control" placeholder="e.g. Tana Delta Mangrove Assessment" value="{{ old('report_title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="report_type">Report Type</label>
                        <select id="report_type" name="report_type" class="form-control" required>
                            <option value="Ecosystem Vulnerability Assessment" {{ old('report_type') == 'Ecosystem Vulnerability Assessment' ? 'selected' : '' }}>Ecosystem Vulnerability Assessment</option>
                            <option value="Species Registry Audit" {{ old('report_type') == 'Species Registry Audit' ? 'selected' : '' }}>Species Registry Audit</option>
                            <option value="Climate Impact Summary" {{ old('report_type') == 'Climate Impact Summary' ? 'selected' : '' }}>Climate Impact Summary</option>
                            <option value="Public Observations Synthesis" {{ old('report_type') == 'Public Observations Synthesis' ? 'selected' : '' }}>Public Observations Synthesis</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="assessment_id">Link to Vulnerability Assessment (Optional)</label>
                        <select id="assessment_id" name="assessment_id" class="form-control">
                            <option value="">-- No Linked Assessment --</option>
                            @foreach($assessments as $assessment)
                                <option value="{{ $assessment->assessment_id }}" {{ old('assessment_id') == $assessment->assessment_id ? 'selected' : '' }}>
                                    Assessed {{ $assessment->region->region_name }} ({{ $assessment->vulnerability_level }}) - Score: {{ $assessment->overall_score }}% - Run by: {{ $assessment->creator ? $assessment->creator->full_name : 'System' }} ({{ $assessment->created_at->format('Y-m-d') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Assessment Notes & Comments</label>
                        <textarea id="notes" name="notes" rows="8" class="form-control" placeholder="Enter findings, observations, recommendations, and analytical notes here..." required>{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit">Compile & Save Report</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
