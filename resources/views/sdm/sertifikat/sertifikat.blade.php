@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .select2-fixed {
            width: 210px;
        }

        .select2-fixed .select2-container {
            width: 100% !important;
        }

        /* tinggi select tetap */
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>

@endsection

@section('breadcrumb-title')
    <h3>Sertifikat</h3>
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
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <!-- Kiri: Tombol & Filter -->
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary add-btn-sertifikat">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah Sertifikat</span>
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_sertifikat" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Pekerja</th>
                                            <th class="f-light">Jenis</th>
                                            <th class="f-light">Nama</th>
                                            <th class="f-light">Penyelenggara</th>
                                            <th class="f-light">Tahun</th>
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

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-sertifikat" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-wizard form-sertifikat" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_sertifikat">
                        </div>

                        <!-- Pegawai -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="id_pegawai">Pegawai</label>
                            <div class="col-sm-10">
                                <select class="form-select select2" name="id_pegawai"
                                    data-placeholder="---- Pilih Salah Satu ----" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <!-- Nama Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama_sertifikat">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_sertifikat" name="nama_sertifikat"
                                    placeholder="Nama Sertifikat..." required>
                            </div>
                        </div>

                        <!-- Penyelenggara -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="penyelenggara_sertifikat">Penyelenggara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="penyelenggara_sertifikat"
                                    name="penyelenggara_sertifikat" placeholder="Penyelenggara Sertifikat..." required>
                            </div>
                        </div>

                        <!-- Tahun Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tahun_sertifikat">Tahun</label>
                            <div class="col-sm-10">
                                <input type="text" name="tahun_sertifikat" id="tahun_sertifikat"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Jenis Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_sertifikat">Jenis</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="jenis_sertifikat" name="jenis_sertifikat" required>
                                    <option value=""></option>
                                    <option value="Pelatihan">Pelatihan</option>
                                    <option value="Sertifikat">Sertifikat</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-sertifikat"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-sertifikat"
                                    id="btn-attach-sertifikat"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-sertifikat" name="lampiran-sertifikat"
                                    accept="application/pdf" class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-sertifikat">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-sertifikat" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Sertifikat -->
    <div class="modal fade" id="modal-preview-pdf-sertifikat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-sertifikat" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('sdm.sertifikat.script')
@endsection