@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('jabatan') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Jabatan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('jabatan') }}">Jabatan</a></div>
            <div class="breadcrumb-item active">Edit</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Jabatan</h4>
                </div>
                <div class="card-body">
                    {!! Form::model($jabatan, ['method' => 'post', 'route' => ['jabatan.update', $jabatan->id], 'enctype' => 'multipart/form-data']) !!}
                    @method('PUT')
                    @csrf
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jabatan</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" name="jabatan" class="form-control" value="{{ $jabatan->jabatan }}">
                            <i class="text-danger">{{ $errors->first('jabatan') }}</i>
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
