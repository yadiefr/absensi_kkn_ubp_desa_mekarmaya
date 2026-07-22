@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="glass-panel">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">groups</span> Data Mahasiswa KKN
        </h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
                <span class="material-symbols-rounded">person_add</span> Tambah Mahasiswa
            </a>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama / NIM</th>
                    <th class="text-center">Program Studi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $mhs)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                @if($mhs->profile_photo_path)
                                    <img src="{{ Storage::url($mhs->profile_photo_path) }}" alt="Avatar" class="img-avatar" style="width: 40px; height: 40px; flex-shrink: 0;">
                                @else
                                    <div class="img-avatar" style="width: 40px; height: 40px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <span class="material-symbols-rounded text-primary" style="font-size: 1.5rem;">person</span>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $mhs->name }}</div>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.1rem;">{{ $mhs->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $mhs->prodi }}</td>
                        <td class="text-center">
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <a href="{{ route('admin.attendances') }}" class="btn" style="background: rgba(99, 102, 241, 0.15); color: var(--primary-color); padding: 0.4rem 0.8rem;" title="Absenkan Mahasiswa">
                                    <span class="material-symbols-rounded" style="font-size: 1.2rem;">how_to_reg</span>
                                </a>
                                <a href="{{ route('admin.students.edit', $mhs->id) }}" class="btn" style="background: rgba(245, 158, 11, 0.15); color: var(--warning); padding: 0.4rem 0.8rem;" title="Edit Data Mahasiswa">
                                    <span class="material-symbols-rounded" style="font-size: 1.2rem;">edit</span>
                                </a>
                                <form action="{{ route('admin.students.destroy', $mhs->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: rgba(244, 63, 94, 0.15); color: var(--danger); padding: 0.4rem 0.8rem;" title="Hapus Mahasiswa">
                                        <span class="material-symbols-rounded" style="font-size: 1.2rem;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data mahasiswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
