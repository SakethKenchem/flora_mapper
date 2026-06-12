<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FloraMapper</title>
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
        }

        .login-box {
            background: white;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .login-box h2 {
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

        .demo-credentials {
            margin-top: 25px;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }

        .demo-credentials h3 {
            font-size: 12px;
            color: #666666;
            margin-top: 0;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .demo-btn {
            display: block;
            width: 100%;
            padding: 6px 10px;
            background: #eeeeee;
            border: 1px solid #cccccc;
            margin-bottom: 6px;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
            font-size: 12px;
            color: #333333;
        }

        .demo-btn:hover {
            background: #dddddd;
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

    <div class="login-box">
        <h2>System Login</h2>

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

        <form id="login-form" method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="demo-credentials">
            <h3>Quick Sign-In Assistant</h3>
            <button class="demo-btn" onclick="fillCredentials('admin@floramapper.com', 'Password123')">
                System Admin: <strong>admin@floramapper.com</strong>
            </button>
            <button class="demo-btn" onclick="fillCredentials('researcher@floramapper.com', 'Password123')">
                Researcher: <strong>researcher@floramapper.com</strong>
            </button>
            <button class="demo-btn" onclick="fillCredentials('public@floramapper.com', 'Password123')">
                General Public: <strong>public@floramapper.com</strong>
            </button>
        </div>

        <div class="footer-links">
            Don't have an account?<br>
            Register as <a href="{{ route('register') }}">General Public</a> or <a href="{{ route('register.researcher') }}">Researcher</a>
            <br><br>
            <a href="{{ route('home') }}">Back to Home</a>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('login-form').submit();
        }
    </script>

</body>

</html>
