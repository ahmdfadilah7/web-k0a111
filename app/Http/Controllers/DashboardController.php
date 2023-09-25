<?php

namespace App\Http\Controllers;

use App\Models\Polres;
use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard
    public function index()
    {
        $setting = Setting::first();
        $polres = Polres::get();
        $polres_count = Polres::count();
        $petugas_count = Profile::join('users', 'profiles.user_id', 'users.id')->where('users.role', 'Petugas')->count();
        $pemohon_sim = Profile::join('users', 'profiles.user_id', 'users.id')->where('users.role', 'Member')->count();
        $pemohon_sim_blm_diverif = Profile::join('users', 'profiles.user_id', 'users.id')->where('users.role', 'Member')->where('status', 0)->orWhere('status', null)->count();
        $pemohon_sim_sdh_diverif = Profile::join('users', 'profiles.user_id', 'users.id')->where('users.role', 'Member')->where('status', 1)->count();

        return view('dashboard.index', compact('setting', 'polres', 'polres_count', 'petugas_count', 'pemohon_sim', 'pemohon_sim_blm_diverif', 'pemohon_sim_sdh_diverif'));
    }
}
