<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi KKN')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet" />
    <script src="https://unpkg.com/htmx.org@1.9.12" integrity="sha384-ujb1lRIkbPla3geNTMBbE1P4ONRxjV+cDF9GRVYwyIAtKC3PIpIBpak3411GChNj" crossorigin="anonymous"></script>
</head>
<body hx-boost="true">
    @auth
    <nav class="nav-bar">
        <div class="container nav-content">
            <a href="#" class="nav-brand">
                <span class="material-symbols-rounded" style="font-size: 2rem;">location_on</span>
                Absen KKN
            </a>
            <div class="nav-links {{ Auth::user()->role === 'admin' ? 'nav-links-admin' : 'nav-links-student' }}">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"><span class="material-symbols-rounded">dashboard</span> Dashboard</a>
                    <a href="{{ route('admin.students') }}"><span class="material-symbols-rounded">groups</span> Mahasiswa</a>
                    <a href="{{ route('admin.attendances') }}"><span class="material-symbols-rounded">fact_check</span> Kehadiran</a>
                    <a href="{{ route('admin.settings') }}"><span class="material-symbols-rounded">settings</span> Pengaturan</a>
                @else
                    <a href="{{ route('student.dashboard') }}"><span class="material-symbols-rounded">home</span> Dashboard</a>
                    <a href="{{ route('student.history') }}"><span class="material-symbols-rounded">history</span> Riwayat</a>
                @endif
                
                @if(Auth::user()->role === 'admin')
                    <div class="nav-profile-link">
                        <div class="img-avatar" style="width: 32px; height: 32px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-rounded text-primary" style="font-size: 1.2rem;">person</span>
                        </div>
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-primary);">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </div>
                @else
                    <a href="{{ route('student.profile') }}" class="nav-profile-link">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Avatar" class="img-avatar" style="width: 32px; height: 32px;">
                        @else
                            <div class="img-avatar" style="width: 32px; height: 32px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center;">
                                <span class="material-symbols-rounded text-primary" style="font-size: 1.2rem;">person</span>
                            </div>
                        @endif
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-primary);">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'admin')
                    <form action="{{ route('logout') }}" method="POST" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" style="color:var(--danger);">
                            <span class="material-symbols-rounded">logout</span> Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </nav>
    @endauth

    <main class="container mt-8 mb-8">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
