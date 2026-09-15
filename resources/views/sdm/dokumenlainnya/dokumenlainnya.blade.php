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
    <h3>Dokumen Lainnya</h3>
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
                                <button class="btn btn-primary add-btn-dokumen-lainnya">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah Hasil MCU</span>
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_dokumen_lainnya" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Pekerja</th>
                                            <th class="f-light">Jenis</th>
                                            <th class="f-light">Nomor</th>
                                            <th class="f-light">Catatan</th>
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
    <div class="modal fade" id="modal-dokumen-lainnya" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-wizard form-dokumen-lainnya" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_dokumen_lainnya">
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

                        <!-- Jenis Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_dokumen_lainnya">Jenis Dokumen</label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="jenis_dokumen_lainnya" name="jenis_dokumen_lainnya"
                                    required>
                                    <option value=""></option>
                                    <option value="KTP">KTP</option>
                                    <option value="KK">KK</option>
                                    <option value="NPWP">NPWP</option>
                                    <option value="BPJS Kesehatan">BPJS Kesehatan</option>
                                    <option value="BPJS Ketenagakerjaan">BPJS Ketenagakerjaan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Nomor Dokumen-->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_dokumen_lainnya">Nomor Dokumen</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_dokumen_lainnya"
                                    name="nomor_dokumen_lainnya" placeholder="Nomor Dokumen..." required>
                            </div>
                        </div>

                        <!-- catatan Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="catatan_dokumen">Catatan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="catatan_dokumen" name="catatan_dokumen_lainnya" rows="3"
                                    required placeholder="Catatan..."></textarea>
                            </div>
                        </div>

                        <!-- Dokumen Lainnya -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-dokumen-lainnya"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-dokumen-lainnya"
                                    id="btn-attach-dokumen-lainnya"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-dokumen-lainnya" name="lampiran-dokumen-lainnya"
                                    accept="application/pdf" class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-dokumen-lainnya">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-dokumen-lainnya" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Lainnya -->
    <div class="modal fade" id="modal-preview-pdf-dokumen" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-dokumen" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('sdm.dokumenlainnya.script')
@endsection