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
.filter-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin: 0;
}
.filter-input-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    flex-wrap: nowrap;
}
.filter-input-group label {
    white-space: nowrap;
}
.filter-input-group .form-control {
    flex-grow: 1;
    min-width: 0;
    width: 100%;
    max-width: 100% !important;
}
.filter-button-group {
    display: flex;
    gap: 0.5rem;
    width: 100%;
    flex-wrap: wrap;
}
.filter-button-group a,
.filter-button-group button {
    flex: 1 1 auto;
    justify-content: center;
    text-align: center;
    white-space: nowrap;
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
    .filter-form {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    .filter-input-group {
        width: auto;
    }
    .filter-input-group .form-control {
        width: 250px;
        max-width: 250px !important;
        flex-grow: 0;
    }
    .filter-button-group {
        width: auto;
        flex-wrap: nowrap;
    }
    .filter-button-group a,
    .filter-button-group button {
        flex: none;
    }
}
</style>

<div class="glass-panel">
    <div class="flex justify-between items-center mb-6" style="flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-rounded text-success" style="font-size: 2rem;">fact_check</span> Rekap Kehadiran
        </h2>
        <button type="button" onclick="openAttendanceModal()" class="btn btn-primary" style="border-radius: 99px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.4rem;">
            <span class="material-symbols-rounded">how_to_reg</span> + Input Absensi
        </button>
    </div>

    <!-- Filter Form -->
    <div style="background: rgba(255, 255, 255, 0.4); border-radius: var(--radius-md); border: 1px solid rgba(255, 255, 255, 0.4); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.attendances') }}" class="filter-form">
            <div class="filter-input-group">
                <span class="material-symbols-rounded text-primary" style="font-size: 1.5rem;">calendar_today</span>
                <label for="date-filter" style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary); white-space: nowrap; margin: 0;">Pilih Tanggal:</label>
                <input type="date" name="date" id="date-filter" class="form-control" style="padding: 0.5rem 1rem; margin: 0;" value="{{ $selectedDate ?? '' }}" onchange="this.form.submit()">
            </div>
            <div class="filter-button-group">
                @if($selectedDate)
                    <a href="{{ route('admin.attendances') }}" class="btn btn-outline" style="padding: 0.5rem 1.2rem; border-radius: 99px; font-size: 0.9rem; background: rgba(255,255,255,0.5);">
                        <span class="material-symbols-rounded">close</span> Reset Filter
                    </a>
                @endif
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.2rem; border-radius: 99px; font-size: 0.9rem;">
                    <span class="material-symbols-rounded">filter_alt</span> Filter
                </button>
                <a href="{{ route('admin.attendances.export') }}" class="btn btn-success" style="padding: 0.5rem 1.2rem; border-radius: 99px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                    <span class="material-symbols-rounded" style="font-size: 1.2rem;">download</span> Export Excel
                </a>
            </div>
        </form>
    </div>

    @if($selectedDate)
        <div class="grid-container">
            <!-- Left Side: Belum Absen -->
            <div class="col-left" style="background: rgba(255, 255, 255, 0.25); border-radius: var(--radius-md); border: 1px solid rgba(255, 255, 255, 0.3); height: fit-content;">
                <h3 style="margin-top: 0; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-family: 'Poppins', sans-serif;">
                    <span class="material-symbols-rounded text-danger">person_off</span> Belum Absen
                    <span class="badge badge-danger" style="margin-left: auto;">{{ $notAttendedStudents->count() }} orang</span>
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 480px; overflow-y: auto; padding-right: 0.25rem;">
                    @forelse($notAttendedStudents as $mhs)
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.75rem; background: rgba(255, 255, 255, 0.5); border-radius: var(--radius-sm); border: 1px solid rgba(255, 255, 255, 0.3);">
                            <div style="display: flex; align-items: center; gap: 0.75rem; overflow: hidden;">
                                <div class="img-avatar" style="width: 36px; height: 36px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; color: var(--primary-color); font-size: 0.9rem; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); flex-shrink: 0;">
                                    {{ strtoupper(substr($mhs->name, 0, 2)) }}
                                </div>
                                <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $mhs->name }}">{{ $mhs->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $mhs->nim }} • {{ $mhs->prodi }}</div>
                                </div>
                            </div>
                            <button type="button" onclick="openAttendanceModal({{ $mhs->id }}, '{{ $selectedDate }}')" class="btn btn-primary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; border-radius: 6px; flex-shrink: 0;" title="Absenkan Mahasiswa Ini">
                                <span class="material-symbols-rounded" style="font-size: 1rem;">check_circle</span> Absenkan
                            </button>
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
                                <th class="text-center">Pagi</th>
                                <th class="text-center">Malam</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                                <tr>
                                    <td style="font-weight: 500;">{{ $att->user->name }}</td>
                                    <td class="text-center text-success" style="font-weight: 600;">
                                        {{ $att->check_in_time ?? '-' }}
                                        @if($att->check_in_time && $att->status === 'terlambat')
                                            <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                                        @endif
                                    </td>
                                    <td class="text-center text-secondary" style="font-weight: 600;">
                                        {{ $att->check_out_time ?? '-' }}
                                        @if($att->check_out_time && \Carbon\Carbon::parse($att->check_out_time)->format('H:i') > $settings['night_end_time'])
                                            <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($att->status === 'hadir')
                                            <span style="background: rgba(16, 185, 129, 0.15); color: var(--success); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                                Hadir
                                            </span>
                                        @elseif($att->status === 'terlambat')
                                            <span style="background: rgba(245, 158, 11, 0.15); color: var(--warning); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                                Terlambat
                                            </span>
                                        @elseif($att->status === 'sakit' || $att->status === 'izin')
                                            <span style="background: rgba(99, 102, 241, 0.15); color: var(--primary-color); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                                {{ ucfirst($att->status) }}
                                            </span>
                                        @else
                                            <span style="background: rgba(100, 116, 139, 0.15); color: var(--text-secondary); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                                {{ ucfirst($att->status) }}
                                            </span>
                                        @endif
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
                                    <td colspan="5" class="text-center">Belum ada data kehadiran pada tanggal ini.</td>
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
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                            <td style="font-weight: 500;">{{ $att->user->name }}</td>
                            <td class="text-center text-success" style="font-weight: 600;">
                                {{ $att->check_in_time ?? '-' }}
                                @if($att->check_in_time && $att->status === 'terlambat')
                                    <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                                @endif
                            </td>
                            <td class="text-center text-secondary" style="font-weight: 600;">
                                {{ $att->check_out_time ?? '-' }}
                                @if($att->check_out_time && \Carbon\Carbon::parse($att->check_out_time)->format('H:i') > $settings['night_end_time'])
                                    <br><small style="color: var(--warning); font-weight: 700;">(Terlambat)</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($att->status === 'hadir')
                                    <span style="background: rgba(16, 185, 129, 0.15); color: var(--success); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                        Hadir
                                    </span>
                                @elseif($att->status === 'terlambat')
                                    <span style="background: rgba(245, 158, 11, 0.15); color: var(--warning); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                        Terlambat
                                    </span>
                                @elseif($att->status === 'sakit' || $att->status === 'izin')
                                    <span style="background: rgba(99, 102, 241, 0.15); color: var(--primary-color); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                @else
                                    <span style="background: rgba(100, 116, 139, 0.15); color: var(--text-secondary); padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700; display: inline-block;">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                @endif
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
    @endif
</div>

<!-- Modal Absensi Manual Admin -->
<div id="attendanceModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.8); max-width: 520px; width: 100%; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); position: relative; animation: slideInDown 0.3s ease;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <span class="material-symbols-rounded text-primary" style="font-size: 1.75rem;">how_to_reg</span> Absenkan Mahasiswa
            </h3>
            <button type="button" onclick="closeAttendanceModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center;">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>

        <form action="{{ route('admin.attendances.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="modal_user_id">Mahasiswa</label>
                <select name="user_id" id="modal_user_id" class="form-control" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($allStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->nim }} - {{ $student->prodi }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="modal_date">Tanggal</label>
                    <input type="date" name="date" id="modal_date" class="form-control" value="{{ $selectedDate ?? date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="modal_status">Status Kehadiran</label>
                    <select name="status" id="modal_status" class="form-control" required onchange="handleStatusChange()">
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="modal_session">Sesi Absen</label>
                <select name="session" id="modal_session" class="form-control" required onchange="handleSessionChange()">
                    <option value="both">Keduanya (Pagi & Malam)</option>
                    <option value="check_in">Absen Pagi Saja</option>
                    <option value="check_out">Absen Malam Saja</option>
                </select>
            </div>

            <div id="time_inputs_container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" id="group_check_in_time">
                    <label class="form-label" for="modal_check_in_time">Jam Check-In (Pagi)</label>
                    <input type="time" name="check_in_time" id="modal_check_in_time" class="form-control" value="07:30">
                </div>
                <div class="form-group" id="group_check_out_time">
                    <label class="form-label" for="modal_check_out_time">Jam Check-Out (Malam)</label>
                    <input type="time" name="check_out_time" id="modal_check_out_time" class="form-control" value="20:00">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="modal_notes">Catatan / Keterangan (Opsional)</label>
                <input type="text" name="notes" id="modal_notes" class="form-control" placeholder="Contoh: Diabsenkan Admin / Ada Surat Dokter">
            </div>

            <div class="form-actions" style="margin-top: 1.5rem;">
                <button type="button" onclick="closeAttendanceModal()" class="btn btn-outline" style="border-radius: 99px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 99px;">
                    <span class="material-symbols-rounded">save</span> Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAttendanceModal(userId = null, date = null) {
    const modal = document.getElementById('attendanceModal');
    const userSelect = document.getElementById('modal_user_id');
    const dateInput = document.getElementById('modal_date');
    
    if (userId) {
        userSelect.value = userId;
    } else if (!userSelect.value && userSelect.options.length > 1) {
        userSelect.selectedIndex = 1;
    }

    if (date) {
        dateInput.value = date;
    }

    handleStatusChange();
    modal.style.display = 'flex';
}

function closeAttendanceModal() {
    document.getElementById('attendanceModal').style.display = 'none';
}

function handleStatusChange() {
    const status = document.getElementById('modal_status').value;
    const timeContainer = document.getElementById('time_inputs_container');
    
    if (status === 'sakit' || status === 'izin') {
        timeContainer.style.opacity = '0.5';
    } else {
        timeContainer.style.opacity = '1';
    }
    handleSessionChange();
}

function handleSessionChange() {
    const session = document.getElementById('modal_session').value;
    const groupIn = document.getElementById('group_check_in_time');
    const groupOut = document.getElementById('group_check_out_time');

    if (session === 'check_in') {
        groupIn.style.display = 'block';
        groupOut.style.display = 'none';
    } else if (session === 'check_out') {
        groupIn.style.display = 'none';
        groupOut.style.display = 'block';
    } else {
        groupIn.style.display = 'block';
        groupOut.style.display = 'block';
    }
}

// Close modal when clicking backdrop
window.addEventListener('click', function(e) {
    const modal = document.getElementById('attendanceModal');
    if (e.target === modal) {
        closeAttendanceModal();
    }
});
</script>
@endsection
