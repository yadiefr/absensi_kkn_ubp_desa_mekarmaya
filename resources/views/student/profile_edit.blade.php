@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="glass-panel" style="max-width: 600px; margin: 0 auto; padding: 2.5rem 1.5rem;">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">manage_accounts</span> Edit Profil
        </h2>
        <a href="{{ route('student.profile') }}" class="btn btn-outline" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
            <span class="material-symbols-rounded">arrow_back</span> Batal
        </a>
    </div>

    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group text-center">
            @if($user->profile_photo_path)
                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Foto Profil" class="img-avatar" style="width: 120px; height: 120px; margin-bottom: 1rem;">
            @else
                <div class="img-avatar" style="width: 120px; height: 120px; background: rgba(99, 102, 241, 0.1); margin: 0 auto 1rem auto; display: flex; align-items: center; justify-content: center;">
                    <span class="material-symbols-rounded text-primary" style="font-size: 4rem;">person</span>
                </div>
            @endif
            
            <label class="form-label" for="profile_photo">Unggah Foto Profil Baru</label>
            <input type="file" id="profile_photo" name="profile_photo" class="form-control" accept="image/jpeg,image/png,image/jpg" style="background: rgba(255,255,255,0.5);">
            <small class="text-secondary mt-2" style="display: block;">Format: JPG/PNG, Maks: 2MB.</small>
        </div>

        <div class="form-group">
            <label class="form-label" for="nim">NIM</label>
            <input type="text" id="nim" name="nim" class="form-control" value="{{ old('nim', $user->nim) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="prodi">Program Studi</label>
            <input type="text" id="prodi" name="prodi" class="form-control" value="{{ old('prodi', $user->prodi) }}">
        </div>

        <div style="background: rgba(0,0,0,0.02); padding: 1rem; border-radius: 8px; margin-top: 1.5rem; margin-bottom: 1.5rem; border: 1px dashed rgba(0,0,0,0.1);">
            <p style="margin: 0 0 1rem 0; font-weight: 600; font-size: 0.9rem;">Ubah Kata Sandi (Opsional)</p>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label" for="password">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; border-radius: 99px;">
            <span class="material-symbols-rounded">save</span> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
