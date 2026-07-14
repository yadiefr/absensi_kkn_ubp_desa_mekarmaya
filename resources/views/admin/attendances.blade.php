@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')
<style>
.grid-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.col-left {
    width: 100%;
}
.col-right {
    width: 100%;
}
@media (min-width: 768px) {
    .grid-container {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 2rem;
    }
    .col-left {
        grid-column: span 1 / span 1;
    }
    .col-right {
        grid-column: span 2 / span 2;
    }
}
</style>

<div class="glass-panel">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-success" style="font-size: 2rem;">fact_check</span> Rekap Kehadiran
        </h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
            <span class="material-symbols-rounded">arrow_back</span> Kembali
        </a>
    </div>

    <!-- Filter Form -->
    <div style="background: rgba(255, 255, 255, 0.4); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid rgba(255, 255, 255, 0.4); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.attendances') }}" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin: 0;">
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-grow: 1;">
                <span class="material-symbols-rounded text-primary" style="font-size: 1.5rem;">calendar_today</span>
                <label for="date-filter" style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary); white-space: nowrap; margin: 0;">Pilih Tanggal:</label>
                <input type="date" name="date" id="date-filter" class="form-control" style="max-width: 250px; padding: 0.5rem 1rem; margin: 0;" value="{{ $selectedDate ?? '' }}" onchange="this.form.submit()">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                @if($selectedDate)
                    <a href="{{ route('admin.attendances') }}" class="btn btn-outline" style="padding: 0.5rem 1.2rem; border-radius: 99px; font-size: 0.9rem; background: rgba(255,255,255,0.5);">
                        <span class="material-symbols-rounded">close</span> Semua Tanggal
                    </a>
                @endif
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.2rem; border-radius: 99px; font-size: 0.9rem;">
                    <span class="material-symbols-rounded">filter_alt</span> Filter
                </button>
            </div>
        </form>
    </div>

    @if($selectedDate)
        <div class="grid-container">
            <!-- Left Side: Belum Absen -->
            <div class="col-left" style="background: rgba(255, 255, 255, 0.25); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid rgba(255, 255, 255, 0.3); height: fit-content;">
                <h3 style="margin-top: 0; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-family: 'Poppins', sans-serif;">
                    <span class="material-symbols-rounded text-danger">person_off</span> Belum Absen
                    <span class="badge badge-danger" style="margin-left: auto;">{{ $notAttendedStudents->count() }} orang</span>
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 480px; overflow-y: auto; padding-right: 0.25rem;">
                    @forelse($notAttendedStudents as $mhs)
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(255, 255, 255, 0.5); border-radius: var(--radius-sm); border: 1px solid rgba(255, 255, 255, 0.3);">
                            <div class="img-avatar" style="width: 36px; height: 36px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; color: var(--primary-color); font-size: 0.9rem; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); flex-shrink: 0;">
                                {{ strtoupper(substr($mhs->name, 0, 2)) }}
                            </div>
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $mhs->name }}">{{ $mhs->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $mhs->nim }} • {{ $mhs->prodi }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-success" style="font-weight: 600; padding: 3rem 1rem;">
                            <span class="material-symbols-rounded" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">check_circle</span>
                            Semua mahasiswa sudah absen!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Riwayat Kehadiran -->
            <div class="col-right">
                <h3 style="margin-top: 0; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-family: 'Poppins', sans-serif;">
                    <span class="material-symbols-rounded text-success">fact_check</span> Kehadiran: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                    <span class="badge badge-success" style="margin-left: auto;">{{ $attendances->count() }} data</span>
                </h3>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Pagi (Check-In)</th>
                                <th class="text-center">Malam (Check-Out)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                                <tr>
                                    <td style="font-weight: 500;">{{ $att->user->name }}</td>
                                    <td class="text-center text-success" style="font-weight: 600;">{{ $att->check_in_time ?? '-' }}</td>
                                    <td class="text-center text-secondary" style="font-weight: 600;">{{ $att->check_out_time ?? '-' }}</td>
                                    <td>
                                        <div style="display: flex; justify-content: center;">
                                            <form action="{{ route('admin.attendances.destroy', $att->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn" style="background: rgba(244, 63, 94, 0.15); color: var(--danger); padding: 0.4rem 0.8rem;">
                                                    <span class="material-symbols-rounded" style="font-size: 1.2rem;">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data kehadiran pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- No date selected: Show all logs in full-width -->
        <h3 style="margin-top: 0; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-family: 'Poppins', sans-serif;">
            <span class="material-symbols-rounded text-primary">history</span> Semua Riwayat Kehadiran
            <span class="badge badge-success" style="margin-left: auto;">{{ $attendances->count() }} data</span>
        </h3>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Pagi (Check-In)</th>
                        <th class="text-center">Malam (Check-Out)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                            <td style="font-weight: 500;">{{ $att->user->name }}</td>
                            <td class="text-center text-success" style="font-weight: 600;">{{ $att->check_in_time ?? '-' }}</td>
                            <td class="text-center text-secondary" style="font-weight: 600;">{{ $att->check_out_time ?? '-' }}</td>
                            <td>
                                <div style="display: flex; justify-content: center;">
                                    <form action="{{ route('admin.attendances.destroy', $att->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="background: rgba(244, 63, 94, 0.15); color: var(--danger); padding: 0.4rem 0.8rem;">
                                            <span class="material-symbols-rounded" style="font-size: 1.2rem;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
