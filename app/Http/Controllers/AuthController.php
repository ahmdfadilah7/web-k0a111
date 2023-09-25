<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Polres;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        $setting = Setting::first();

        return view('auth.login', compact('setting'));
    }

    // Menampilkan halaman register
    public function register()
    {
        $setting = Setting::first();
        $polres = Polres::get();

        return view('auth.register', compact('setting', 'polres'));
    }

    // Menampilkan halaman register petugas
    public function register_petugas()
    {
        $setting = Setting::first();
        $polres = Polres::get();
        $jabatan = Jabatan::get();

        return view('auth.registerpetugas', compact('setting', 'polres', 'jabatan'));
    }

    // Proses login
    public function proses_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $username = $request->get('username');
        $password = Hash::make($request->get('password'));
        if (Auth::attempt($request->only('username', 'password'))) {
            return redirect()->route('dashboard')->with('berhasil', 'Selamat datang '.Auth::user()->name);
        } else {
            return back()->with('gagal', 'Data yang dimasukkan tidak sesuai.');
        }
    }

    // Proses Register Pemohon SIM
    public function proses_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric',
            'nm_lengkap' => 'required',
            'jns_kelamin' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
            'email' => 'required|unique:users,email',
            'no_telp' => 'required',
            'alamat' => 'required',
            'polres' => 'required',
            'foto' => 'mimes:jpg,jpeg,png,svg,webp',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8|confirmed'
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'unique' => ':attribute sudah ada !!',
            'confirmed' => ':attribute tidak sama dengan yang dimasukkan !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $user = new User;
        $user->name = $request->get('nm_lengkap');
        $user->username = $request->get('username');
        $user->role = 'Member';
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $user_id = $user->id;

        if ($request->image <> '') {
            $foto = $request->file('image');
            $namafoto = 'Member-'.str_replace(' ', '-', $request->get('nm_lengkap')).Str::random(5).'.'.$foto->extension();
            $foto->move(public_path('images/'), $namafoto);
            $fotoNama = 'images/'.$namafoto;
        } else {
            $fotoNama = null;
        }

        $qrcode = QrCode::format('png')
            ->size(200)
            ->generate(route('profile.show', $user_id));
        $nameqrcode = 'QrCode-'.str_replace(' ','-',$request->get('nm_lengkap')).'-'.Str::random(5).'.png';
        $tujuanfolder = 'images';
        $output_file = $tujuanfolder.'/'.$nameqrcode;
        Storage::disk('public')->put($output_file, $qrcode);

        Profile::create([
            'user_id' => $user_id,
            'polres_id' => $request->get('polres'),
            'nama_lengkap' => $request->get('nm_lengkap'),
            'nik' => $request->get('nik'),
            'email' => $request->get('email'),
            'no_telp' => $request->get('no_telp'),
            'jns_kelamin' => $request->get('jns_kelamin'),
            'tmpt_lahir' => $request->get('tmpt_lahir'),
            'tgl_lahir' => $request->get('tgl_lahir'),
            'alamat' => $request->get('alamat'),
            'foto' => $fotoNama,
            'qrcode' => $output_file,
            'status' => 0
        ]);

        return redirect()->route('login')->with('berhasil', 'Pendaftaran berhasil.');
    }

    // Proses Register Petugas
    public function proses_register_petugas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric',
            'nm_lengkap' => 'required',
            'jns_kelamin' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
            'email' => 'required|unique:users,email',
            'no_telp' => 'required',
            'alamat' => 'required',
            'polres' => 'required',
            'jabatan' => 'required',
            'foto' => 'mimes:jpg,jpeg,png,svg,webp',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8|confirmed'
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'unique' => ':attribute sudah ada !!',
            'confirmed' => ':attribute tidak sama dengan yang dimasukkan !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $user = new User;
        $user->name = $request->get('nm_lengkap');
        $user->username = $request->get('username');
        $user->role = 'Petugas';
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $user_id = $user->id;

        if ($request->image <> '') {
            $foto = $request->file('image');
            $namafoto = 'Petugas-'.str_replace(' ', '-', $request->get('nm_lengkap')).Str::random(5).'.'.$foto->extension();
            $foto->move(public_path('images/'), $namafoto);
            $fotoNama = 'images/'.$namafoto;
        } else {
            $fotoNama = null;
        }

        Profile::create([
            'user_id' => $user_id,
            'polres_id' => $request->get('polres'),
            'jabatan_id' => $request->get('jabatan'),
            'nama_lengkap' => $request->get('nm_lengkap'),
            'nik' => $request->get('nik'),
            'email' => $request->get('email'),
            'no_telp' => $request->get('no_telp'),
            'jns_kelamin' => $request->get('jns_kelamin'),
            'tmpt_lahir' => $request->get('tmpt_lahir'),
            'tgl_lahir' => $request->get('tgl_lahir'),
            'alamat' => $request->get('alamat'),
            'foto' => $fotoNama,
            'status' => 0
        ]);

        return redirect()->route('login')->with('berhasil', 'Pendaftaran berhasil.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('berhasil', 'Anda berhasil keluar');
    }
}
