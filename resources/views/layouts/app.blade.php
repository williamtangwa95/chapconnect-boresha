<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Chap Connect - Talent Directory')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Style.css') }}">
    @yield('styles')
</head>

<body class="{{ Request::is('admin*') ? 'admin-body' : '' }}">
    @if(!Request::is('admin*'))
    <nav class="nav">
        <div class="logo">
            <a href="{{ route('home') }}">
                <!-- Use logo from backup or default placeholder if not copied yet -->
                <img src="/logo.png" alt="Chap Connect Logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
            </a>
            <a href="{{ route('home') }}">
                <h1>Chap Connect</h1>
            </a>
        </div>
        <div class="icon">
            <div class="menu">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>

                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}" style="color: var(--accent-pink);">Admin Dashboard</a></li>
                    @else
                    <li><a href="{{ route('dashboard') }}">Dashboard Panel</a></li>
                    @endif
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444;">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}" style="background: var(--accent); color: white; border-color: transparent;">Register</a></li>
                    @endauth
                </ul>
            </div>

            @yield('search_bar')
        </div>
    </nav>
    @endif

    <!-- Global alert messages -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ul style="display: block; list-style: disc; margin-left: 20px;">
            @foreach($errors->all() as $error)
            <li style="margin-left: 0; margin-top: 0; font-size: 0.85rem; width: auto; list-style: disc;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @yield('content')

    <script src="{{ asset('js/image-compressor.js') }}"></script>
    @yield('scripts')
</body>

</html>