@extends('layouts.app')

@section('title', 'Pengaturan Absensi')

@section('content')
<div class="glass-panel" style="max-width: 700px; margin: 0 auto;">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">settings</span> Pengaturan Sistem
        </h2>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <h3 style="border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 0.5rem; margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-success">schedule</span> Pengaturan Waktu
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label" for="morning_start_time">Mulai Absen Pagi (Check-In)</label>
                <input type="time" name="morning_start_time" id="morning_start_time" class="form-control" value="{{ $settingsData['morning_start_time'] ?? '06:00' }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="morning_end_time">Batas Absen Pagi</label>
                <input type="time" name="morning_end_time" id="morning_end_time" class="form-control" value="{{ $settingsData['morning_end_time'] ?? '10:00' }}" required>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label" for="night_start_time">Mulai Absen Malam (Check-Out)</label>
                <input type="time" name="night_start_time" id="night_start_time" class="form-control" value="{{ $settingsData['night_start_time'] ?? '18:00' }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="night_end_time">Batas Absen Malam</label>
                <input type="time" name="night_end_time" id="night_end_time" class="form-control" value="{{ $settingsData['night_end_time'] ?? '22:00' }}" required>
            </div>
        </div>

        <h3 style="border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 0.5rem; margin-top: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded" style="color: var(--danger);">location_on</span> Pengaturan Lokasi Posko
        </h3>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label" for="posko_latitude">Latitude (Garis Lintang)</label>
                <input type="text" name="posko_latitude" id="posko_latitude" class="form-control" value="{{ $settingsData['posko_latitude'] ?? '' }}" placeholder="Contoh: -6.200000" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="posko_longitude">Longitude (Garis Bujur)</label>
                <input type="text" name="posko_longitude" id="posko_longitude" class="form-control" value="{{ $settingsData['posko_longitude'] ?? '' }}" placeholder="Contoh: 106.816666" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="radius_meters">Batas Radius Absen (Meter)</label>
            <input type="number" name="radius_meters" id="radius_meters" class="form-control" value="{{ $settingsData['radius_meters'] ?? '50' }}" placeholder="Contoh: 50" required min="10">
            <small class="text-secondary">Jarak maksimal mahasiswa diperbolehkan absen dari kordinat pusat posko.</small>
        </div>

        <div class="form-actions mt-8">
            <button type="button" class="btn btn-outline" style="border-radius: 99px;" onclick="getLocation()">
                <span class="material-symbols-rounded">my_location</span> Ambil Lokasi Saat Ini
            </button>
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1.1rem; border-radius: 99px;">
                <span class="material-symbols-rounded">save</span> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            document.getElementById('posko_latitude').value = position.coords.latitude;
            document.getElementById('posko_longitude').value = position.coords.longitude;
            alert('Lokasi berhasil diambil dari GPS Anda.');
        }, () => {
            alert('Gagal mengambil lokasi. Pastikan izin lokasi dihidupkan.');
        });
    } else {
        alert('Browser Anda tidak mendukung Geolocation.');
    }
}
</script>
@endsection
