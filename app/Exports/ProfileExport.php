<?php

namespace App\Exports;

use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProfileExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public $status;
    public $tempat;
    public $tanggal;

    public function __construct($status, $tempat, $tanggal) 
    {
        $this->status = $status;
        $this->tempat = $tempat;
        $this->tanggal = $tanggal;
    }

    // Menampilkan halaman export profile
    public function view(): View 
    {
        $setting = Setting::first();
        if (Auth::user()->role == 'Petugas') {
            if ($this->status == 'semua' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.polres_id', $this->tempat)
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } elseif ($this->status <> 'semua' && $this->status <> '' && $this->tanggal == '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.status', $this->status)
                    ->where('profiles.polres_id', $this->tempat)
                    ->get();
            } elseif ($this->status <> 'semua' && $this->status <> '' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.status', $this->status)
                    ->where('profiles.polres_id', $this->tempat)
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } else {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.polres_id', $this->tempat)
                    ->get();
            }
        } elseif (Auth::user()->role == 'Administrator') {
            if ($this->status == 'semua' && $this->tempat <> '' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.polres_id', $this->tempat)
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } elseif ($this->status <> 'semua' && $this->status <> '' && $this->tempat <> '' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.status', $this->status)
                    ->where('profiles.polres_id', $this->tempat)
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } elseif ($this->status == 'semua' && $this->tempat == '' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } elseif ($this->status <> 'semua' && $this->status <> '' && $this->tempat == '' && $this->tanggal <> '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.status', $this->status)
                    ->whereDate('profiles.created_at', $this->tanggal)
                    ->get();
            } elseif ($this->status == 'semua' && $this->tempat <> '' && $this->tanggal == '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.polres_id', $this->tempat)
                    ->get();
            } elseif ($this->status <> 'semua' && $this->status <> '' && $this->tempat <> '' && $this->tanggal == '') {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->where('profiles.status', $this->status)
                    ->where('profiles.polres_id', $this->tempat)
                    ->get();
            } else {
                $profile = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')
                    ->join('users', 'profiles.user_id', 'users.id')
                    ->join('polres', 'profiles.polres_id', 'polres.id')
                    ->where('users.role', 'Member')
                    ->get();
            }
        }

        return view('profile.excel', compact('setting', 'profile'));
    }
}
