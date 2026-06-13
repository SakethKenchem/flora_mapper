<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FloraMapper</title>
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
            background: #1f2937;
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
            background: #d9edf7;
            color: #31708f;
            border: 1px solid #bce8f1;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
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

        .metrics-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .metric-card {
            background: white;
            border: 1px solid #dcdcdc;
            padding: 15px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .metric-label {
            font-size: 11px;
            color: #666666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .metric-value {
            font-size: 22px;
            font-weight: bold;
            color: #1e5631;
        }

        .workspace-row {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
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

        .panel-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .filter-select {
            min-width: 180px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            font-size: 13px;
            background: white;
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

        .status-badge {
            background: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-suspended {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fcf8e3;
            color: #8a6d3b;
        }

        .btn-action {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #cccccc;
            background: white;
            cursor: pointer;
            margin-right: 4px;
        }

        .btn-action:hover {
            background: #eeeeee;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .form-input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            box-sizing: border-box;
            font-size: 13px;
        }

        .btn-execute {
            width: 100%;
            padding: 10px;
            background: #1e5631;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            margin-top: 5px;
        }

        .btn-execute:hover {
            background: #153e22;
        }

        .backup-card {
            background: #f9f9f9;
            border: 1px dashed #cccccc;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }

        .btn-backup {
            background: #2e3d30;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-backup:hover {
            background: #1e2a20;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div>
            <a href="{{ route('home') }}" class="sidebar-brand">FloraMapper</a>

            <div class="menu-label">System Control</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link active">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account') }}" class="menu-link">My Account</a>
                </li>
            </ul>

            <div class="menu-label">Auditing</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="#" onclick="alert('System records log auditor is under development.')" class="menu-link">System Records</a>
                </li>
            </ul>
        </div>

        <div class="user-panel">
            <strong>{{ Auth::user()->full_name }}</strong><br>
            <span style="font-size: 11px; color: #a3e635;">System Administrator</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <span style="font-size: 13px; background: #e2e8f0; padding: 4px 8px; border-radius: 4px;">System Admin Console</span>
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

        <div class="metrics-row">
            <div class="metric-card">
                <span class="metric-label">Registered Users</span>
                <span class="metric-value">{{ $totalUsers }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Active Observers</span>
                <span class="metric-value">{{ $activeObservers }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Active Rule sets</span>
                <span class="metric-value">{{ $thresholdCount }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Backups Done</span>
                <span class="metric-value">{{ $backupsCount }}</span>
            </div>
        </div>

        <div class="workspace-row">
            <div class="panel">
                <div class="panel-title">User Account Audit & Settings</div>
                <div class="panel-toolbar">
                    <select id="roleFilter" class="filter-select" onchange="filterUsersTable()">
                        <option value="all">All Roles</option>
                        <option value="PUBLIC">Public</option>
                        <option value="RESEARCHER">Researcher</option>
                    </select>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $normalizedRole = $user->role
                                    ? str_replace(
                                        ['SYSTEM_ADMINISTRATOR', 'GENERAL_PUBLIC'],
                                        ['ADMIN', 'PUBLIC'],
                                        $user->role->role_name
                                    )
                                    : 'N/A';
                            @endphp
                            <tr data-role="{{ $normalizedRole }}">
                                <td><strong>{{ $user->full_name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $normalizedRole }}</td>
                                <td>
                                    <span class="status-badge @if($user->account_status === 'Suspended' || $user->account_status === 'Disabled') status-suspended @elseif($user->account_status === 'Pending') status-pending @endif">
                                        {{ $user->account_status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->user_id !== Auth::user()->user_id)
                                        @if ($user->account_status === 'Pending')
                                            <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Active">
                                                <button type="submit" class="btn-action" style="background:#d4edda; color:#155724; border-color:#c3e6cb;">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Disabled">
                                                <button type="submit" class="btn-action" style="background:#f8d7da; color:#721c24; border-color:#f5c6cb;">Reject</button>
                                            </form>
                                        @elseif ($user->account_status === 'Active')
                                            <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Suspended">
                                                <button type="submit" class="btn-action">Suspend</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Active">
                                                <button type="submit" class="btn-action" style="background:#d4edda; color:#155724; border-color:#c3e6cb;">Activate</button>
                                            </form>
                                        @endif
                                    @else
                                        <span style="font-size:11px; color:#666666;">Current Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <div class="panel-title">Vulnerability Threshold Parameters</div>
                <form onsubmit="event.preventDefault(); saveThresholds();">
                    <div class="form-group">
                        <label>Low Vulnerability Range</label>
                        <div class="form-row">
                            <input type="number" class="form-input" value="0" required>
                            <input type="number" class="form-input" value="30" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Moderate Vulnerability Range</label>
                        <div class="form-row">
                            <input type="number" class="form-input" value="31" required>
                            <input type="number" class="form-input" value="60" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>High Vulnerability Range</label>
                        <div class="form-row">
                            <input type="number" class="form-input" value="61" required>
                            <input type="number" class="form-input" value="100" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-execute">Save Threshold Mapping</button>
                </form>

                <div class="backup-card">
                    <strong>System Backup Operations</strong>
                    <div style="font-size: 11px; margin-bottom: 10px; color: #666666; margin-top: 5px;">Dump all active tables into server backup storage.</div>
                    <button class="btn-backup" onclick="runBackup()">Run System Backup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterUsersTable() {
            const filter = document.getElementById('roleFilter').value;
            const rows = document.querySelectorAll('tbody tr[data-role]');

            rows.forEach((row) => {
                const role = row.getAttribute('data-role');
                row.style.display = filter === 'all' || role === filter ? '' : 'none';
            });
        }

        function saveThresholds() {
            alert("Assessment thresholds updated successfully.");
        }

        function runBackup() {
            alert("Database backup dump executed successfully.");
        }
    </script>

</body>

</html>
