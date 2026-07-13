@extends('layouts.app')

@section('title', isset($student) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')

@section('content')
<div class="glass-panel" style="max-width: 600px; margin: 0 auto;">
    <div class="flex justify-between items-center mb-6">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-primary" style="font-size: 2rem;">{{ isset($student) ? 'edit_square' : 'person_add' }}</span>
            {{ isset($student) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}
        </h2>
        <a href="{{ route('admin.students') }}" class="btn btn-outline" style="border-radius: 99px; padding: 0.5rem 1.5rem;">
            <span class="material-symbols-rounded">arrow_back</span> Kembali
        </a>
    </div>

    <form action="{{ isset($student) ? route('admin.students.update', $student->id) : route('admin.students.store') }}" method="POST">
        @csrf
        @if(isset($student))
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="nim">NIM</label>
            <input type="text" name="nim" id="nim" class="form-control" value="{{ old('nim', $student->nim ?? '') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $student->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="prodi">Program Studi</label>
            <input type="text" name="prodi" id="prodi" class="form-control" value="{{ old('prodi', $student->prodi ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password {{ isset($student) ? '(Kosongkan jika tidak ingin diubah)' : '' }}</label>
            <input type="password" name="password" id="password" class="form-control" {{ isset($student) ? '' : 'required' }} minlength="6">
            @if(isset($student))
                <small class="text-secondary">Minimal 6 karakter.</small>
            @endif
        </div>

        <div class="mt-8 text-right">
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1.1rem; border-radius: 99px;">
                <span class="material-symbols-rounded">save</span> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
