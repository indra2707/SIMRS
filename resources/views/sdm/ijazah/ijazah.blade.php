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
    <h3>Ijazah</h3>
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
                                <button class="btn btn-primary add-btn-ijazah">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah Ijazah</span>
                                </button>
                            </div>
                        </div>

                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_ijazah" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Pekerja</th>
                                            <th class="f-light">Nomor Ijazah</th>
                                            <th class="f-light">Pendidikan</th>
                                            <th class="f-light">Institusi</th>
                                            <th class="f-light">Prodi</th>
                                            <th class="f-light">Tahun Lulus</th>
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
    <div class="modal fade" id="modal-ijazah" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-wizard form-ijazah" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Id_ijazah --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_ijazah">
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

                        <!-- Nomor Ijazah -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_ijazah">Nomor Ijazah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_ijazah" name="nomor_ijazah"
                                    placeholder="Nomor Ijazah..." required>
                            </div>
                        </div>

                        <!-- Institusi -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="institusi">Institusi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="institusi" name="institusi"
                                    placeholder="Nama Institusi..." required>
                            </div>
                        </div>

                        <!-- Pendidikan -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="pendidikan">Pendidikan</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="pendidikan" name="pendidikan" required>
                                    <option value=""></option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    <option value="Profesi">Profesi</option>
                                    <option value="Spesialis">Spesialis</option>
                                    <option value="Subspesialis">Subspesialis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Prodi -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="prodi">Prodi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="prodi" name="prodi"
                                    placeholder="Program Studi..." required>
                            </div>
                        </div>

                        <!-- Tahun Lulus -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tahun_lulus">Tahun Lulus</label>
                            <div class="col-sm-10">
                                <input type="text" name="tahun_lulus" id="tahun_lulus"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Lulus" data-language="en" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach"
                                    id="btn-attach"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran" name="lampiran" accept="application/pdf" class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                        <br> upload ijazah dan transkrip nilai menjadi satu file
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-ijazah" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat foto -->
    <div class="modal fade" id="modal-preview-pdf" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
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
    @include('sdm.ijazah.script')
@endsection