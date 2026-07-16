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
