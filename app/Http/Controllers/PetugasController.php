<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PetugasController extends Controller
{
    // Menampilkan halaman petugas
    public function index()
    {
        $setting = Setting::first();

        return view('petugas.index', compact('setting'));
    }

    // Proses menampilkan data petugas dengan datatable
    public function listData()
    {
        if (Auth::user()->role == 'Administrator') {
            $data = Profile::join('users', 'profiles.user_id', 'users.id')->join('jabatans', 'profiles.jabatan_id', 'jabatans.id')->where('role', 'Petugas')->get();
        } else {
            $data = Profile::join('users', 'profiles.user_id', 'users.id')->join('jabatans', 'profiles.jabatan_id', 'jabatans.id')->where('role', 'Petugas')->where('user_id', Auth::user()->id);
        }
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('foto', function($row) {
                if ($row->foto <> '') {
                    $img = '<img src="'.url($row->foto).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Tidak ada data</i>';
                }

                return $img;
            })
            ->addColumn('qrcode', function($row) {
                if ($row->qrcode <> '') {
                    $img = '<img src="'.url($row->qrcode).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Tidak ada data</i>';
                }

                return $img;
            })
            ->addColumn('created_at', function($row) {
                $date = date('d M Y', strtotime($row->created_at));

                return $date;
            })
            ->addColumn('jabatan', function($row) {
                $btn = '<span class="badge badge-primary">'.$row->jabatan.'</span>';

                return $btn;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('petugas.show', $row->user_id).'" class="btn btn-info btn-sm mr-2">
                        <i class="fas fa-eye"></i>
                    </a>';
                if ($row->user_id == Auth::user()->id) {
                    $btn .= '<a href="'.route('petugas.edit', $row->user_id).'" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-edit"></i>
                        </a>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'qrcode', 'foto', 'jabatan', 'created_at'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman detail petugas
    public function show($id)
    {
        $setting = Setting::first();
        $profile = Profile::join('jabatans', 'profiles.jabatan_id', 'jabatans.id')->where('user_id', $id)->first();

        return view('petugas.show', compact('setting', 'profile'));
    }

    // Menampilkan halaman edit petugas
    public function edit($id)
    {
        $setting = Setting::first();
        $profile = Profile::where('user_id', $id)->first();
        $user = User::find($id);

        return view('petugas.edit', compact('setting', 'profile', 'user'));
    }

    // Proses mengupdate petugas
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
            'jns_kelamin' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
            'alamat' => 'required',
            'image' => 'mimes:jpg,jpeg,png,svg,webp',
            'username' => 'required',
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'min' => ':attribute minimal :min karakter !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->image <> '') {
            $foto = $request->file('image');
            $namafoto = 'Petugas-'.str_replace(' ', '-', $request->get('nm_lengkap')).Str::random(5).'.'.$foto->extension();
            $foto->move(public_path('images/'), $namafoto);
            $fotoNama = 'images/'.$namafoto;
        } else {
            $fotoNama = null;
        }

        $profile = Profile::find($id);
        $profile->nik = $request->get('nik');
        $profile->nama_lengkap = $request->get('nama_lengkap');
        $profile->email = $request->get('email');
        $profile->no_telp = $request->get('no_telp');
        $profile->jns_kelamin = $request->get('jns_kelamin');
        $profile->tmpt_lahir = $request->get('tmpt_lahir');
        $profile->tgl_lahir = $request->get('tgl_lahir');
        $profile->alamat = $request->get('alamat');
        if ($request->image <> '') {
            File::delete($profile->foto);

            $profile->foto = $fotoNama;
        }
        $profile->save();

        $user = User::find($profile->user_id);
        $user->name = $request->get('nama_lengkap');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        if ($request->password <> '') {
            $user->password = Hash::make($request->get('password'));
        }
        $user->save();

        return redirect()->route('petugas')->with('berhasil', 'Berhasil mengupdate profile.');
    }
}
