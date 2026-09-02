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

        .badge-jabatan {
            font-size: 11px;
        }

        .isi-surat-readonly {
            white-space: pre-line;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px;
            border: 1px solid #eee;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Approval Memorandum</h3>
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
                                <table id="table_aproval_memo" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">No Surat</th>
                                            <th class="f-light">Tanggal</th>
                                            <th class="f-light">Perihal</th>
                                            <th class="f-light">Pembuat</th>
                                            <th class="f-light">Level Approval Saya</th>
                                            <th class="f-light">Status Surat</th>
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

    {{-- Modal Detail Surat (read-only) --}}
    <div class="modal fade" id="modal-detail-memo" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Surat</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th width="150">Tanggal</th>
                            <td>:</td>
                            <td class="detail-memo-tanggal"></td>
                        </tr>
                        <tr>
                            <th>No Surat</th>
                            <td>:</td>
                            <td class="detail-memo-no-surat"></td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td>:</td>
                            <td class="detail-memo-perihal"></td>
                        </tr>
                        <tr>
                            <th>Pembuat</th>
                            <td>:</td>
                            <td class="detail-memo-pembuat"></td>
                        </tr>
                    </table>

                    <label class="fw-bold mb-2">Isi Surat</label>
                    <div class="isi-surat-readonly mb-3 detail-memo-isi-surat"></div>

                    <label class="fw-bold mb-2">Lampiran</label>
                    <div class="d-flex flex-wrap gap-2 detail-memo-lampiran"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Approve / Tolak --}}
    <div class="modal fade" id="modal-keputusan-memo" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keputusan Approval</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-2 form-keputusan-memo" autocomplete="off">
                        <input type="hidden" name="id_aproval_surat">

                        <label class="col-form-label col-sm-12">
                            No Surat: <span class="fw-bold keputusan-memo-no-surat"></span><br>
                            Perihal: <span class="keputusan-memo-perihal"></span>
                        </label>

                        <label for="keterangan_keputusan" class="col-form-label col-sm-12">
                            Keterangan / Catatan Revisi
                        </label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="keterangan" id="keterangan_keputusan" rows="4"
                                placeholder="Wajib diisi kalau menolak, opsional kalau menyetujui..."></textarea>
                            <small class="text-danger d-none keterangan-wajib-warning">
                                Keterangan wajib diisi untuk menolak surat.
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger btn-tolak-memo" type="button">
                        <span class="fa fa-times"></span> Tolak
                    </button>
                    <button class="btn btn-success btn-approve-memo" type="button">
                        <span class="fa fa-check"></span> Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal lihat foto (besar) --}}
    <div class="modal fade" id="modal-preview-image-memo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="preview-large-memo" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('surat.aproval-memorandum.script')
@endsection