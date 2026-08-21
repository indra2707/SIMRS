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
            /* kunci lebar */
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
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Surat</h3>
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
                                            <th class="f-light">Perihal</th>
                                            <th class="f-light">Approval</th>
                                            <th class="f-light">Lampiran</th>
                                            <th class="f-light">Dibuat</th>
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
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
                        <label for="tanggal" class="col-form-label col-sm-2">Tanggal</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required />
                        </div>

                        <!-- No Surat -->
                        <label for="no_surat" class="col-form-label col-sm-2">No Surat</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="no_surat" id="no_surat" class="form-control" required
                                    placeholder="Nomor surat..." readonly />
                                <button class="btn btn-outline-secondary btn-generate-no" type="button"
                                    title="Generate nomor otomatis berdasarkan tanggal">
                                    <span class="fa fa-magic"></span>
                                </button>
                            </div>
                            <small class="text-muted">Klik ikon untuk generate otomatis setelah memilih tanggal.</small>
                        </div>

                        <!-- Perihal -->
                        <label for="perihal" class="col-form-label col-sm-2">Perihal</label>
                        <div class="col-sm-10">
                            <input type="text" name="perihal" id="perihal" class="form-control" required
                                placeholder="Perihal surat..." maxlength="255" />
                        </div>

                        <!-- Approval -->
                        <label for="approval_id" class="col-form-label col-sm-2">Approval</label>
                        <div class="col-sm-10">
                            <select id="approval_id" class="form-select select2" name="approval_id"
                                data-placeholder="---- Pilih Salah Satu (opsional) ----">
                                <option></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lampiran Lama (sudah tersimpan, khusus mode edit) -->
                        <label class="col-form-label col-sm-2 lampiran-lama-label" style="display:none;">Lampiran Saat Ini</label>
                        <div class="col-sm-10 lampiran-lama-wrap" style="display:none;">
                            <div class="d-flex flex-wrap gap-2 lampiran-current"></div>
                            <small class="text-muted">Klik &times; merah untuk menandai lampiran yang mau dihapus.</small>
                        </div>

                        <!-- Lampiran Baru -->
                        <label class="col-form-label col-sm-2">Tambah Lampiran</label>
                        <div class="col-sm-10">

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

                            {{-- PREVIEW file baru --}}
                            <div class="row mt-2" id="preview-images-surat"></div>
                        </div>

                        <!-- Isi Surat -->
                        <label for="isi_surat" class="col-form-label col-sm-2">Isi Surat</label>
                        <div class="col-sm-10">
                            <textarea name="isi_surat" id="isi_surat" class="form-control" rows="6" required
                                placeholder="Isi surat..."></textarea>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Surat</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Tanggal</th>
                            <td>:</td>
                            <td class="detail-tanggal"></td>
                        </tr>
                        <tr>
                            <th>No Surat</th>
                            <td>:</td>
                            <td class="detail-no-surat"></td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td>:</td>
                            <td class="detail-perihal"></td>
                        </tr>
                        <tr>
                            <th>Approval</th>
                            <td>:</td>
                            <td class="detail-approval"></td>
                        </tr>
                        <tr>
                            <th>Lampiran</th>
                            <td>:</td>
                            <td class="detail-lampiran"></td>
                        </tr>
                        <tr>
                            <th>Isi Surat</th>
                            <td>:</td>
                            <td class="detail-isi-surat"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat semua lampiran (grid thumbnail) -->
    <div class="modal fade" id="modal-lampiran-surat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran Surat</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-2 lampiran-view-list"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
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

@endsection


@section('script')
    @include('surat.list-surat.script')
@endsection