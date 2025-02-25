@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>COA</h3>
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
                    <div class="card-body">
                        {{-- Add Button --}}
                        <button class="btn btn-secondary add-btn">
                            <span class="fa fa-plus"></span>
                            <span> Tambah</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_icd10" class="table table-hover" data-toggle="table">
                                    <thead class="bg-secondary text-light text-bold text-uppercase text-center">
                                        <tr>
                                            {{-- <th scope="col">No</th> --}}
                                            <th scope="col"><b>Kode</b></th>
                                            <th scope="col"><b>Nama ICD 10</b></th>
                                            <th scope="col"><b>Status</b></th>
                                            <th scope="col"><b>Action</b></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-coa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-coa" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>
                        {{-- Kode --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="kode"><b>Kode COA</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="kode" type="text" placeholder="Kode COA..."
                                    required>
                            </div>
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Nama COA</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="nama" type="text" placeholder="Nama COA..."
                                    required>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Kategori</b></label>
                            <div class="col-sm-10">
                                <select class="form-select form-control select2" name="kategori" required>
                                    <option></option>
                                    <option value="Tindakan">Tindakan</option>
                                    <option value="Penjamin">Penjamin</option>
                                    <option value="Obat">Obat & BMHP</option>
                                </select>
                            </div>
                        </div>
                        {{-- Satus --}}
                        <div class="media mb-2">
                            <label class="col-sm-2 col-form-label m-r-10"><b>Status</b></label>
                            <div class="media-body">
                                <label class="switch">
                                    <input class="form-control" name="status" type="checkbox" checked>
                                    <span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('master-data.coa.script')
@endsection
