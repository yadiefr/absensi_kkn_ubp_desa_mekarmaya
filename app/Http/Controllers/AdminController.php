<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $todayAttendances = Attendance::whereDate('date', Carbon::today())->count();
        
        return view('admin.dashboard', compact('totalMahasiswa', 'todayAttendances'));
    }

    public function students()
    {
        $students = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();
        return view('admin.students', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.student_form');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:users,nim',
            'name' => 'required',
            'prodi' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'prodi' => $request->prodi,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('admin.students')->with('success', 'Data Mahasiswa berhasil ditambahkan.');
    }

    public function editStudent($id)
    {
        $student = User::findOrFail($id);
        return view('admin.student_form', compact('student'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'nim' => 'required|unique:users,nim,' . $student->id,
            'name' => 'required',
            'prodi' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $student->nim = $request->nim;
        $student->name = $request->name;
        $student->prodi = $request->prodi;
        
        if ($request->filled('password')) {
            $student->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('admin.students')->with('success', 'Data Mahasiswa berhasil diubah.');
    }

    public function destroyStudent($id)
    {
        $student = User::findOrFail($id);
        
        // Hapus juga riwayat absensinya
        $student->attendances()->delete();
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    public function attendances(Request $request)
    {
        $selectedDate = $request->query('date');
        
        // If not specified, default to today
        if ($request->has('date') && empty($selectedDate)) {
            // Admin explicitly cleared the date filter
            $selectedDate = null;
        } elseif (!$request->has('date')) {
            $selectedDate = Carbon::today('Asia/Jakarta')->toDateString();
        }

        $attendancesQuery = Attendance::with('user');
        if ($selectedDate) {
            $attendancesQuery->whereDate('date', $selectedDate);
        }
        $attendances = $attendancesQuery->orderBy('date', 'desc')->orderBy('check_in_time', 'desc')->get();

        $notAttendedStudents = collect();
        if ($selectedDate) {
            $attendedUserIds = Attendance::whereDate('date', $selectedDate)
                ->pluck('user_id')
                ->toArray();

            $notAttendedStudents = User::where('role', 'mahasiswa')
                ->whereNotIn('id', $attendedUserIds)
                ->orderBy('name', 'asc')
                ->get();
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.attendances', compact('attendances', 'notAttendedStudents', 'selectedDate', 'settings'));
    }

    public function destroyAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.attendances')->with('success', 'Data absensi berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $selectedDate = $request->query('date');
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();

        // 1. Ambil data absen total per mahasiswa (untuk sheet "Absen Total")
        $students = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();

        // Hitung total hari aktif absensi KKN secara keseluruhan (atau disaring sesuai bulan dari tanggal terpilih jika difilter)
        $totalActiveDaysQuery = Attendance::distinct();
        if ($selectedDate) {
            $dateObj = Carbon::parse($selectedDate);
            $totalActiveDaysQuery->whereYear('date', $dateObj->year)->whereMonth('date', $dateObj->month);
        }
        $totalActiveDays = $totalActiveDaysQuery->pluck('date')->count();

        $totalSheetData = [
            [
                'No',
                'NIM',
                'Nama Mahasiswa',
                'Program Studi',
                'Total Hari Aktif KKN',
                'Total Hadir Pagi',
                'Total Hadir Malam',
                'Total Terlambat',
                'Persentase Kehadiran'
            ]
        ];

        $noTotal = 1;
        foreach ($students as $student) {
            $studentQuery = Attendance::where('user_id', $student->id);
            if ($selectedDate) {
                $dateObj = Carbon::parse($selectedDate);
                $studentQuery->whereYear('date', $dateObj->year)->whereMonth('date', $dateObj->month);
            }
            $studentAttendances = $studentQuery->get();

            $hadirPagi = 0;
            $hadirMalam = 0;
            $terlambat = 0;

            foreach ($studentAttendances as $att) {
                // Check-in (Pagi)
                if ($att->check_in_time) {
                    $isLate = Carbon::parse($att->check_in_time)->format('H:i') > $settings['morning_end_time'];
                    if ($isLate) {
                        $terlambat++;
                    } else {
                        $hadirPagi++;
                    }
                }
                
                // Check-out (Malam)
                if ($att->check_out_time) {
                    $isLate = Carbon::parse($att->check_out_time)->format('H:i') > $settings['night_end_time'];
                    if ($isLate) {
                        $terlambat++;
                    } else {
                        $hadirMalam++;
                    }
                }
            }

            // Persentase Kehadiran = ((Hadir Pagi + Hadir Malam + Terlambat) / (Total Hari Aktif KKN * 2)) * 100
            $percentage = $totalActiveDays > 0
                ? round((($hadirPagi + $hadirMalam + $terlambat) / ($totalActiveDays * 2)) * 100, 1) . '%'
                : '0%';

            $totalSheetData[] = [
                $noTotal++,
                $student->nim ?? '-',
                $student->name ?? '-',
                $student->prodi ?? '-',
                $totalActiveDays,
                $hadirPagi,
                $hadirMalam,
                $terlambat,
                $percentage
            ];
        }

        // 2. Ambil data absen harian dan kelompokkan per hari
        $attendancesQuery = Attendance::with('user');
        if ($selectedDate) {
            $attendancesQuery->whereDate('date', $selectedDate);
        }
        
        // Urutkan tanggal naik (ascending) agar urutan sheet teratur dari awal ke akhir
        $attendances = $attendancesQuery->orderBy('date', 'asc')
            ->orderBy('check_in_time', 'asc')
            ->get();

        // Kelompokkan data absensi berdasarkan tanggal
        $groupedAttendances = $attendances->groupBy('date');

        // Buat instance SimpleXLSXGen baru
        $xlsx = new \Shuchkin\SimpleXLSXGen();

        // Tambahkan sheet untuk setiap hari
        if ($groupedAttendances->isEmpty()) {
            $xlsx->addSheet([
                ['Tidak ada data absensi untuk periode ini.']
            ], 'Absen Harian');
        } else {
            foreach ($groupedAttendances as $dateStr => $dayRecords) {
                // Format tanggal untuk nama sheet (misal: "01 Jun 2026")
                $sheetName = Carbon::parse($dateStr)->translatedFormat('d M Y');
                
                $dailySheetData = [
                    [
                        'No',
                        'Tanggal',
                        'NIM',
                        'Nama Mahasiswa',
                        'Program Studi',
                        'Absen Pagi',
                        'Absen Malam'
                    ]
                ];

                $noDaily = 1;
                foreach ($dayRecords as $att) {
                    if ($att->status === 'sakit' || $att->status === 'izin') {
                        $label = ucfirst($att->status);
                        // Tampilkan label Sakit/Izin dengan warna ungu, tidak merah karena absensinya sah
                        $checkInDisplay = '<style color="#4f46e5">' . $label . '</style>';
                        $checkOutDisplay = '<style color="#4f46e5">' . $label . '</style>';
                    } else {
                        // Absen Pagi (Check-in)
                        $checkInDisplay = '-';
                        if ($att->check_in_time) {
                            $isCheckInLate = Carbon::parse($att->check_in_time)->format('H:i') > $settings['morning_end_time'];
                            if ($isCheckInLate) {
                                $checkInDisplay = '<style color="#FF0000">' . $att->check_in_time . '</style>';
                            } else {
                                $checkInDisplay = $att->check_in_time;
                            }
                        } else {
                            // Tidak absen pagi (Alpha/Terlambat malam saja)
                            $checkInDisplay = '<style color="#FF0000">-</style>';
                        }

                        // Absen Malam (Check-out)
                        $checkOutDisplay = '-';
                        if ($att->check_out_time) {
                            $isCheckOutLate = Carbon::parse($att->check_out_time)->format('H:i') > $settings['night_end_time'];
                            if ($isCheckOutLate) {
                                $checkOutDisplay = '<style color="#FF0000">' . $att->check_out_time . '</style>';
                            } else {
                                $checkOutDisplay = $att->check_out_time;
                            }
                        } else {
                            // Tidak absen malam
                            $checkOutDisplay = '<style color="#FF0000">-</style>';
                        }
                    }

                    $dailySheetData[] = [
                        $noDaily++,
                        Carbon::parse($att->date)->translatedFormat('d F Y'),
                        $att->user->nim ?? '-',
                        $att->user->name ?? '-',
                        $att->user->prodi ?? '-',
                        $checkInDisplay,
                        $checkOutDisplay
                    ];
                }

                $xlsx->addSheet($dailySheetData, $sheetName);
            }
        }

        // Tambahkan sheet Absen Total di paling akhir
        $xlsx->addSheet($totalSheetData, 'Absen Total');

        $filename = 'rekap_absensi_' . ($selectedDate ?: 'semua') . '_' . date('Ymd_His') . '.xlsx';

        return response((string)$xlsx, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    public function settings()
    {
        $settingsData = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settingsData'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
