<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observer Registration - FloraMapper</title>
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

        .register-box {
            background: white;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .register-box h2 {
            color: #1e5631;
            margin-top: 0;
            margin-bottom: 5px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666666;
            margin-bottom: 20px;
            font-size: 13px;
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

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer-links a {
            color: #1e5631;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="register-box">
        <h2>Observer Register</h2>
        <p class="subtitle">Create an account as a General Public user to report observations</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number (Optional)</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="+254..." value="{{ old('phone_number') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn-submit">Register Observer</button>
        </form>

        <div class="footer-links">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
            <br><br>
            Are you a scientist? <a href="{{ route('register.researcher') }}">Register as Researcher</a>
            <br><br>
            <a href="{{ route('home') }}">Back to Home</a>
        </div>
    </div>

</body>

</html>