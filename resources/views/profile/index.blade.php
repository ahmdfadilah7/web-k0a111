@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

    <div class="section-header">
        @if(Auth::user()->role == 'Petugas' || Auth::user()->role == 'Administrator')
            <h1>Pemohon SIM</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('profile') }}">Pemohon SIM</a></div>
            </div>
        @else
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('profile') }}">Profile</a></div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h4>Profile</h4>
                </div>
                @if(Auth::user()->role == 'Petugas')
                    {!! Form::open(['method' => 'post', 'route' => ['profile.export']]) !!}
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label text-md-right col-12 col-md-4 col-lg-4">Status</label>
                                    <div class="col-sm-12 col-md-8">
                                        <select name="status" class="form-control selectric">
                                            <option value="">- Pilih -</option>
                                            <option value="semua">Semua</option>
                                            <option value="0">Belum Diverifikasi</option>
                                            <option value="1">Sudah Diverifikasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label text-md-right col-12 col-md-4 col-lg-4">Tanggal Pendaftaran</label>
                                    <div class="col-sm-12 col-md-8">
                                        <input type="date" name="tanggal" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
                                    <a href="{{ route('profile.exportsemua') }}" class="btn btn-success"><i class="fas fa-file-export"></i> Export Semua</a>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                @elseif(Auth::user()->role == 'Administrator')
                    {!! Form::open(['method' => 'post', 'route' => ['profile.export']]) !!}
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label text-md-right col-12 col-md-4 col-lg-4">Status</label>
                                    <div class="col-sm-12 col-md-8">
                                        <select name="status" class="form-control selectric">
                                            <option value="">- Pilih -</option>
                                            <option value="semua">Semua</option>
                                            <option value="0">Belum Diverifikasi</option>
                                            <option value="1">Sudah Diverifikasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label text-md-right col-12 col-md-4 col-lg-4">Tempat Pendaftaran</label>
                                    <div class="col-sm-12 col-md-8">
                                        <select name="tempat" class="form-control selectric">
                                            <option value="">- Pilih -</option>
                                            @foreach($polres as $row)
                                                <option value="{{ $row->id }}">{{ $row->nama_polres }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label text-md-right col-12 col-md-4 col-lg-4">Tanggal Pendaftaran</label>
                                    <div class="col-sm-12 col-md-8">
                                        <input type="date" name="tanggal" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
                                    <a href="{{ route('profile.exportsemua') }}" class="btn btn-success"><i class="fas fa-file-export"></i> Export Semua</a>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        #
                                    </th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Foto</th>
                                    <th>QrCode</th>
                                    <th>Status</th>
                                    <th>Tempat Pendaftaran</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(function() {
            $('#table-1').dataTable({
                processing: true,
                serverSide: true,
                'ordering': 'true',
                ajax: {
                    url: "{{ route('profile.list') }}",
                    data: function(d) {}
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp'
                    },
                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'qrcode',
                        name: 'qrcode'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'polres',
                        name: 'polres'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>

@endsection