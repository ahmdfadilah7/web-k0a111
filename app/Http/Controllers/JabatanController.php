<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    // Menampilkan halaman jabatan
    public function index()
    {
        $setting = Setting::first();

        return view('jabatan.index', compact('setting'));
    }

    // Proses menampilkan data jabatan dengan datatable
    public function listData()
    {
        $data = Jabatan::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('jabatan.edit', $row->id).'" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-edit"></i>
                    </a>';

                $btn .= '<a href="'.route('jabatan.delete', $row->id).'" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman tambah jabatan
    public function create()
    {
        $setting = Setting::first();

        return view('jabatan.add', compact('setting'));
    }

    // Proses menambahkan jabatan
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required'
        ],
        [
            'required' => ':attribute wajib diisi !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        Jabatan::create([
            'jabatan' => $request->get('jabatan')
        ]);

        return redirect()->route('jabatan')->with('berhasil', 'Berhasil menambahkan jabatan baru.');
    }

    // Menampilkan halaman edit jabatan
    public function edit($id)
    {
        $setting = Setting::first();
        $jabatan = Jabatan::find($id);

        return view('jabatan.edit', compact('setting', 'jabatan'));
    }

    // Proses mengupdate jabatan
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        $jabatan = Jabatan::find($id);
        $jabatan->jabatan = $request->get('jabatan');
        $jabatan->save();

        return redirect()->route('jabatan')->with('berhasil', 'Berhasil mengupdate jabatan.');
    }

    // Proses menghapus jabatan
    public function destroy($id)
    {
        $jabatan = Jabatan::find($id);
        $jabatan->delete();

        return redirect()->route('jabatan')->with('berhasil', 'Berhasil menghapus jabatan.');
    }
}
