<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // Menampilkan halaman user
    public function index()
    {
        $setting = Setting::first();

        return view('user.index', compact('setting'));
    }

    // Proses menampilkan data user dengan datatable
    public function listData()
    {
        $data = User::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('role', function($row) {
                if ($row->role == 'Member') {
                    $role = 'Pemohon SIM';
                } else {
                    $role = $row->role;
                }
                return $role;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('user.edit', $row->id).'" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-edit"></i>
                    </a>';
                if ($row->id <> Auth::user()->id) {

                    $btn .= '<a href="'.route('user.delete', $row->id).'" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </a>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'role'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman tambah user
    public function create()
    {
        $setting = Setting::first();

        return view('user.add', compact('setting'));
    }

    // Proses menambahkan user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required'
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        User::create([
            'name' => $request->get('nama'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'role' => $request->get('role')
        ]);

        return redirect()->route('user')->with('berhasil', 'Berhasil menambahkan user baru.');
    }

    // Menampilkan halaman edit user
    public function edit($id)
    {
        $setting = Setting::first();
        $user = User::find($id);

        return view('user.edit', compact('setting', 'user'));
    }

    // Proses mengupdate user
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'username' => 'required',
            'role' => 'required',
        ],
        [
            'required' => ':attribute wajib diisi !!',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        $user = User::find($id);
        $user->name = $request->get('nama');
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->role = $request->get('role');
        if ($request->password <> '') {
            $user->password = Hash::make($request->get('password'));
        }
        $user->save();

        return redirect()->route('user')->with('berhasil', 'Berhasil mengubah user.');
    }

    // Proses menghapus user
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        if ($user->profile->foto <> '') {
            File::delete($user->profile->foto);
        }
        if ($user->profile->qrcode <> '') {
            File::delete($user->profile->qrcode);
        }

        return redirect()->route('user')->with('berhasil', 'Berhasil menghapus user.');
    }
}
