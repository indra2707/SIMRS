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
    <h3>Hasil MCU</h3>
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
                                <button class="btn btn-primary add-btn-mcu">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah Hasil MCU</span>
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_mcu" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Pekerja</th>
                                            <th class="f-light">Tanggal MCU</th>
                                            <th class="f-light">Hasil MCU</th>
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
    <div class="modal fade" id="modal-mcu" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-wizard form-mcu" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_mcu">
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

                        <!-- Tanggal MCU -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mcu">Tanggal</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_mcu" id="tanggal_mcu"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Hasil MCU -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="hasil_mcu">Hasil</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="hasil_mcu" name="hasil_mcu" required>
                                    <option value=""></option>
                                    <option value="P1">P1 - TIDAK DITEMUKAN KELAINAN MEDIS</option>
                                    <option value="P2">P2 - DITEMUKAN KELAINAN MEDIS YANG TIDAK SERIUS</option>
                                    <option value="P3">P3 - DITEMUKAN KELAINAN MEDIS, RESIKO KESEHATAN RENDAH</option>
                                    <option value="P4">P4 - DITEMUKAN KELAINAN MEDIS BERMAKNA YANG DAPAT MENJADI SERIUS,
                                        RESIKO KESEHATAN SEDANG</option>
                                    <option value="P5">P5 - DITEMUKAN KELAINAN MEDIS YANG SERIUS, RESIKO KESEHATAN TINGGI
                                    </option>
                                    <option value="P6">P6 - DITEMUKAN KELAINAN MEDIS YANG MENYEBABKAN KETERBATASAN FISIK
                                        ATAU PSIKIS UNTUK MELAKUKAN PEKERJAAN SESUAI DENGAN JABATAN ATAU FUNGSINYA</option>
                                    <option value="P7">P7 - TIDAK DAPAT BEKERJA UNTUK MELAKUKAN PEKERJAAN SESUAI DENGAN
                                        JABATAN/ POSISINYA DAN ATAU POSISI APAPUN, SEDANG DALAM PERAWATAN DI RUMAH SAKIT,
                                        ATAU DENGAN STATUS IZIN SAKIT</option>
                                </select>
                            </div>
                        </div>

                        <!-- catatan -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="catatan_mcu">Catatan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="catatan_mcu" name="catatan_mcu" rows="3" required
                                    placeholder="Catatan..."></textarea>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-mcu"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-mcu"
                                    id="btn-attach-mcu"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-mcu" name="lampiran-mcu" accept="application/pdf"
                                    class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-mcu">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-mcu" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Hasil MCU -->
    <div class="modal fade" id="modal-preview-pdf-mcu" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-mcu" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('sdm.mcu.script')
@endsection