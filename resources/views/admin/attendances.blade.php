@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')
<div class="glass-panel">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-success" style="font-size: 2rem;">fact_check</span> Rekap Kehadiran
        </h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
            <span class="material-symbols-rounded">arrow_back</span> Kembali
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Pagi</th>
                    <th class="text-center">Malam</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                        <td class="text-center" style="font-weight: 500; ">{{ $att->user->name }}</td>
                        <td class="text-center text-success" style="font-weight: 600;">{{ $att->check_in_time }}</td>
                        <td class="text-center text-secondary" style="font-weight: 600;">{{ $att->check_out_time ?? '-' }}</td>
                        </td>
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
                        <td colspan="6" class="text-center">Belum ada data kehadiran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
