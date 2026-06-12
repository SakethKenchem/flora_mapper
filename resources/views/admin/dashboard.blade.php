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

        .btn-action {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #cccccc;
            background: white;
            cursor: pointer;
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
                    <a href="#" onclick="alert('Manage user accounts in the table below!')" class="menu-link">Manage Accounts</a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="alert('Threshold configurator is preloaded in the right panel!')" class="menu-link">Configure Thresholds</a>
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
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="metrics-row">
            <div class="metric-card">
                <span class="metric-label">Registered Users</span>
                <span class="metric-value">3</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Active Observers</span>
                <span class="metric-value">1</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Active Rule sets</span>
                <span class="metric-value">1</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Backups Done</span>
                <span class="metric-value">12</span>
            </div>
        </div>

        <div class="workspace-row">
            <div class="panel">
                <div class="panel-title">User Account Audit & Settings</div>
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
                        <tr id="user-row-1">
                            <td>System Administrator</td>
                            <td>admin@floramapper.com</td>
                            <td>ADMIN</td>
                            <td><span class="status-badge" id="status-badge-1">Active</span></td>
                            <td>
                                <button class="btn-action" onclick="toggleUserStatus(1)">Suspend</button>
                            </td>
                        </tr>
                        <tr id="user-row-2">
                            <td>Dr. Jane Mwangi</td>
                            <td>researcher@floramapper.com</td>
                            <td>RESEARCHER</td>
                            <td><span class="status-badge" id="status-badge-2">Active</span></td>
                            <td>
                                <button class="btn-action" onclick="toggleUserStatus(2)">Suspend</button>
                            </td>
                        </tr>
                        <tr id="user-row-3">
                            <td>John Doe</td>
                            <td>public@floramapper.com</td>
                            <td>PUBLIC</td>
                            <td><span class="status-badge" id="status-badge-3">Active</span></td>
                            <td>
                                <button class="btn-action" onclick="toggleUserStatus(3)">Suspend</button>
                            </td>
                        </tr>
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
        function toggleUserStatus(id) {
            const badge = document.getElementById(`status-badge-${id}`);
            const btn = event.target;
            if (badge.classList.contains('status-suspended')) {
                badge.classList.remove('status-suspended');
                badge.innerText = 'Active';
                btn.innerText = 'Suspend';
                alert(`User account #${id} is now active.`);
            } else {
                badge.classList.add('status-suspended');
                badge.innerText = 'Suspended';
                btn.innerText = 'Activate';
                alert(`User account #${id} has been suspended.`);
            }
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
