@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="glass-panel text-center">

    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            @if($user->profile_photo_path)
                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Foto Profil" class="img-avatar" style="width: 70px; height: 70px;">
            @else
                <div class="img-avatar" style="width: 70px; height: 70px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center;">
                    <span class="material-symbols-rounded text-primary" style="font-size: 2.5rem;">person</span>
                </div>
            @endif
            <div>
                <h2 style="margin: 0;">Halo, {{ explode(' ', $user->name)[0] }}!</h2>
                <p class="text-secondary" style="font-weight: 600; margin: 0;">NIM: {{ $user->nim }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div style="background: rgba(255, 255, 255, 0.5); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: left; font-size: 0.9rem;">
            <p style="margin: 0 0 0.5rem 0; font-weight: 700; color: var(--text-primary);">Info Aturan Absensi:</p>
            <ul style="margin: 0; padding-left: 1.2rem; color: var(--text-secondary);">
                <li><strong>Absen Pagi:</strong> {{ $settings['morning_start_time'] }} - {{ $settings['morning_end_time'] }}</li>
                <li><strong>Absen Malam:</strong> {{ $settings['night_start_time'] }} - {{ $settings['night_end_time'] }}</li>
                <li><strong>Batas Jarak:</strong> Maksimal {{ $settings['radius_meters'] }} meter dari posko.</li>
            </ul>
        </div>

        <h3 style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary">calendar_today</span> 
            Kehadiran Hari Ini
        </h3>
        <p style="font-weight: 700; font-size: 1.1rem;">({{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }})</p>
        
        <div class="grid md:grid-cols-2 gap-6 mt-6" style="max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.4);">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="material-symbols-rounded" style="color: var(--primary-color);">login</span>
                    <h4 style="margin: 0; font-size: 1.1rem;">Absen Pagi</h4>
                </div>
                
                @if($todayAttendance && $todayAttendance->check_in_time)
                    <p class="text-success" style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0;">{{ $todayAttendance->check_in_time }}</p>
                    <span class="badge badge-success"><span class="material-symbols-rounded" style="font-size: 1rem;">check_circle</span> Selesai</span>
                @else
                    <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0; color: #cbd5e1;">--:--</p>
                    <span class="badge badge-danger"><span class="material-symbols-rounded" style="font-size: 1rem;">cancel</span> Belum Absen</span>
                @endif
            </div>
            
            <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.4);">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span class="material-symbols-rounded" style="color: var(--secondary-color);">logout</span>
                    <h4 style="margin: 0; font-size: 1.1rem;">Absen Malam</h4>
                </div>

                @if($todayAttendance && $todayAttendance->check_out_time)
                    <p class="text-success" style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0;">{{ $todayAttendance->check_out_time }}</p>
                    <span class="badge badge-success"><span class="material-symbols-rounded" style="font-size: 1rem;">check_circle</span> Selesai</span>
                @else
                    <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0; color: #cbd5e1;">--:--</p>
                    <span class="badge badge-warning"><span class="material-symbols-rounded" style="font-size: 1rem;">pending</span> Belum Absen</span>
                @endif
            </div>
        </div>
        
        <div class="mt-8">
            @if(!$todayAttendance)
                <a href="{{ route('student.attend.form') }}?type=check_in" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px;">
                    <span class="material-symbols-rounded">location_on</span> Mulai Absen Pagi
                </a>
            @elseif($todayAttendance && !$todayAttendance->check_out_time)
                <a href="{{ route('student.attend.form') }}?type=check_out" class="btn btn-warning" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px; background: linear-gradient(135deg, var(--warning), #d97706); color: white;">
                    <span class="material-symbols-rounded">location_on</span> Lakukan Absensi Malam
                </a>
            @else
                <button class="btn" disabled style="background: #e2e8f0; color: #94a3b8; font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px; cursor: not-allowed;">
                    <span class="material-symbols-rounded">task_alt</span> Kehadiran Selesai Hari Ini
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
