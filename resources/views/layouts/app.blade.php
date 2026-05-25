<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SocialConnect - Laravel Acquaintances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
        }
        .btn-primary:hover {
            transform: scale(1.05);
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        .alert {
            border-radius: 10px;
        }
        .user-switch {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 5px 10px;
        }
        .stats-card {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .stats-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        .stats-label {
            color: #666;
            font-size: 14px;
        }
        footer {
            text-align: center;
            padding: 20px;
            color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}" style="font-weight: bold; color: #667eea;">
                <i class="fas fa-users"></i> SocialConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/friend-list">
                            <i class="fas fa-user-friends"></i> Friends
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/following-list">
                            <i class="fas fa-user-plus"></i> Following
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/follower-list">
                            <i class="fas fa-users"></i> Followers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/friend-suggestions">
                            <i class="fas fa-lightbulb"></i> Suggestions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/all-users">
                            <i class="fas fa-globe"></i> All Users
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer>
        <p>&copy; 2026 SocialConnect - Built with Laravel Acquaintances</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>