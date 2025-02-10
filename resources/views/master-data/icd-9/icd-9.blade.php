@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
<h3>ICD-9</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">{{ $menuTitle }}</li>
<li class="breadcrumb-item active">{{ $menuSubtitle }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    {{-- Add Button --}}
                    <button class="btn btn-primary add-btn">
                        <span class="fa fa-plus"></span>
                        <span>Tambah</span>
                    </button>
                </div>
                <div class="card-body">
                    {{-- Table View --}}
                    <div class="table-responsive custom-scrollbar">
                        <table id="table_icd9" data-classes="table table-responsive-sm" data-toggle="table">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Form --}}
<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Title</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="theme-form g-3 needs-validation form-icd9" novalidate="" autocomplete="off">
                    @csrf
                    {{-- Hidden Input --}}
                    <div class="mb-3 row">
                        <input type="hidden" name="id">
                    </div>
                    {{-- Kode --}}
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label" for="kode">Kode</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="kode" type="text" placeholder="Kode..." required>
                        </div>
                    </div>
                    {{-- Nama --}}
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="nama" type="text" placeholder="Nama..." required>
                        </div>
                    </div>
                    {{-- Kelas --}}
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label" for="kelas">Kelas</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="kelas" type="text" placeholder="Kelas..." required>
                        </div>
                    </div>
                    <div class="media mb-2">
                        <label class="col-sm-3 col-form-label m-r-10">Aktif</label>
                        <div class="media-body text-end">
                            <label class="switch">
                                <input class="form-control" name="status" type="checkbox" checked>
                                <span class="switch-state"></span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                    <span class="fa fa-times"></span> Batal</button>
                <button class="btn btn-primary save-btn" type="button"><span class="fa fa-check"></span>
                    Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
@include('master-data.icd-9.script')
@endsection
