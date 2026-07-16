@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}
.dashboard-card {
    padding: 1.25rem 0.75rem !important;
}
.dashboard-card h3 {
    font-size: 0.9rem;
    margin: 0;
}
.dashboard-card .number {
    font-size: 2.2rem !important;
    margin: 0.25rem 0 !important;
}
.dashboard-card .desc {
    font-size: 0.75rem;
    margin-bottom: 1rem !important;
}
.dashboard-card .btn {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .dashboard-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    .dashboard-card {
        padding: 2.5rem !important;
    }
    .dashboard-card h3 {
        font-size: 1.17rem;
    }
    .dashboard-card .number {
        font-size: 3.5rem !important;
        margin: 0.5rem 0 !important;
    }
    .dashboard-card .desc {
        font-size: 1rem;
        margin-bottom: 1.5rem !important;
    }
    .dashboard-card .btn {
        padding: 0.75rem 1.75rem;
        font-size: 0.95rem;
        width: auto;
    }
}
</style>

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

    <div class="dashboard-grid mt-8">
        <div class="glass-panel dashboard-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(244, 114, 182, 0.05) 100%); position: relative; overflow: hidden;">
            <span class="material-symbols-rounded" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; color: rgba(99, 102, 241, 0.05); z-index: -1;">groups</span>
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><span class="material-symbols-rounded text-primary">groups</span> Total Mahasiswa</h3>
            <p class="number" style="font-weight: 800; color: var(--primary-color);">{{ $totalMahasiswa }}</p>
            <p class="text-secondary desc">Terdaftar di sistem</p>
            <a href="{{ route('admin.students') }}" class="btn btn-primary" style="border-radius: 99px;">Kelola Data <span class="material-symbols-rounded" style="font-size: 1.2rem;">arrow_forward</span></a>
        </div>
        
        <div class="glass-panel dashboard-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(52, 211, 153, 0.05) 100%); position: relative; overflow: hidden;">
            <span class="material-symbols-rounded" style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; color: rgba(16, 185, 129, 0.05); z-index: -1;">how_to_reg</span>
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><span class="material-symbols-rounded text-success">how_to_reg</span> Kehadiran Hari Ini</h3>
            <p class="number" style="font-weight: 800; color: var(--success);">{{ $todayAttendances }}</p>
            <p class="text-secondary desc">Telah check-in</p>
            <a href="{{ route('admin.attendances') }}" class="btn btn-success" style="border-radius: 99px;">Lihat Rekap <span class="material-symbols-rounded" style="font-size: 1.2rem;">arrow_forward</span></a>
        </div>
    </div>
</div>
@endsection
