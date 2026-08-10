@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        /* tinggi select tetap */
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-fixed {
            width: 220px;
            /* kunci lebar */
            min-width: 220px;
            max-width: 220px;
        }
    </style>

@endsection

@section('breadcrumb-title')
    <h3>Aproval</h3>
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
                        <div class="d-flex align-item-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary add-btn">
                                    <span class="fa fa-plus"></span> Tambah
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_aproval" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">Aproval</th>
                                            <th class="f-light">Status</th>
                                            <th>Action</th>
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

    {{-- Modal Form aproval --}}
    <div class="modal fade" id="modal-aproval" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-aproval" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <!-- Aproval Name -->
                        <label for="nama_aproval" class="col-form-label col-sm-2">Aproval Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama_aproval" id="nama_aproval" class="form-control" required
                                placeholder="Aproval Name..." />
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


    <!-- Data Hirarki -->
    <div class="modal fade" id="modal-hirarki" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Add Button --}}
                    <button class="btn btn-primary add-hirarki">
                        <span class="fa fa-plus"></span>
                        <span> Tambah Hirarki</span>
                    </button>
                    {{-- Table View --}}
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive signal-table">
                            <table id="table_aproval_detail" class="table table-hover" data-buttons-class="primary"
                                data-toggle="table">
                                <thead class="text-bold text-white text-uppercase text-center">
                                    <tr>
                                        <th class="f-light">No</th>
                                        <th class="f-light">Level</th>
                                        <th class="f-light">Nama Pegawai</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form hirarki --}}
    <div class="modal fade" id="modal-input-hirarki" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-aproval-detail" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id_detail">
                        <input type="hidden" name="id_aproval">

                        <!-- Aproval Name -->
                        <label for="nama_aproval" class="col-form-label col-sm-2">Aproval Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama_aproval" id="nama_aproval" class="form-control" readonly
                                placeholder="Aproval Name..." />
                        </div>

                        <!-- Parent Jabatan -->
                        <label for="parent_jabatan" class="col-form-label col-sm-2">Parent Jabatan</label>
                        <div class="col-sm-10"> <select class="form-select form-control select2" name="parent_jabatan" required>
                                <option></option>
                                <option value="0">Director</option>
                                <option value="1">Vice Director</option>
                                <option value="2">Head</option>
                            </select>
                        </div>

                        <!-- Nama Pegawai -->
                        <label for="id_pegawai" class="col-form-label col-sm-2">Nama Pegawai</label>
                        <div class="col-sm-10">
                            <select id="id_pegawai" class="form-select select2" name="id_pegawai"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option></option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-detail" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('surat.aproval.script')
@endsection