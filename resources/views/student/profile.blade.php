@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('content')
<div class="glass-panel" style="max-width: 600px; margin: 0 auto; padding: 2.5rem 1.5rem;">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">person</span> Profil Saya
        </h2>
    </div>

    <div class="text-center" style="margin-bottom: 2rem;">
        @if($user->profile_photo_path)
            <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Foto Profil" class="img-avatar" style="width: 120px; height: 120px; margin-bottom: 1rem;">
        @else
            <div class="img-avatar" style="width: 120px; height: 120px; background: rgba(99, 102, 241, 0.1); margin: 0 auto 1rem auto; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-rounded text-primary" style="font-size: 4rem;">person</span>
            </div>
        @endif
        <h3 style="margin: 0;">{{ $user->name }}</h3>
        <p class="text-secondary" style="margin: 0; font-weight: 500;">NIM: {{ $user->nim }}</p>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <form action="{{ route('student.profile.edit') }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-primary" style="border-radius: 99px; padding: 0.75rem 2rem; width: 100%;">
                <span class="material-symbols-rounded" style="font-size: 1.25rem;">edit</span> Edit Profil
            </button>
        </form>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline" style="border-radius: 99px; padding: 0.75rem 2rem; color: var(--danger); border-color: var(--danger); width: 100%;">
                <span class="material-symbols-rounded" style="font-size: 1.25rem;">logout</span> Logout
            </button>
        </form>
    </div>
</div>
@endsection
