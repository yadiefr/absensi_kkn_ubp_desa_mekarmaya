@extends('layouts.app')

@section('title', 'Riwayat Absensi KKN')

@section('content')
<div class="glass-panel">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">history</span> Riwayat Kehadiran
        </h2>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
            <span class="material-symbols-rounded">arrow_back</span> Kembali
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Pagi</th>
                    <th class="text-center">Malam</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $absen)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($absen->date)->translatedFormat('d F Y') }}</td>
                        <td class="text-center">
                            {{ $absen->check_in_time ?? '-' }}
                            @if($absen->check_in_time && $absen->status === 'terlambat')
                                <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $absen->check_out_time ?? '-' }}
                            @if($absen->check_out_time && \Carbon\Carbon::parse($absen->check_out_time)->format('H:i') > $settings['night_end_time'])
                                <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($absen->status === 'hadir')
                                <span style="background: rgba(16, 185, 129, 0.15); color: var(--success); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                    Hadir
                                </span>
                            @elseif($absen->status === 'terlambat')
                                <span style="background: rgba(245, 158, 11, 0.15); color: var(--warning); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                    Terlambat
                                </span>
                            @elseif($absen->status === 'sakit' || $absen->status === 'izin')
                                <span style="background: rgba(99, 102, 241, 0.15); color: var(--primary-color); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            @else
                                <span style="background: rgba(100, 116, 139, 0.15); color: var(--text-secondary); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada riwayat kehadiran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
