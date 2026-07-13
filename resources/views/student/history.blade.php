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
                            {{ $absen->check_in_time ?? '-' }}<br>
                        </td>
                        <td class="text-center">
                            {{ $absen->check_out_time ?? '-' }}<br>
                        </td>
                        <td class="text-center">
                            <span style="background: var(--success); color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">
                                {{ ucfirst($absen->status) }}
                            </span>
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
