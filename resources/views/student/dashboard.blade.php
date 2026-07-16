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
        
        <div class="grid grid-cols-2 gap-4 mt-6" style="max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="glass-panel" style="padding: 1.25rem 0.75rem; background: rgba(255,255,255,0.4); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.25rem; margin-bottom: 0.5rem;">
                        <span class="material-symbols-rounded" style="color: var(--primary-color); font-size: 1.5rem;">login</span>
                        <h4 style="margin: 0; font-size: 1rem;">Absen Pagi</h4>
                    </div>
                    
                    @if($todayAttendance && $todayAttendance->check_in_time)
                        <p class="text-success" style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0;">{{ $todayAttendance->check_in_time }}</p>
                    @else
                        <p style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0; color: #cbd5e1;">--:--</p>
                    @endif
                </div>
                
                <div>
                    @if($todayAttendance && $todayAttendance->check_in_time)
                        @if($todayAttendance->status === 'terlambat')
                            <span class="badge" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; color: #b45309; background: #fef3c7; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">warning</span> Terlambat</span>
                        @else
                            <span class="badge badge-success" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">check_circle</span> Selesai</span>
                        @endif
                    @else
                        <span class="badge badge-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">cancel</span> Belum Absen</span>
                    @endif
                </div>
            </div>
            
            <div class="glass-panel" style="padding: 1.25rem 0.75rem; background: rgba(255,255,255,0.4); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.25rem; margin-bottom: 0.5rem;">
                        <span class="material-symbols-rounded" style="color: var(--secondary-color); font-size: 1.5rem;">logout</span>
                        <h4 style="margin: 0; font-size: 1rem;">Absen Malam</h4>
                    </div>
     
                    @if($todayAttendance && $todayAttendance->check_out_time)
                        <p class="text-success" style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0;">{{ $todayAttendance->check_out_time }}</p>
                    @else
                        <p style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0; color: #cbd5e1;">--:--</p>
                    @endif
                </div>
                
                <div>
                    @if($todayAttendance && $todayAttendance->check_out_time)
                        @php
                            $checkoutTime = \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i');
                            $isCheckoutLate = $checkoutTime > $settings['night_end_time'];
                        @endphp
                        @if($isCheckoutLate)
                            <span class="badge" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; color: #b45309; background: #fef3c7; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">warning</span> Terlambat</span>
                        @else
                            <span class="badge badge-success" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">check_circle</span> Selesai</span>
                        @endif
                    @else
                        <span class="badge badge-warning" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; color: #b45309; background: #fef3c7; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: fit-content; margin: 0 auto;"><span class="material-symbols-rounded" style="font-size: 0.85rem;">pending</span> Belum Absen</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mt-8">
            @php
                $showCheckOut = false;
                if ($todayAttendance && !$todayAttendance->check_out_time) {
                    $showCheckOut = true;
                }
            @endphp

            @if($todayAttendance && $todayAttendance->check_out_time)
                <button class="btn" disabled style="background: #e2e8f0; color: #94a3b8; font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px; cursor: not-allowed;">
                    <span class="material-symbols-rounded">task_alt</span> Kehadiran Selesai Hari Ini
                </button>
            @elseif($showCheckOut)
                <a href="{{ route('student.attend.form') }}?type=check_out" class="btn btn-warning" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px; background: linear-gradient(135deg, var(--warning), #d97706); color: white;">
                    <span class="material-symbols-rounded">location_on</span> Lakukan Absensi Malam
                </a>
            @else
                <a href="{{ route('student.attend.form') }}?type=check_in" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 99px;">
                    <span class="material-symbols-rounded">location_on</span> Mulai Absen Pagi
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
