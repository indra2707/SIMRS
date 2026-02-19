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
    <h3>Perizinan</h3>
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

                                <div class="select2-fixed">
                                    <select class="form-select select3" name="status" id="filter-status">
                                        <option></option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="bs-bars">
                                    <input type="text" class="form-control js-daterangepicker text-center"
                                        style="width:220px" placeholder="dd/mm/yyyy - dd/mm/yyyy" data-language="en">
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 small fw-semibold">

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        style="width:15px; height:15px; background-color: #ffaa05; display:inline-block;"></span>
                                    <span>Perizinan akan berakhir dalam 3 Bulan</span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        style="width:15px; height:15px; background-color: #FF0000; display:inline-block;"></span>
                                    <span>Perizinan akan berakhir dalam 1 Bulan</span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        style="width:15px; height:15px; background-color: #808080; display:inline-block;"></span>
                                    <span>Perizinan sudah berakhir</span>
                                </div>

                            </div>
                        </div>

                        <input type="hidden" name="tgl_awal" id="tgl_awal">
                        <input type="hidden" name="tgl_akhir" id="tgl_akhir">
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_perizinan" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">Nomor Perizinan</th>
                                            <th class="f-light">Jenis Perizinan</th>
                                            <th class="f-light">Tanggal Awal</th>
                                            <th class="f-light">Tanggal Akhir</th>
                                            <th class="f-light">Sisa Hari</th>
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

    {{-- Modal Form perizinan --}}
    <div class="modal fade" id="modal-perizinan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-perizinan" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <!-- Nomor perizinan  -->
                        <label for="nomor_kontrak" class="col-form-label col-sm-2">Nomor perizinan</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="nomor_perizinan" type="text"
                                placeholder="Nomor perizinan..." required>
                        </div>

                        <!-- Jenis Perizinan  -->
                        <label for="jenis_perizinan" class="col-form-label col-sm-2">Jenis Perizinan</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="jenis_perizinan" type="text"
                                placeholder="Jenis Perizinan" required>
                        </div>

                        <!-- Tanggal Awal  -->
                        <label for="tanggal_mulai" class="col-form-label col-sm-2">Tanggal Awal</label>
                        <div class="col-sm-4">
                            <input class="form-control form-control js-datepicker digits" name="tgl_awal" type="text"
                                placeholder="Tanggal Awal..." data-language="en" required>
                        </div>

                        <!-- Tanggal Akhir  -->
                        <label for="tanggal_selesai" class="col-form-label col-sm-2">Tanggal Akhir</label>
                        <div class="col-sm-4">
                            <input class="form-control form-control js-datepicker digits" name="tgl_akhir" type="text"
                                placeholder="Tanggal Akhir..." data-language="en" required>
                        </div>

                        {{-- ATTACH FILE --}}
                        <label class="col-sm-2 col-form-label">Dokumen</label>
                        <div class="col-sm-10">

                            <!-- Button Attach -->
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach"
                                id="btn-attach">
                                <i class="fa fa-paperclip"></i> Attach File
                            </button>

                            <!-- Hidden Input -->
                            <input type="file" id="upload" name="upload" multiple accept="application/pdf"
                                class="d-none">

                            <small class="text-muted btn-attach">
                                Maksimal 1 file (PDF)
                            </small>

                            {{-- PREVIEW --}}
                            <div class="row mt-2" id="preview-images"></div>
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

    <!-- Modal lihat foto -->
    <div class="modal fade" id="modal-preview-pdf" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('legal.perizinan.script')
@endsection
