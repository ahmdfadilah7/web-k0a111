<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    // Menampilkan halaman setting website
    public function index()
    {
        $setting = Setting::first();
        $count = Setting::count();

        return view('setting.index', compact('setting', 'count'));
    }

    // Proses menampilkan data setting website dengan datatable
    public function listData()
    {
        $data = Setting::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('logo', function($row) {
                if ($row->logo <> '') {
                    $img = '<img src="'.url($row->logo).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Belum ada gambar</i>';
                }
                return $img;
            })
            ->addColumn('favicon', function($row) {
                if ($row->favicon <> '') {
                    $img = '<img src="'.url($row->favicon).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Belum ada gambar</i>';
                }
                return $img;
            })
            ->addColumn('bg_login', function($row) {
                if ($row->favicon <> '') {
                    $img = '<img src="'.url($row->bg_login).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Belum ada gambar</i>';
                }
                return $img;
            })
            ->addColumn('bg_register', function($row) {
                if ($row->bg_register <> '') {
                    $img = '<img src="'.url($row->bg_register).'" width="50">';
                } else {
                    $img = '<i class="text-danger">Belum ada gambar</i>';
                }
                return $img;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('setting.edit', $row->id).'" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>';

                return $btn;
            })
            ->rawColumns(['action', 'logo', 'favicon', 'bg_login', 'bg_register'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman tambah setting website
    public function create()
    {
        $setting = Setting::first();

        return view('setting.add', compact('setting'));
    }

    // Proses tambah setting website
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_website' => 'required',
            'logo' => 'required|mimes:png,jpg,jpeg,svg,webp',
            'favicon' => 'required|mimes:png,jpg,jpeg,svg,webp',
            'bg_login' => 'required|mimes:png,jpg,jpeg,svg,webp',
            'bg_register' => 'required|mimes:png,jpg,jpeg,svg,webp',
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'mimes' => ':attribute harus berupa format: png,jpg,jpeg,svg,webp'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $logo = $request->file('logo');
        $namalogo = 'Logo-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$logo->extension();
        $logo->move(public_path('images/'), $namalogo);
        $logoNama = 'images/'.$namalogo;

        $favicon = $request->file('favicon');
        $namafavicon = 'Favicon-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$favicon->extension();
        $favicon->move(public_path('images/'), $namafavicon);
        $faviconNama = 'images/'.$namafavicon;

        $bg_login = $request->file('bg_login');
        $namabg_login = 'BG-Login-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$bg_login->extension();
        $bg_login->move(public_path('images/'), $namabg_login);
        $bg_loginNama = 'images/'.$namabg_login;

        $bg_register = $request->file('bg_register');
        $namabg_register = 'BG-Register-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$bg_register->extension();
        $bg_register->move(public_path('images/'), $namabg_register);
        $bg_registerNama = 'images/'.$namabg_register;

        Setting::create([
            'nama_website' => $request->get('nama_website'),
            'logo' => $logoNama,
            'favicon' => $faviconNama,
            'bg_login' => $bg_loginNama,
            'bg_register' => $bg_registerNama,
        ]);

        return redirect()->route('setting')->with('berhasil', 'Berhasil menambahkan setting website.');
    }

    // Menampilkan halaman edit setting website
    public function edit($id)
    {
        $setting = Setting::find($id);
        
        return view('setting.edit', compact('setting'));
    }

    // Proses mengedit setting website
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_website' => 'required',
            'logo' => 'mimes:png,jpg,jpeg,webp,svg',
            'favicon' => 'mimes:png,jpg,jpeg,webp,svg',
            'bg_login' => 'mimes:png,jpg,jpeg,webp,svg',
            'bg_register' => 'mimes:png,jpg,jpeg,webp,svg',
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'mimes' => ':attribute harus berupa format: png,jpg,jpeg,svg,webp'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        if ($request->logo <> '') {
            $logo = $request->file('logo');
            $namalogo = 'Logo-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$logo->extension();
            $logo->move(public_path('images/'), $namalogo);
            $logoNama = 'images/'.$namalogo;
        } else {
            $logoNama = null;
        }

        if ($request->favicon <> '') {
            $favicon = $request->file('favicon');
            $namafavicon = 'Favicon-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$favicon->extension();
            $favicon->move(public_path('images/'), $namafavicon);
            $faviconNama = 'images/'.$namafavicon;
        } else {
            $faviconNama = null;
        }

        if ($request->bg_login <> '') {
            $bg_login = $request->file('bg_login');
            $namabg_login = 'BG-Login-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$bg_login->extension();
            $bg_login->move(public_path('images/'), $namabg_login);
            $bg_loginNama = 'images/'.$namabg_login;
        } else {
            $bg_loginNama = null;
        }

        if ($request->bg_register <> '') {
            $bg_register = $request->file('bg_register');
            $namabg_register = 'BG-Register-'.str_replace(' ', '-', $request->get('nama_website')).Str::random(5).'.'.$bg_register->extension();
            $bg_register->move(public_path('images/'), $namabg_register);
            $bg_registerNama = 'images/'.$namabg_register;
        } else {
            $bg_registerNama = null;
        }

        $setting = Setting::find($id);
        $setting->nama_website = $request->get('nama_website');
        if ($logoNama <> null) {
            File::delete($setting->logo);
            $setting->logo = $logoNama;
        }
        if ($faviconNama <> null) {
            File::delete($setting->favicon);
            $setting->favicon = $faviconNama;
        }
        if ($bg_loginNama <> null) {
            File::delete($setting->bg_login);
            $setting->bg_login = $bg_loginNama;
        }
        if ($bg_registerNama <> null) {
            File::delete($setting->bg_register);
            $setting->bg_register = $bg_registerNama;
        }
        $setting->save();

        return redirect()->route('setting')->with('berhasil', 'Berhasil mengubah setting website.');
    }
}
