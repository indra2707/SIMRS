@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Template Jabatan Disposisi</h3>
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
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary add-btn-djabatan">
                                    <span class="fa fa-plus"></span> Tambah Jabatan
                                </button>
                            </div>

                            <div style="width: 260px;">
                                <select id="filter_id_aproval" class="form-select select2"
                                    data-placeholder="Filter berdasarkan Approval...">
                                    <option value="">-- Semua Approval --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_djabatan" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Urutan</th>
                                            <th class="f-light">Approval</th>
                                            <th class="f-light">Nama Jabatan</th>
                                            <th class="f-light">Pemegang Jabatan</th>
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

    {{-- Modal Form Jabatan --}}
    <div class="modal fade" id="modal-djabatan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-djabatan" autocomplete="off">
                        <input type="hidden" name="id">

                        <!-- Approval -->
                        <label class="col-form-label col-sm-3">Approval</label>
                        <div class="col-sm-9">
                            <select class="form-select select2" name="id_aproval" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- Urutan -->
                        <label class="col-form-label col-sm-3">Urutan</label>
                        <div class="col-sm-9">
                            <input type="number" name="urutan" class="form-control" min="1" required
                                placeholder="1, 2, 3, dst..." />
                        </div>

                        <!-- Nama Jabatan -->
                        <label class="col-form-label col-sm-3">Nama Jabatan</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_jabatan" class="form-control" required
                                placeholder="Contoh: Head of Human Capital & General Affair" />
                        </div>

                        <!-- Pegawai -->
                        <label class="col-form-label col-sm-3">Pemegang Jabatan</label>
                        <div class="col-sm-9">
                            <select class="form-select select2" name="id_pegawai"
                                data-placeholder="---- Opsional ----">
                                <option></option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-djabatan" type="button">
                        <span class="fa fa-check"></span> Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('surat.disposisi-jabatan.script')
@endsection