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

        .badge-tingkat {
            font-size: 11px;
        }

        .badge-checklist {
            font-size: 10px;
            margin-right: 2px;
        }

        .isi-surat-readonly {
            white-space: pre-line;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px;
            border: 1px solid #eee;
        }

        .tabel-tujuan-disposisi th,
        .tabel-tujuan-disposisi td {
            vertical-align: middle;
            text-align: center;
        }

        .tabel-tujuan-disposisi td.nama-tujuan-col {
            text-align: left;
        }

        .lembar-kosong-warning {
            display: none;
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

                        <ul class="nav nav-tabs mb-3" id="tab-disposisi" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-surat-siap-btn" data-bs-toggle="tab"
                                    data-bs-target="#tab-surat-siap" type="button" role="tab">
                                    Surat Siap Didisposisikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-disposisi-masuk-btn" data-bs-toggle="tab"
                                    data-bs-target="#tab-disposisi-masuk" type="button" role="tab">
                                    Disposisi Masuk
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="tab-disposisi-content">

                            {{-- TAB 1: Surat Siap Didisposisikan --}}
                            <div class="tab-pane fade show active" id="tab-surat-siap" role="tabpanel">
                                <div class="table-responsive signal-table">
                                    <table id="table_surat_siap" class="table table-hover"
                                        data-buttons-class="primary" data-toggle="table">
                                        <thead class="text-bold text-white text-uppercase text-center">
                                            <tr>
                                                <th class="f-light">#</th>
                                                <th class="f-light">No Surat</th>
                                                <th class="f-light">Tanggal</th>
                                                <th class="f-light">Perihal</th>
                                                <th class="f-light">Pembuat</th>
                                                <th class="f-light">Status Surat</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            {{-- TAB 2: Disposisi Masuk --}}
                            <div class="tab-pane fade" id="tab-disposisi-masuk" role="tabpanel">
                                <div class="table-responsive signal-table">
                                    <table id="table_disposisi_masuk" class="table table-hover"
                                        data-buttons-class="primary" data-toggle="table">
                                        <thead class="text-bold text-white text-uppercase text-center">
                                            <tr>
                                                <th class="f-light">#</th>
                                                <th class="f-light">No Surat</th>
                                                <th class="f-light">Perihal</th>
                                                <th class="f-light">Dari</th>
                                                <th class="f-light">Tingkat</th>
                                                <th class="f-light">Checklist</th>
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
    </div>

    {{-- Modal Detail Surat (read-only, dari tab Surat Siap Didisposisikan) --}}
    <div class="modal fade" id="modal-detail-surat-disposisi" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
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
                            <td class="detail-surat-tanggal"></td>
                        </tr>
                        <tr>
                            <th>No Surat</th>
                            <td>:</td>
                            <td class="detail-surat-no-surat"></td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td>:</td>
                            <td class="detail-surat-perihal"></td>
                        </tr>
                        <tr>
                            <th>Pembuat</th>
                            <td>:</td>
                            <td class="detail-surat-pembuat"></td>
                        </tr>
                    </table>

                    <label class="fw-bold mb-2">Isi Surat</label>
                    <div class="isi-surat-readonly mb-3 detail-surat-isi-surat"></div>

                    <label class="fw-bold mb-2">Lampiran</label>
                    <div class="d-flex flex-wrap gap-2 detail-surat-lampiran"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Buat / Teruskan Disposisi --}}
    <div class="modal fade" id="modal-buat-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title judul-modal-disposisi">Buat Disposisi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-2 form-buat-disposisi" autocomplete="off">
                        <input type="hidden" name="id_surat">
                        <input type="hidden" name="id_parent" value="">

                        <label class="col-form-label col-sm-12 mb-2">
                            No Surat: <span class="fw-bold disposisi-form-no-surat"></span><br>
                            Perihal: <span class="disposisi-form-perihal"></span><br>
                            Lembar: <span class="badge bg-primary disposisi-form-nama-master">-</span>
                        </label>

                        <div class="alert alert-warning lembar-kosong-warning" role="alert">
                            Anda belum memiliki lembar disposisi (template tujuan). Silakan hubungi admin untuk
                            mengaturnya terlebih dahulu.
                        </div>

                        <div class="col-sm-6">
                            <label class="col-form-label">Tingkat Surat</label><br>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="tingkat_surat" id="tingkat_b" value="B"
                                    checked>
                                <label class="btn btn-outline-secondary btn-sm" for="tingkat_b">Biasa</label>

                                <input type="radio" class="btn-check" name="tingkat_surat" id="tingkat_s" value="S">
                                <label class="btn btn-outline-primary btn-sm" for="tingkat_s">Segera</label>

                                <input type="radio" class="btn-check" name="tingkat_surat" id="tingkat_p" value="P">
                                <label class="btn btn-outline-warning btn-sm" for="tingkat_p">Penting</label>

                                <input type="radio" class="btn-check" name="tingkat_surat" id="tingkat_r" value="R">
                                <label class="btn btn-outline-danger btn-sm" for="tingkat_r">Rahasia</label>
                            </div>
                        </div>

                        <div class="col-sm-6 text-sm-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-pilih-semua-tujuan">
                                Pilih Semua Tujuan
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-batal-pilih-tujuan">
                                Batal Pilih Semua
                            </button>
                        </div>

                        <div class="col-sm-12 mt-2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm tabel-tujuan-disposisi">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">#</th>
                                            <th class="nama-tujuan-col">Diteruskan Kepada</th>
                                            <th width="60">Pilih</th>
                                            <th width="70">Action</th>
                                            <th width="90">Tanggapan</th>
                                            <th width="60">Info</th>
                                            <th width="60">File</th>
                                        </tr>
                                    </thead>
                                    <tbody class="baris-tujuan-disposisi">
                                        {{-- diisi via JS dari templateTujuan() --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <label for="catatan_disposisi" class="col-form-label col-sm-12">
                            Catatan / Note
                        </label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="catatan" id="catatan_disposisi" rows="3"
                                placeholder="Opsional..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success btn-simpan-disposisi" type="button">
                        <span class="fa fa-paper-plane"></span> <span class="teks-btn-simpan-disposisi">Kirim
                            Disposisi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Disposisi Masuk --}}
    <div class="modal fade" id="modal-detail-disposisi-masuk" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Disposisi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="detail-masuk-id-disposisi">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th width="150">No Surat</th>
                            <td>:</td>
                            <td class="detail-masuk-no-surat"></td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td>:</td>
                            <td class="detail-masuk-perihal"></td>
                        </tr>
                        <tr>
                            <th>Dari</th>
                            <td>:</td>
                            <td class="detail-masuk-pengirim"></td>
                        </tr>
                        <tr>
                            <th>Tingkat Surat</th>
                            <td>:</td>
                            <td class="detail-masuk-tingkat"></td>
                        </tr>
                        <tr>
                            <th>Checklist</th>
                            <td>:</td>
                            <td class="detail-masuk-checklist"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td class="detail-masuk-status"></td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>:</td>
                            <td class="detail-masuk-catatan"></td>
                        </tr>
                    </table>

                    <label class="fw-bold mb-2">Lampiran</label>
                    <div class="d-flex flex-wrap gap-2 detail-masuk-lampiran"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-outline-success btn-teruskan-dari-detail" type="button">
                        <span class="fa fa-share"></span> Teruskan ke Bawahan
                    </button>
                    <div>
                        <button class="btn btn-primary btn-tandai-selesai-masuk" type="button">
                            <span class="fa fa-check-circle"></span> Tandai Selesai
                        </button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                    </div>
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
