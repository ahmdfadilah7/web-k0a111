<?php

namespace App\Http\Controllers;

use App\Models\Polres;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PolresController extends Controller
{
    // Menampilkan halaman polres
    public function index()
    {
        $setting = Setting::first();

        return view('polres.index', compact('setting'));
    }

    // Proses menampilkan data polres dengan datatable
    public function listData()
    {
        $data = Polres::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('polres.edit', $row->id).'" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-edit"></i>
                    </a>';

                $btn .= '<a href="'.route('polres.delete', $row->id).'" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        
        return $datatables;
    }

    // Menampilkan halaman tambah polres
    public function create()
    {
        $setting = Setting::first();

        return view('polres.add', compact('setting'));
    }

    // Proses menambahkan polres
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_polres' => 'required'
        ],
        [
            'required' => ':attribute wajib diisi !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        Polres::create([
            'nama_polres' => $request->get('nama_polres')
        ]);

        return redirect()->route('polres')->with('berhasil', 'Berhasil menambahkan polres baru.');
    }

    // Menampilkan halaman edit polres
    public function edit($id)
    {
        $setting = Setting::first();
        $polres = Polres::find($id);

        return view('polres.edit', compact('setting', 'polres'));
    }

    // Proses mengupdate polres
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_polres' => 'required'
        ],
        [
            'required' => ':attribute wajib diisi !!'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $polres = Polres::find($id);
        $polres->nama_polres = $request->get('nama_polres');
        $polres->save();

        return redirect()->route('polres')->with('berhasil', 'Berhasil mengupdate polres.');
    }

    // Proses menghapus polres
    public function destroy($id)
    {
        $polres = Polres::find($id);
        $polres->delete();

        return redirect()->route('polres')->with('berhasil', 'Berhasil menghapus polres.');
    }
}
