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
    <h3>STR dan SIP</h3>
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
                                <button class="btn btn-primary add-btn-str">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah STR dan SIP</span>
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_str" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Pekerja</th>
                                            <th class="f-light">Nomor STR</th>
                                            <th class="f-light">Jenis</th>
                                            <th class="f-light">Masa Berlaku</th>
                                            <th class="f-light">Tanggal Mulai</th>
                                            <th class="f-light">Tanggal Berakhir</th>
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
    <div class="modal fade" id="modal-str" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-wizard form-str" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_str">
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

                        <!-- Nomor STR -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_str">Nomor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_str" name="nomor_str"
                                    placeholder="Nomor..." required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_str">Jenis</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="jenis_str" name="jenis_str" required>
                                    <option value=""></option>
                                    <option value="STR">STR</option>
                                    <option value="SIP">SIP</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai_str">Tanggal Mulai</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_mulai_str" id="tanggal_mulai_str"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Mulai" data-language="en" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Masa Berlaku -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="masa_berlaku_str">Masa Berlaku</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="masa_berlaku_str"
                                        id="masa_berlaku_str" value="1">
                                    <label class="form-check-label" for="masa_berlaku_str">Seumur Hidup</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_berakhir_str">Tanggal Berakhir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_berakhir_str" id="tanggal_berakhir_str"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-str"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-str"
                                    id="btn-attach-str"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-str" name="lampiran-str" accept="application/pdf"
                                    class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-str">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-str" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen STR dan SIP -->
    <div class="modal fade" id="modal-preview-pdf-str" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-str" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('sdm.strsip.script')
@endsection