<?php

namespace App\Http\Controllers;

use App\Exports\ProfileExport;
use App\Models\Polres;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    // Menampilkan halaman profile
    public function index()
    {
        $setting = Setting::first();
        $polres = Polres::get();

        return view('profile.index', compact('setting', 'polres'));
    }

    // Proses menampilkan data profile dengan datatable
    public function listData()
    {
        if (Auth::user()->role == 'Administrator') {
            $data = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')->join('users', 'profiles.user_id', 'users.id')->join('polres', 'profiles.polres_id', 'polres.id')->where('role', 'Member')->get();
        } elseif (Auth::user()->role == 'Member') {
            $data = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')->join('users', 'profiles.user_id', 'users.id')->join('polres', 'profiles.polres_id', 'polres.id')->where('role', 'Member')->where('user_id', Auth::user()->id);
        } elseif (Auth::user()->role == 'Petugas') {
            $data = Profile::select('profiles.*', 'polres.nama_polres', 'users.role')->join('users', 'profiles.user_id', 'users.id')->join('polres', 'profiles.polres_id', 'polres.id')->where('role', 'Member')->where('role', 'Member')->where('polres_id', Auth::user()->profile->polres_id);
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
            ->addColumn('status', function($row) {
                if ($row->status == null) {
                    $btn = '<span class="badge badge-warning">Belum diverifikasi</span>';
                } elseif ($row->status == 0) {
                    $btn = '<span class="badge badge-warning">Belum diverifikasi</span>';
                } elseif ($row->status == 1) {
                    $btn = '<span class="badge badge-success">Sudah diverifikasi</span>';
                }
                return $btn;
            })
            ->addColumn('polres', function($row) {
                $polres = '<span class="badge badge-primary">'.$row->nama_polres.'</span>';

                return $polres;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('profile.show', $row->user_id).'" class="btn btn-info btn-sm mr-2" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </a>';
                if ($row->user_id == Auth::user()->id) {
                    $btn .= '<a href="'.route('profile.edit', $row->user_id).'" class="btn btn-primary btn-sm mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                }
                if (Auth::user()->role == 'Petugas') {
                    if (Auth::user()->profile->jabatan->jabatan == 'Baur SIM' && $row->status <> 1) {
                        $btn .= '<a href="'.route('profile.verifikasi', $row->id).'" class="btn btn-success btn-sm mr-2" title="Verifikasi">
                                <i class="fas fa-check"></i>
                            </a>';
                    }
                }

                return $btn;
            })
            ->rawColumns(['action', 'qrcode', 'foto', 'status', 'created_at', 'polres'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman detail profile
    public function show($id)
    {
        $setting = Setting::first();
        $profile = Profile::select('profiles.*', 'polres.nama_polres')->join('polres', 'profiles.polres_id', 'polres.id')->where('user_id', $id)->first();

        return view('profile.show', compact('setting', 'profile'));
    }

    // Proses verifikasi profile
    public function verifikasi($id)
    {
        $profile = Profile::find($id);
        $profile->status = 1;
        $profile->save();

        return redirect()->route('profile')->with('berhasil', 'Berhasil memverifikasi profile.');
    }

    // Proses export profile
    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ],
        [
            'required' => ':attribute wajib diisi !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if (Auth::user()->role == 'Petugas') {
            $tempat = Auth::user()->profile->polres_id;
        } elseif (Auth::user()->role == 'Administrator') {
            $tempat = $request->tempat;
        }
        // return Excel::download(new ProfileExport, 'Data-Profile-'.Carbon::now()->timestamp.'.xlsx');
        return (new ProfileExport($request->status, $tempat, $request->tanggal))->download('Data-Pemohon-SIM-'.Carbon::now()->timestamp.'.xlsx');
    }

    // Proses export profile
    public function export_semua(Request $request)
    {
        if (Auth::user()->role == 'Petugas') {
            $tempat = Auth::user()->profile->polres_id;
        } elseif (Auth::user()->role == 'Administrator') {
            $tempat = $request->tempat;
        }
        // return Excel::download(new ProfileExport, 'Data-Profile-'.Carbon::now()->timestamp.'.xlsx');
        return (new ProfileExport($request->status, $tempat, $request->tanggal))->download('Data-Pemohon-SIM-'.Carbon::now()->timestamp.'.xlsx');
    }

    // Menampilkan halaman edit profile
    public function edit($id)
    {
        $setting = Setting::first();
        $profile = Profile::where('user_id', $id)->first();
        $user = User::find($id);

        return view('profile.edit', compact('setting', 'profile', 'user'));
    }

    // Proses mengupdate profile
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
            $namafoto = 'Member-'.str_replace(' ', '-', $request->get('nm_lengkap')).Str::random(5).'.'.$foto->extension();
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

        return redirect()->route('profile')->with('berhasil', 'Berhasil mengupdate profile.');
    }
}
