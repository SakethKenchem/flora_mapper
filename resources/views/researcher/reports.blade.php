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

        .workspace-row {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
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

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #f9f9f9;
            border-bottom: 2px solid #dddddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
        }

        .btn-action {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #1e5631;
            background: #1e5631;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-action:hover {
            background: #153e22;
            border-color: #153e22;
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        /* Printable modal styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #report-print-content, #report-print-content * {
                visibility: visible;
            }
            #report-print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
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
                    <a href="{{ route('researcher.compare') }}" class="menu-link">Compare Regions</a>
                </li>
            </ul>

            <div class="menu-label">Flora & Reports</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('researcher.flora.manage') }}" class="menu-link">Flora Registry</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('researcher.reports') }}" class="menu-link active">Reports Manager</a>
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
            <h1>Reports Manager</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="workspace-row">
            <!-- Compiled Reports Log -->
            <div class="panel">
                <div class="panel-title">Compiled Analytical Summary Reports</div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Report Title</th>
                                <th>Report Type</th>
                                <th>Generated By</th>
                                <th>Date Generated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td><strong>{{ $report->report_title }}</strong></td>
                                    <td>{{ $report->report_type }}</td>
                                    <td>{{ $report->creator ? $report->creator->full_name : 'System' }}</td>
                                    <td>{{ $report->created_at ? $report->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                    <td>
                                        <button onclick="openReportModal({{ $report->report_id }})" class="btn-action">View Report</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #777777; padding: 20px;">
                                        No reports have been compiled yet. Use the compiler panel on the right.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Generate Report Form -->
            <div class="panel" style="align-self: flex-start;">
                <div class="panel-title">Compile New Report</div>
                <form method="POST" action="{{ route('researcher.reports.generate') }}">
                    @csrf

                    <div class="form-group">
                        <label for="report_title">Report Title</label>
                        <input type="text" id="report_title" name="report_title" class="form-control" placeholder="e.g. Q3 Regional Vulnerability Assessment" required>
                    </div>

                    <div class="form-group">
                        <label for="report_type">Report Focus Area</label>
                        <select id="report_type" name="report_type" class="form-control" required>
                            <option value="">Select Report Focus</option>
                            <option value="vulnerability_summary">Ecosystem Vulnerability Summary</option>
                            <option value="observations_summary">Public Observations Summary</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Compile Analytical Summary</button>
                </form>
            </div>
        </div>
    </div>

    <!-- View Report Modal -->
    <div id="report-view-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
        <div style="background: white; border: 1px solid #cccccc; border-radius: 6px; width: 100%; max-width: 800px; max-height: 90%; overflow-y: auto; padding: 30px; box-sizing: border-box; position: relative; font-family: Arial, sans-serif; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <button onclick="closeReportModal()" class="no-print" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; font-weight: bold; cursor: pointer; color: #666666;">&times;</button>
            
            <div id="report-print-content">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #1e5631; padding-bottom: 10px; margin-bottom: 20px;">
                    <div>
                        <h1 id="modal-report-title" style="margin: 0; color: #1e5631; font-size: 20px; font-weight: bold;">Report Title</h1>
                        <span id="modal-report-type" style="font-size: 12px; color: #666666; font-weight: bold;">Report Type</span>
                    </div>
                    <div style="text-align: right; font-size: 11px; color: #555555; line-height: 1.4;">
                        <strong>Generated by:</strong> <span id="modal-report-creator"></span><br>
                        <strong>Date:</strong> <span id="modal-report-date"></span>
                    </div>
                </div>

                <div id="modal-report-body" style="font-size: 13px; line-height: 1.6; color: #333333;">
                    <!-- dynamic report contents go here -->
                </div>
            </div>

            <div class="no-print" style="margin-top: 30px; text-align: right; border-top: 1px solid #eeeeee; padding-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                <button onclick="window.print()" style="background: #1e5631; border: 1px solid #1e5631; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: white;">Print Report</button>
                <button onclick="closeReportModal()" style="background: #e2e8f0; border: 1px solid #cccccc; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; color: #333333;">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openReportModal(reportId) {
            const modal = document.getElementById('report-view-modal');
            if (!modal) return;

            document.getElementById('modal-report-title').innerText = "Loading Report...";
            document.getElementById('modal-report-body').innerHTML = "";
            modal.style.display = 'flex';

            fetch(`/researcher/reports/${reportId}`)
                .then(res => res.json())
                .then(data => {
                    const r = data.report;
                    document.getElementById('modal-report-title').innerText = r.report_title;
                    document.getElementById('modal-report-type').innerText = r.report_type;
                    document.getElementById('modal-report-creator').innerText = r.creator ? r.creator.full_name : 'System';
                    document.getElementById('modal-report-date').innerText = data.formatted_date;
                    document.getElementById('modal-report-body').innerHTML = r.content;
                })
                .catch(err => {
                    console.error("Error fetching report details:", err);
                    document.getElementById('modal-report-title').innerText = "Error Loading Report";
                });
        }

        function closeReportModal() {
            document.getElementById('report-view-modal').style.display = 'none';
        }
    </script>

</body>

</html>
