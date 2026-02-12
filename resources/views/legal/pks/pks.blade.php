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
    </style>

@endsection

@section('breadcrumb-title')
    <h3>PKS</h3>
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
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-primary add-btn">
                                <span class="fa fa-plus"></span>
                                <span> Tambah PKS</span>
                            </button>

                            <div class="bs-bars">
                                <input type="text" class="form-control js-daterangepicker text-center" style="width:220px"
                                    placeholder="dd/mm/yyyy - dd/mm/yyyy" data-language="en">
                            </div>
                        </div>
                        <input type="hidden" name="tgl_awal" id="tgl_awal">
                        <input type="hidden" name="tgl_akhir" id="tgl_akhir">


                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_helpdesk" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Tiket</th>
                                            <th class="f-light">Judul PKS</th>
                                            <th class="f-light">Jenis Kontrak</th>
                                            <th class="f-light">Pihak</th>
                                            <th class="f-light">Tanggal mulai</th>
                                            <th class="f-light">Tanggal selesai</th>
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
    <div class="modal fade" id="modal-pks" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-pks" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <!-- Nomor PKS  -->
                        <label for="nomor_pks" class="col-form-label col-sm-2">Nomor PKS</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="nomor_pks" type="text" placeholder="Nomor PKS..."
                                required>
                        </div>

                        <!-- Judul  -->
                        <label for="judul_laporan" class="col-form-label col-sm-2">Judul</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="judul" type="text" placeholder="Judul..."
                                required>
                        </div>

                        <!-- Jenis Kontrak  -->
                        <label for="jenis_kontrak" class="col-form-label col-sm-2">Jenis Kontrak</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control" id="jenis_kontrak" name="jenis_kontrak"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Pihak  -->
                        <label for="pihak" class="col-form-label col-sm-2">Pihak</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="pihak" type="text" placeholder="Pihak..."
                                required>
                        </div>

                        {{-- ATTACH FILE --}}
                        <label class="col-sm-2 col-form-label">Dokumen</label>
                        <div class="col-sm-10">

                            <!-- Button Attach -->
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach" id="btn-attach">
                                <i class="fa fa-paperclip"></i> Attach File
                            </button>

                            <!-- Hidden Input -->
                            <input type="file" id="lampiran" name="lampiran" multiple accept="application/pdf"
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
    <div class="modal fade" id="modal-preview-image" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="preview-large" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('legal.pks.script')
@endsection