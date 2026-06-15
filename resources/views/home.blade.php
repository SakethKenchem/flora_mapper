<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Vulnerability Mapping System</title>
    @php
        $heroImage = base64_encode(file_get_contents(resource_path('images/Proteas-on-top-of-Mt-Kenya.jpg')));
    @endphp
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f4;
            color: #333333;
        }

        .navbar {
            background: #1e5631;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .hero {
            padding: 50px 30px;
            background:
                linear-gradient(rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.82)),
                url('data:image/jpeg;base64,{{ $heroImage }}') center/cover no-repeat;
            border-bottom: 2px solid #e0e0e0;
            text-align: center;
        }

        .hero h1 {
            color: #1e5631;
            margin-bottom: 15px;
            font-size: 32px;
        }

        .hero p {
            max-width: 800px;
            margin: 0 auto 30px;
            font-size: 16px;
            line-height: 1.6;
            color: #666666;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-primary {
            background: #1e5631;
            color: white;
            border: 1px solid #1e5631;
        }

        .btn-secondary {
            background: #ffffff;
            color: #1e5631;
            border: 2px solid #1e5631;
        }

        .modules-section {
            padding: 40px 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .modules-title {
            text-align: center;
            color: #1e5631;
            margin-bottom: 30px;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .module-card {
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 20px;
        }

        .module-card h3 {
            color: #1e5631;
            margin-top: 0;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 10px;
        }

        .module-card p {
            line-height: 1.5;
            font-size: 14px;
            color: #555555;
        }

        footer {
            background: #1e5631;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <a href="{{ route('home') }}" style="font-size: 20px;">Flora Vulnerability System</a>

        <div class="navbar-links">
            <a href="{{ route('map') }}">Map</a>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @elseif(auth()->user()->isResearcher())
                    <a href="{{ route('researcher.dashboard') }}">Researcher Dashboard</a>
                @else
                    <a href="{{ route('public.dashboard') }}">My Dashboard</a>
                @endif
                <a href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}"
                    style="background: white; color: #1e5631; padding: 6px 12px; border-radius: 4px;">Register</a>
            @endauth
        </div>
    </div>

    <section class="hero">
        <h1>Climate-Sensitive Flora Vulnerability Mapping System</h1>
        <p>
            A web-based system designed for tracking vulnerable flora in Kenya. Public users can view the interactive
            vulnerability map and submit observations. Registered researchers can upload climate/NDVI datasets and run
            vulnerability assessments.
        </p>

        <div>
            <a href="{{ route('map') }}" class="btn btn-primary">View Vulnerability Map</a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-secondary">Public Registration</a>
                <a href="{{ route('register.researcher') }}" class="btn btn-secondary">Researcher Registration</a>
            @else
                @if (auth()->user()->isPublic())
                    <a href="{{ route('public.dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
                @elseif(auth()->user()->isResearcher())
                    <a href="{{ route('researcher.dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
                @endif
            @endguest
        </div>
    </section>

    <section class="modules-section">
        <h2 class="modules-title">System Modules & Portals</h2>

        <div class="modules-grid">
            <div class="module-card">
                <h3>General Public Portal</h3>
                <p>
                    Open observer accounts, inspect the Leaflet.js interactive vulnerability maps with flora location
                    markers, filter flora parameters, and upload flora observation reports.
                </p>
            </div>

            <div class="module-card">
                <h3>Researcher Console</h3>
                <p>
                    Import climate dataset files and NDVI vegetation files, manage flora record profiles, execute
                    vulnerability computation models, and approve or reject observation reports.
                </p>
            </div>

            <div class="module-card">
                <h3>Administration Deck</h3>
                <p>
                    Activate, suspend, or update system user accounts, edit vulnerability index threshold brackets,
                    audit system records, and trigger manual database backups.
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Flora Vulnerability Mapping System.</p>
    </footer>

</body>

</html>
