@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="glass-panel">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
        <div style="background: rgba(99, 102, 241, 0.1); width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <span class="material-symbols-rounded" style="font-size: 2rem; color: var(--primary-color);">admin_panel_settings</span>
        </div>
        <div>
            <h2 style="margin: 0;">Admin Dashboard</h2>
            <p class="text-secondary" style="margin: 0;">Ringkasan Sistem Absensi KKN</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mt-8">
        <div class="glass-panel" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(244, 114, 182, 0.05) 100%); position: relative; overflow: hidden;">
            <span class="material-symbols-rounded" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; color: rgba(99, 102, 241, 0.05); z-index: -1;">groups</span>
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><span class="material-symbols-rounded text-primary">groups</span> Total Mahasiswa</h3>
            <p style="font-size: 3.5rem; font-weight: 800; color: var(--primary-color); margin: 0.5rem 0;">{{ $totalMahasiswa }}</p>
            <p class="text-secondary" style="margin-bottom: 1.5rem;">Terdaftar di sistem</p>
            <a href="{{ route('admin.students') }}" class="btn btn-primary" style="font-size: 0.95rem; border-radius: 99px;">Kelola Data <span class="material-symbols-rounded" style="font-size: 1.2rem;">arrow_forward</span></a>
        </div>
        
        <div class="glass-panel" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(52, 211, 153, 0.05) 100%); position: relative; overflow: hidden;">
            <span class="material-symbols-rounded" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; color: rgba(16, 185, 129, 0.05); z-index: -1;">how_to_reg</span>
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><span class="material-symbols-rounded text-success">how_to_reg</span> Kehadiran Hari Ini</h3>
            <p style="font-size: 3.5rem; font-weight: 800; color: var(--success); margin: 0.5rem 0;">{{ $todayAttendances }}</p>
            <p class="text-secondary" style="margin-bottom: 1.5rem;">Telah check-in</p>
            <a href="{{ route('admin.attendances') }}" class="btn btn-success" style="font-size: 0.95rem; border-radius: 99px;">Lihat Rekap <span class="material-symbols-rounded" style="font-size: 1.2rem;">arrow_forward</span></a>
        </div>
    </div>
</div>
@endsection
