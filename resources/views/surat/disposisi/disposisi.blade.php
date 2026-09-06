@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .lampiran-thumb-wrap {
            position: relative;
            display: inline-block;
        }

        .lampiran-thumb-wrap img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .isi-surat-readonly {
            white-space: pre-line;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px;
            border: 1px solid #eee;
        }

        .catatan-pengirim-box {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 6px;
            padding: 10px 12px;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Disposisi Surat</h3>
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

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_disposisi" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">No Surat</th>
                                            <th class="f-light">Tanggal</th>
                                            <th class="f-light">Perihal</th>
                                            <th class="f-light">Dari</th>
                                            <th class="f-light">Tanggal Disposisi</th>
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

    {{-- Modal Detail Disposisi (read-only) --}}
    <div class="modal fade" id="modal-detail-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Disposisi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th width="150">Tanggal</th>
                            <td>:</td>
                            <td class="detail-disp-tanggal"></td>
                        </tr>
                        <tr>
                            <th>No Surat</th>
                            <td>:</td>
                            <td class="detail-disp-no-surat"></td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td>:</td>
                            <td class="detail-disp-perihal"></td>
                        </tr>
                        <tr>
                            <th>Dari</th>
                            <td>:</td>
                            <td class="detail-disp-pengirim"></td>
                        </tr>
                    </table>

                    <label class="fw-bold mb-2">Catatan / Instruksi</label>
                    <div class="catatan-pengirim-box mb-3 detail-disp-catatan"></div>

                    <label class="fw-bold mb-2">Isi Surat</label>
                    <div class="isi-surat-readonly mb-3 detail-disp-isi-surat"></div>

                    <label class="fw-bold mb-2">Lampiran</label>
                    <div class="d-flex flex-wrap gap-2 detail-disp-lampiran"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tandai Selesai --}}
    <div class="modal fade" id="modal-selesai-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tandai Sudah Ditindaklanjuti</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-2 form-selesai-disposisi" autocomplete="off">
                        <input type="hidden" name="id_disposisi">

                        <label for="catatan_tindak_lanjut" class="col-form-label col-sm-12">
                            Catatan Tindak Lanjut (opsional)
                        </label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="catatan_tindak_lanjut" id="catatan_tindak_lanjut"
                                rows="4" placeholder="Contoh: sudah dikoordinasikan dengan tim terkait..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success btn-submit-selesai-disposisi" type="button">
                        <span class="fa fa-check"></span> Tandai Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal lihat foto (besar) --}}
    <div class="modal fade" id="modal-preview-image-disposisi" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="preview-large-disposisi" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('surat.disposisi.script')
@endsection
