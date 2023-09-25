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
            <div class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></div>
            <div class="breadcrumb-item active">Edit</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                    {!! Form::model($profile, ['method' => 'post', 'route' => ['profile.update', $profile->id], 'enctype' => 'multipart/form-data']) !!}
                    @method('PUT')
                    @csrf
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" name="nik" class="form-control" value="{{ $profile->nik }}">
                                <i class="text-danger">{{ $errors->first('nik') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $profile->nama_lengkap }}">
                                <i class="text-danger">{{ $errors->first('nama_lengkap') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email" class="form-control" value="{{ $profile->email }}">
                                <i class="text-danger">{{ $errors->first('email') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No Telepon</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" name="no_telp" class="form-control" value="{{ $profile->no_telp }}">
                                <i class="text-danger">{{ $errors->first('no_telp') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Kelamin</label>
                            <div class="col-sm-12 col-md-7">
                                <select name="jns_kelamin" class="form-control selectric">
                                    <option value="">- Pilih -</option>
                                    <option value="L" @if($profile->jns_kelamin == 'L') selected @endif>Laki - Laki</option>
                                    <option value="P" @if($profile->jns_kelamin == 'P') selected @endif>Perempuan</option>
                                </select>
                                <i class="text-danger">{{ $errors->first('jns_kelamin') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="tmpt_lahir" class="form-control" value="{{ $profile->tmpt_lahir }}">
                                <i class="text-danger">{{ $errors->first('tmpt_lahir') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="date" name="tgl_lahir" class="form-control" value="{{ $profile->tgl_lahir }}">
                                <i class="text-danger">{{ $errors->first('tgl_lahir') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Alamat</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea name="alamat" class="form-control summernote-simple" id="alamat" rows="10">{{ $profile->alamat }}</textarea>
                                <i class="text-danger">{{ $errors->first('alamat') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto Sebelumnya</label>
                            <div class="col-sm-12 col-md-7">
                                <a href="{{ url($profile->foto) }}" target="_blank">
                                    <img src="{{ url($profile->foto) }}" width="150">
                                </a>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview">
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="image" id="image-upload" />
                                </div>
                                <i class="text-danger">{{ $errors->first('image') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Username</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                <i class="text-danger">{{ $errors->first('username') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="password" name="password" class="form-control">
                                <i>*Isi jika ingin mengganti password.</i>
                                <i class="text-danger">{{ $errors->first('password') }}</i>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection