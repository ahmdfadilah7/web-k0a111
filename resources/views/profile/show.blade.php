@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('profile') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Profile</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('profile') }}">Profile</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h4>Profile</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ $profile->nik }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ $profile->nama_lengkap }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ $profile->email }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No Telepon</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ $profile->no_telp }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Kelamin</label>
                        <div class="col-sm-12 col-md-7">
                            @if($profile->jns_kelamin == 'L')
                                <input type="text" class="form-control" value="Laki - Laki" readonly>
                            @else
                                <input type="text" class="form-control" value="Perempuan" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ $profile->tmpt_lahir }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" value="{{ date('d M Y', strtotime($profile->tgl_lahir)) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Alamat</label>
                        <div class="col-sm-12 col-md-7">
                            {!! $profile->alamat !!}
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto</label>
                        <div class="col-sm-12 col-md-7">
                            <a href="{{ url($profile->foto) }}" target="_blank">
                                <img src="{{ url($profile->foto) }}" width="150">
                            </a>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">QR Code</label>
                        <div class="col-sm-12 col-md-7">
                            <a href="{{ url($profile->qrcode) }}" target="_blank">
                                <img src="{{ url($profile->qrcode) }}" width="150">
                            </a>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                        <div class="col-sm-12 col-md-7">
                            @if($profile->status == 0 && $profile->status == null)
                                <span class="badge badge-warning">Belum diverifikasi</span>
                            @else
                                <span class="badge badge-success">Sudah diverifikasi</span>                                
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Pendaftaran</label>
                        <div class="col-sm-12 col-md-7">
                            <span class="badge badge-primary">{{ $profile->nama_polres }}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Pendaftaran</label>
                        <div class="col-sm-12 col-md-7">
                            <span class="badge badge-primary">{{ date('d M Y', strtotime($profile->created_at)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection