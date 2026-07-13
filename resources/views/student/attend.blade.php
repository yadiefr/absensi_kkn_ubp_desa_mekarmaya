@extends('layouts.app')

@section('title', 'Form Absensi KKN')

@section('content')
<div class="glass-panel" style="max-width: 600px; margin: 0 auto; padding: 2.5rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <span class="material-symbols-rounded" style="font-size: 3rem; color: var(--primary-color);">location_on</span>
        <h2 style="color: var(--text-primary); margin: 0.5rem 0;">Absen {{ request('type') == 'check_out' ? 'Malam' : 'Pagi' }}</h2>
        <p class="text-secondary">Sistem akan mencatat lokasi GPS Anda saat ini</p>
    </div>
    
    <form id="attendance-form" action="{{ route('student.attend.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="{{ request('type', 'check_in') }}">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        
        <div class="text-center" style="margin-top: 1.5rem;">
            <p id="location-status" class="text-warning mb-4" style="font-weight:600;"><span class="material-symbols-rounded" style="vertical-align: middle; animation: spin 1s linear infinite;">refresh</span> Sedang mencari lokasi GPS...</p>
            <button type="button" id="submit-btn" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem; border-radius: 99px; width: 100%;" disabled>
                <span class="material-symbols-rounded">how_to_reg</span> Absen Sekarang
            </button>
        </div>
    </form>
</div>

<script>
(function() {
    const submitBtn = document.getElementById('submit-btn');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const form = document.getElementById('attendance-form');
    const statusText = document.getElementById('location-status');

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Jarak dalam meter
    }

    // Get location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;
                latitudeInput.value = userLat;
                longitudeInput.value = userLon;
                submitBtn.disabled = false;
                
                const poskoLat = parseFloat("{{ $settings['posko_latitude'] }}");
                const poskoLon = parseFloat("{{ $settings['posko_longitude'] }}");
                const maxRadius = parseInt("{{ $settings['radius_meters'] }}");
                
                const distance = calculateDistance(userLat, userLon, poskoLat, poskoLon);
                const roundedDistance = Math.round(distance);
                
                if (roundedDistance <= maxRadius) {
                    statusText.innerHTML = `<span class="material-symbols-rounded text-success" style="vertical-align: middle;">check_circle</span> Lokasi GPS Anda berhasil didapatkan.<br><small class="text-success" style="font-weight: 700; margin-top: 0.5rem; display: block;">Jarak Anda: ${roundedDistance} meter dari posko (Dalam Radius Aman: Maks ${maxRadius} meter).</small>`;
                    statusText.className = 'text-success mb-4';
                } else {
                    statusText.innerHTML = `<span class="material-symbols-rounded text-danger" style="vertical-align: middle;">warning</span> Lokasi GPS Anda berhasil didapatkan.<br><small class="text-danger" style="font-weight: 700; margin-top: 0.5rem; display: block;">Jarak Anda: ${roundedDistance} meter dari posko (Di luar radius aman: Maks ${maxRadius} meter!).</small>`;
                    statusText.className = 'text-danger mb-4';
                }
            },
            (error) => {
                let msg = 'Gagal mengambil lokasi GPS. Pastikan izin lokasi diaktifkan.';
                if(error.code === 1) msg = 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser Anda.';
                statusText.innerHTML = '<span class="material-symbols-rounded" style="vertical-align: middle;">error</span> ' + msg;
                statusText.className = 'text-danger mb-4';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        statusText.innerHTML = '<span class="material-symbols-rounded" style="vertical-align: middle;">error</span> Browser Anda tidak mendukung Geolocation.';
        statusText.className = 'text-danger mb-4';
    }

    submitBtn.addEventListener('click', () => {
        submitBtn.innerHTML = '<span class="material-symbols-rounded" style="animation: spin 1s linear infinite; vertical-align: middle;">refresh</span> Memproses...';
        submitBtn.disabled = true;
        form.submit();
    });
})();
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endsection

