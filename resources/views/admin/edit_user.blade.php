<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Account - FloraMapper</title>
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
                    <a href="{{ route('map') }}" class="menu-link">Map</a>
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
            <h1>Edit User Details</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 600px;">
                @forelse ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @empty
                @endforelse
            </div>
        @endif

        <div class="panel">
            <div class="panel-title">Update Profile Details for {{ $user->full_name }}</div>

            <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                @csrf

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                </div>

                @if ($user->user_id === Auth::user()->user_id)
                    <!-- Admin editing themselves: hide role and status to prevent lockout -->
                    <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                    <input type="hidden" name="account_status" value="{{ $user->account_status }}">
                @else
                    <div class="form-group">
                        <label for="role_id">System Role</label>
                        <select id="role_id" name="role_id" class="form-control" onchange="toggleRoleFields(this.value)" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}" {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>
                                    @if ($role->role_name === 'SYSTEM_ADMINISTRATOR') Administrator
                                    @elseif ($role->role_name === 'RESEARCHER') Researcher
                                    @else Public Observer
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="account_status">Account Status</label>
                        <select id="account_status" name="account_status" class="form-control" required>
                            <option value="Active" {{ old('account_status', $user->account_status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Pending" {{ old('account_status', $user->account_status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Suspended" {{ old('account_status', $user->account_status) === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="Disabled" {{ old('account_status', $user->account_status) === 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                @endif

                <!-- Researcher specific fields -->
                <div id="researcher-fields" style="display: none;">
                    <div class="form-group">
                        <label for="institution">Institution (Required for Researchers)</label>
                        <input type="text" id="institution" name="institution" class="form-control" value="{{ old('institution', $user->institution) }}">
                    </div>

                    <div class="form-group">
                        <label for="specialisation">Field of Specialisation</label>
                        <input type="text" id="specialisation" name="specialisation" class="form-control" value="{{ old('specialisation', $user->specialisation) }}">
                    </div>
                </div>

                <!-- Public observer specific fields -->
                <div id="public-fields" style="display: none;">
                    <div class="form-group">
                        <label for="preferred_region">Preferred Ecosystem Region</label>
                        <input type="text" id="preferred_region" name="preferred_region" class="form-control" value="{{ old('preferred_region', $user->preferred_region) }}" placeholder="e.g. Mau Forest Complex">
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-submit">Update User</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleRoleFields(roleId) {
            const researcherFields = document.getElementById('researcher-fields');
            const publicFields = document.getElementById('public-fields');
            const institutionInput = document.getElementById('institution');

            if (roleId == '2') { // Researcher
                researcherFields.style.display = 'block';
                publicFields.style.display = 'none';
                if (institutionInput) {
                    institutionInput.setAttribute('required', 'required');
                }
            } else if (roleId == '1') { // Public Observer
                researcherFields.style.display = 'none';
                publicFields.style.display = 'block';
                if (institutionInput) {
                    institutionInput.removeAttribute('required');
                }
            } else { // Admin or others
                researcherFields.style.display = 'none';
                publicFields.style.display = 'none';
                if (institutionInput) {
                    institutionInput.removeAttribute('required');
                }
            }
        }

        // Initialize visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            if (roleSelect) {
                toggleRoleFields(roleSelect.value);
            } else {
                // If role select doesn't exist (self editing), toggle based on actual user role
                const userRoleId = "{{ $user->role_id }}";
                toggleRoleFields(userRoleId);
            }
        });
    </script>
</body>

</html>
