<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today('Asia/Jakarta')->toDateString())
            ->first();

        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        $now = Carbon::now('Asia/Jakarta');
        $currentTime = $now->format('H:i');

        return view('student.dashboard', compact('user', 'todayAttendance', 'settings', 'currentTime'));
    }

    public function attendForm()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today('Asia/Jakarta')->toDateString())
            ->first();

        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        $now = Carbon::now('Asia/Jakarta');
        $currentTime = $now->format('H:i');

        if ($currentTime >= $settings['night_start_time']) {
            if (request('type') !== 'check_out') {
                return redirect()->route('student.attend.form', ['type' => 'check_out']);
            }
        }

        return view('student.attend', compact('todayAttendance', 'settings'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'type' => 'required|in:check_in,check_out',
        ]);

        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        $now = Carbon::now('Asia/Jakarta');
        $currentTime = $now->format('H:i');

        // 1. Validasi Waktu
        if ($request->type === 'check_in') {
            if ($currentTime < $settings['morning_start_time']) {
                return back()->with('error', "Waktu Absen Pagi belum dimulai. Hanya diperbolehkan setelah pukul {$settings['morning_start_time']} WIB. (Waktu server sekarang: {$currentTime})");
            }
            if ($currentTime >= $settings['night_start_time']) {
                return back()->with('error', "Waktu Absen Pagi sudah berakhir karena telah memasuki waktu Absen Malam. Silakan lakukan Absen Malam.");
            }
        } else {
            if ($currentTime < $settings['night_start_time']) {
                return back()->with('error', "Waktu Absen Malam belum dimulai. Hanya diperbolehkan setelah pukul {$settings['night_start_time']} WIB. (Waktu server sekarang: {$currentTime})");
            }
        }

        // 2. Validasi Jarak (Geofencing)
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $settings['posko_latitude'],
            $settings['posko_longitude']
        );

        if ($distance > $settings['radius_meters']) {
            return back()->with('error', "Lokasi Anda berada di luar radius posko. Jarak Anda: " . round($distance) . " meter (Batas: {$settings['radius_meters']} meter).");
        }

        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($request->type === 'check_in') {
            if ($attendance) {
                return back()->with('error', 'Anda sudah absen hari ini.');
            }
            
            $status = 'hadir';
            if ($currentTime > $settings['morning_end_time']) {
                $status = 'terlambat';
            }

            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'check_in_location' => $request->latitude . ',' . $request->longitude,
                'status' => $status,
            ]);
            
            $successMsg = $status === 'terlambat' ? 'Absen berhasil! Anda terlambat.' : 'Absen berhasil!';
            return redirect()->route('student.dashboard')->with('success', $successMsg);
        } else {
            if ($attendance && $attendance->check_out_time) {
                return back()->with('error', 'Anda sudah absen hari ini.');
            }
            
            if (!$attendance) {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'check_in_time' => null,
                    'check_in_location' => null,
                    'check_out_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'check_out_location' => $request->latitude . ',' . $request->longitude,
                    'status' => 'terlambat',
                ]);
            } else {
                $attendance->update([
                    'check_out_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'check_out_location' => $request->latitude . ',' . $request->longitude,
                ]);
            }
            
            return redirect()->route('student.dashboard')->with('success', 'Absen berhasil!');
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // Jarak dalam meter
    }

    public function history()
    {
        $attendances = Attendance::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('student.history', compact('attendances', 'settings'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('student.profile_edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'name' => 'required|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nim' => $request->nim,
            'name' => $request->name,
            'prodi' => $request->prodi,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('student.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
