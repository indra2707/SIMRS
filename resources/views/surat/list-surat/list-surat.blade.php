@extends('layouts.simple.master')
@section('title', $data['title'])

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
            min-width: 220px;
            max-width: 220px;
        }

        .lampiran-preview {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

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

        .lampiran-thumb-remove {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
            cursor: pointer;
            border: none;
        }

        .lampiran-thumb-wrap.marked-remove img {
            opacity: 0.3;
        }

        .ck-editor__editable {
            height: 500px;
            max-height: 500px;
            overflow-y: auto;
        }

        /* Tabel CKEditor agar tidak terlalu tinggi */
        .ck-editor__editable table {
            height: auto !important;
            min-height: 0 !important;
        }

        .ck-editor__editable table td,
        .ck-editor__editable table th {
            height: auto !important;
            min-height: 0 !important;
            padding: 4px 6px !important;
            line-height: 1.2 !important;
            vertical-align: top;
        }

        .ck-editor__editable table tr {
            height: auto !important;
        }


        approval-wizard-wrapper {
            width: 100%;
            overflow-x: auto;
            padding: 30px 10px 20px;
        }

        .approval-wizard {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-width: max-content;
            padding: 0 20px;
        }

        .approval-step {
            position: relative;
            width: 180px;
            text-align: center;
        }

        /* Garis penghubung */
        .approval-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 23px;
            left: calc(50% + 23px);
            width: calc(100% - 46px);
            height: 2px;
            background: #d5d5d5;
            z-index: 1;
        }

        .approval-step.approved:not(:last-child)::after {
            background: #4053c4;
        }

        /* Lingkaran icon */
        .approval-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #d5d5d5;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 12px;

            position: relative;
            z-index: 2;

            color: #999;
            font-size: 18px;
        }

        /* Approved */
        .approval-step.approved .approval-icon {
            background: #4053c4;
            border-color: #4053c4;
            color: #ffffff;
        }

        /* Pending */
        .approval-step.pending .approval-icon {
            background: #ffffff;
            border-color: #4053c4;
            color: #4053c4;
        }

        .approval-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .approval-name {
            font-size: 13px;
            color: #777;
            margin-bottom: 5px;
        }

        .approval-status {
            font-size: 12px;
            font-weight: 600;
        }

        .approval-step.approved .approval-status {
            color: #4053c4;
        }

        .approval-step.pending .approval-status {
            color: #999;
        }

        .approval-date {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .approval-step {
                width: 160px;
            }

            .approval-wizard {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Memorandum</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ $data['menuTitle'] }}</li>
    <li class="breadcrumb-item active">{{ $data['menuSubtitle'] }}</li>
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
                                <table id="table_surat" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">Tanggal</th>
                                            <th class="f-light">No Surat</th>
                                            <th class="f-light">Kepada</th>
                                            <th class="f-light">Perihal</th>
                                            <th class="f-light">Status</th>
                                            <th class="f-light">Created At</th>
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

    {{-- Modal Form Surat --}}
    <div class="modal fade" id="modal-surat" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-surat" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <!-- Tanggal -->
                        <label for="tanggal" class="col-form-label col-sm-1">Tanggal</label>
                        <div class="col-sm-5">
                            <input type="text" name="tanggal" id="tanggal" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- Nomor -->
                        <label for="no_surat" class="col-form-label col-sm-1">Nomor Surat</label>
                        <div class="col-sm-5">
                            <input type="text" name="no_surat" id="no_surat" class="form-control" readonly
                                placeholder="Nomor surat..." />
                        </div>

                        <!-- Kepada -->
                        <label for="kepada" class="col-form-label col-sm-1">Kepada</label>
                        <div class="col-sm-5">
                            <select id="approval" class="form-select select2" name="approval_id"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- Lampiran -->
                        <label for="jumlah_lampiran" class="col-form-label col-sm-1">Lampiran</label>
                        <div class="col-sm-5">
                            <input type="text" name="jumlah_lampiran" id="jumlah_lampiran" class="form-control"
                                placeholder="Jumlah Lampiran..." required />
                        </div>

                        <!-- Perihal -->
                        <label for="perihal" class="col-form-label col-sm-1">Perihal</label>
                        <div class="col-sm-11">
                            <input type="text" name="perihal" id="perihal" class="form-control" required
                                placeholder="Perihal surat..." maxlength="255" />
                        </div>

                        <!-- Isi Surat -->
                        <label for="isi_surat" class="col-form-label col-sm-1">Isi Surat</label>
                        <div class="col-sm-11">
                            <textarea name="isi_surat" id="editable" class="form-control"></textarea>
                        </div>

                        <!-- Upload Lampiran -->
                        <label class="col-form-label col-sm-1">Upload Lampiran</label>
                        <div class="col-sm-11">

                            <!-- Button Attach -->
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="btn-attach-surat">
                                <i class="fa fa-paperclip"></i> Attach File
                            </button>

                            <!-- Hidden Input -->
                            <input type="file" id="lampiran" name="lampiran[]" multiple
                                accept="image/jpeg,image/png,image/webp" class="d-none">

                            <small class="text-muted d-block">
                                Maksimal 5 file baru sekaligus, JPG/PNG/WEBP, maks 5 MB per file.
                            </small>
                            <div class="row mt-2" id="preview-images-surat"></div>
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

    {{-- Modal Detail Surat --}}
    <div class="modal fade" id="modal-detail-surat" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title-surat"> Status Surat </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <div class="approval-wizard-wrapper">
                                <div id="approvalWizard" class="approval-wizard">
                                    <!-- Wizard akan diisi melalui AJAX -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Tutup </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat foto (besar) -->
    <div class="modal fade" id="modal-preview-image-surat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="preview-large-surat" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>



    {{-- Modal Buat Disposisi --}}

<div class="modal fade" id="modal-buat-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Disposisi</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-2 form-buat-disposisi" autocomplete="off">
                    <input type="hidden" name="id_surat">
                    <input type="hidden" name="id_aproval">

                    <label class="col-form-label col-sm-12">
                        No Surat: <span class="fw-bold disposisi-no-surat"></span> &mdash;
                        Perihal: <span class="disposisi-perihal"></span>
                    </label>

                    <!-- No Agenda -->
                    <label for="no_agenda" class="col-form-label col-sm-2">No. Agenda</label>
                    <div class="col-sm-4">
                        <input type="text" name="no_agenda" id="no_agenda" class="form-control"
                            placeholder="Opsional..." />
                    </div>

                    <!-- Tingkat Surat -->
                    <label class="col-form-label col-sm-2">Tingkat Surat</label>
                    <div class="col-sm-6">
                        <div class="d-flex gap-3 pt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_surat" id="tingkat_r"
                                    value="R">
                                <label class="form-check-label" for="tingkat_r">R - Rahasia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_surat" id="tingkat_p"
                                    value="P">
                                <label class="form-check-label" for="tingkat_p">P - Penting</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_surat" id="tingkat_s"
                                    value="S">
                                <label class="form-check-label" for="tingkat_s">S - Segera</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_surat" id="tingkat_b"
                                    value="B" checked>
                                <label class="form-check-label" for="tingkat_b">B - Biasa</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Jabatan Penerima -->
                    <label class="col-form-label col-sm-12 mt-2">Diteruskan Kepada</label>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle" id="tabel-jabatan-disposisi">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Jabatan</th>
                                        <th width="70">Action</th>
                                        <th width="90">Tanggapan</th>
                                        <th width="60">Info</th>
                                        <th width="60">File</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-jabatan-disposisi">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Memuat daftar jabatan...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">
                            Centang minimal 1 jenis tindakan untuk jabatan yang ingin disertakan.
                            Jabatan tanpa centang tidak akan menerima disposisi ini.
                        </small>
                    </div>

                    <!-- Catatan / NOTE -->
                    <label for="catatan_disposisi" class="col-form-label col-sm-12 mt-2">
                        Catatan / Instruksi (NOTE)
                    </label>
                    <div class="col-sm-12">
                        <textarea class="form-control" name="catatan" id="catatan_disposisi" rows="3"
                            placeholder="Contoh: Mohon ditindaklanjuti sesuai ketentuan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary btn-submit-disposisi" type="button">
                    <span class="fa fa-share"></span> Kirim Disposisi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    @include('surat.list-surat.script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@endsection