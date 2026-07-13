@extends('layouts.app')

@section('title', 'Login - Absensi KKN')

@section('content')
<div style="max-width: 420px; margin: 4rem auto; padding: 0 1rem;">
    <div class="glass-panel text-center">
        <div style="background: var(--bg-gradient-start); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <span class="material-symbols-rounded" style="font-size: 3rem; color: var(--primary-color);">account_circle</span>
        </div>
        <h2 style="color: var(--text-primary); margin-top: 0; margin-bottom: 0.5rem;">Selamat Datang! 👋</h2>
        <p class="mb-8" style="color: var(--text-secondary); font-size: 0.95rem;">Silakan login untuk mencatat kehadiran Anda.</p>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group text-left" style="text-align: left;">
                <label class="form-label" for="nim">NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM anda" required value="{{ old('nim') }}" autofocus>
            </div>
            
            <div class="form-group text-left" style="text-align: left;">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password Anda" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 1rem; font-size: 1.05rem;">
                Login ke Sistem <span class="material-symbols-rounded" style="font-size: 1.25rem;">login</span>
            </button>
        </form>
    </div>
</div>
@endsection
