<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - FloraMapper</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f4;
            color: #333333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .account-box {
            background: white;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .account-box h2 {
            color: #1e5631;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
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

        .form-control:disabled {
            background: #f9f9f9;
            color: #666666;
            cursor: not-allowed;
        }

        .btn-submit {
            background: #1e5631;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #153e22;
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

        .footer-links {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }

        .footer-links a {
            color: #1e5631;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="account-box">
        <h2>My Account Settings</h2>

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

        <form method="POST" action="{{ route('account.update') }}">
            researcher.deleteUploads

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', Auth::user()->full_name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="+254..." value="{{ old('phone_number', Auth::user()->phone_number) }}">
            </div>

            @if (Auth::user()->isResearcher() && Auth::user()->institution)
                <div class="form-group">
                    <label for="institution">Institution</label>
                    <input type="text" id="institution" class="form-control" value="{{ Auth::user()->institution }}" disabled>
                </div>
            @endif

            <div class="form-group">
                <label for="role">Account Role</label>
                <input type="text" id="role" class="form-control" value="{{ Auth::user()->role ? Auth::user()->role->role_name : 'N/A' }}" disabled>
            </div>

            <div class="form-group">
                <label for="status">Account Status</label>
                <input type="text" id="status" class="form-control" value="{{ Auth::user()->account_status }}" disabled>
            </div>

            @if (Auth::user()->isPublic())
                <div class="form-group">
                    <label for="preferred_region">Preferred Observation Region</label>
                    <input type="text" id="preferred_region" name="preferred_region" class="form-control" placeholder="e.g. Mau Forest, Tana Delta..." value="{{ old('preferred_region', Auth::user()->preferred_region) }}">
                </div>
            @endif

            @if (Auth::user()->isResearcher())
                <div class="form-group">
                    <label for="specialisation">Specialisation Area</label>
                    <input type="text" id="specialisation" name="specialisation" class="form-control" placeholder="e.g. Mangrove Ecology, Botany..." value="{{ old('specialisation', Auth::user()->specialisation) }}">
                </div>

                <div class="form-group">
                    <label for="upload_count">Dataset Upload Count</label>
                    <input type="text" id="upload_count" class="form-control" value="{{ Auth::user()->upload_count }}" disabled>
                </div>

                <div class="form-group">
                    <label for="last_upload_date">Last Upload Date</label>
                    <input type="text" id="last_upload_date" class="form-control" value="{{ Auth::user()->last_upload_date ?? 'No uploads yet' }}" disabled>
                </div>
            @endif

            @if (Auth::user()->isAdmin())
                <div class="form-group">
                    <label for="admin_level">Admin Level</label>
                    <input type="text" id="admin_level" class="form-control" value="Level {{ Auth::user()->admin_level }}" disabled>
                </div>

                <div class="form-group">
                    <label for="last_login">Last Active Login</label>
                    <input type="text" id="last_login" class="form-control" value="{{ Auth::user()->last_login ? Auth::user()->last_login->format('Y-m-d H:i:s') : 'N/A' }}" disabled>
                </div>
            @endif

            <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 20px 0;">
            <p style="font-size: 12px; color: #666666; margin-bottom: 15px;">Leave password fields blank if you do not wish to change your current password.</p>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn-submit">Update Profile Settings</button>
        </form>

        {{-- @if (Auth::user()->isResearcher())
            <!--button to delete uploaded datasets-->
            <form method="POST" action="{{ route('researcher.deleteUploads') }}" style="margin-top: 15px;" onsubmit="return confirm('Are you absolutely sure you want to delete all your uploaded datasets? This will remove all associated climate, vegetation, and flora records and cannot be undone.');">
                @csrf
                <button type="submit" class="btn-submit" style="background: #d9534f; margin-top: 10px;">Delete All Uploaded Datasets</button>
            </form>
        @endif --}}

        <div class="footer-links">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">⬅ Back to Admin Dashboard</a>
            @elseif(Auth::user()->isResearcher())
                <a href="{{ route('researcher.dashboard') }}">⬅ Back to Researcher Dashboard</a>
            @else
                <a href="{{ route('public.dashboard') }}">⬅ Back to Observer Dashboard</a>
            @endif
        </div>
    </div>

</body>

</html>
