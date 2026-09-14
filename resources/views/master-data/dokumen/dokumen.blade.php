@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>

@endsection

@section('breadcrumb-title')
    <h3>Dokumen</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ $menuTitle }}</li>
    <li class="breadcrumb-item active">{{ $menuSubtitle }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-sm-12">
                    <form class="form-account" id="form-profile" novalidate autocomplete="off">
                        <div class="card">
                            <div class="card-body pb-0">
                                <ul class="nav nav-tabs" id="ijazahTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="ijazah-tab" data-bs-toggle="tab"
                                            data-bs-target="#ijazah" type="button" role="tab" aria-controls="ijazah"
                                            aria-selected="true">
                                            <i class="fe fe-user me-1"></i>
                                            Ijazah
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="kontrak-tab" data-bs-toggle="tab"
                                            data-bs-target="#kontrak" type="button" role="tab" aria-controls="kontrak"
                                            aria-selected="false">
                                            <i class="fe fe-phone me-1"></i>
                                            Kontrak Kerja
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="jabatan-tab" data-bs-toggle="tab"
                                            data-bs-target="#jabatan" type="button" role="tab" aria-controls="jabatan"
                                            aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            SK Jabatan
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="str-tab" data-bs-toggle="tab" data-bs-target="#str"
                                            type="button" role="tab" aria-controls="str" aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            STR dan SIP
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="spk-tab" data-bs-toggle="tab" data-bs-target="#spk"
                                            type="button" role="tab" aria-controls="spk" aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            SPK dan RKK
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="sertifikat-tab" data-bs-toggle="tab"
                                            data-bs-target="#sertifikat" type="button" role="tab" aria-controls="sertifikat"
                                            aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            Sertifikat
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="mcu-tab" data-bs-toggle="tab" data-bs-target="#mcu"
                                            type="button" role="tab" aria-controls="mcu" aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            Hasil MCU
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="dokumen-lainnya-tab" data-bs-toggle="tab"
                                            data-bs-target="#dokumen-lainnya" type="button" role="tab"
                                            aria-controls="dokumen-lainnya" aria-selected="false">
                                            <i class="fe fe-map-pin me-1"></i>
                                            Dokumen Lainnya
                                        </button>
                                    </li>
                                </ul>
                            </div>


                            <div class="card-body pt-3">
                                <div class="tab-content" id="ijazahTabContent">
                                    <!-- Ijazah -->
                                    <div class="tab-pane fade show active" id="ijazah" role="tabpanel"
                                        aria-labelledby="ijazah-tab"> <input type="hidden" id="id" name="id">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Ijazah </h5> <small class="text-muted">
                                                            Daftar riwayat pendidikan dan ijazah pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-ijazah">
                                                        <i class="fa fa-plus me-1"></i> Tambah Ijazah
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_ijazah" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
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

                                    <!-- Kontrak -->
                                    <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Kontrak </h5> <small class="text-muted">
                                                            Daftar riwayat kontrak pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-kontrak">
                                                        <i class="fa fa-plus me-1"></i> Tambah Kontrak
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_kontrak" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
                                                                <th class="f-light">ID</th>
                                                                <th class="f-light">Nomor Kontrak</th>
                                                                <th class="f-light">Status</th>
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

                                    <!-- SK Jabatan -->
                                    <div class="tab-pane fade" id="jabatan" role="tabpanel" aria-labelledby="jabatan-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Jabatan </h5> <small class="text-muted">
                                                            Daftar riwayat Jabatan pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-jabatan">
                                                        <i class="fa fa-plus me-1"></i> Tambah Jabatan
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_jabatan" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
                                                                <th class="f-light">ID</th>
                                                                <th class="f-light">Nomor</th>
                                                                <th class="f-light">Nama Jabatan</th>
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


                                    <!-- STR dan SIP -->
                                    <div class="tab-pane fade" id="str" role="tabpanel" aria-labelledby="str-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data STR dan SIP </h5> <small class="text-muted">
                                                            Daftar riwayat STR dan SIP pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-str">
                                                        <i class="fa fa-plus me-1"></i> Tambah STR dan SIP
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_str" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
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

                                    <!-- SPK dan RKK -->
                                    <div class="tab-pane fade" id="spk" role="tabpanel" aria-labelledby="spk-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data SPK dan RKK </h5> <small class="text-muted">
                                                            Daftar riwayat SPK dan RKK pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-spk">
                                                        <i class="fa fa-plus me-1"></i> Tambah SPK dan RKK
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_spk" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
                                                                <th class="f-light">Nomor SPK</th>
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


                                    <!-- Sertifikat -->
                                    <div class="tab-pane fade" id="sertifikat" role="tabpanel"
                                        aria-labelledby="sertifikat-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Sertifikat </h5> <small class="text-muted">
                                                            Daftar riwayat Sertifikat pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-sertifikat">
                                                        <i class="fa fa-plus me-1"></i> Tambah Sertifikat
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_sertifikat" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
                                                                <th class="f-light">Jenis</th>
                                                                <th class="f-light">Nama</th>
                                                                <th class="f-light">Penyelenggara</th>
                                                                <th class="f-light">Tahun</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hasil MCU -->
                                    <div class="tab-pane fade" id="mcu" role="tabpanel" aria-labelledby="mcu-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Hasil MCU </h5> <small class="text-muted">
                                                            Daftar riwayat Hasil MCU pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-mcu">
                                                        <i class="fa fa-plus me-1"></i> Tambah Hasil MCU
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_mcu" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
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


                                    <!-- Dokumen Lainnya -->
                                    <div class="tab-pane fade" id="dokumen-lainnya" role="tabpanel"
                                        aria-labelledby="dokumen-lainnya-tab">
                                        <div class="row">
                                            <!-- Header / Button -->
                                            <div class="col-12 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-0"> Data Dokumen Lainnya </h5> <small
                                                            class="text-muted">
                                                            Daftar riwayat Dokumen Lainnya pegawai </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add-btn-dokumen-lainnya">
                                                        <i class="fa fa-plus me-1"></i> Tambah Dokumen Lainnya
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Table -->
                                            <div class="col-sm-12">
                                                <div class="table-responsive signal-table">
                                                    <table id="table_dokumen_lainnya" class="table table-hover"
                                                        data-buttons-class="primary" data-toggle="table">
                                                        <thead class="text-bold text-white text-uppercase text-center">
                                                            <tr>
                                                                <th class="f-light">No</th>
                                                                <th class="f-light">Jenis</th>
                                                                <th class="f-light">Nomor</th>
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
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Form Ijazah --}}
    <div class="modal fade" id="modal-ijazah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-ijazah" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Ijazah -->
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


    {{-- Modal Form Kontrak --}}
    <div class="modal fade" id="modal-kontrak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-kontrak" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_kontrak">
                        </div>

                        <!-- Nomor Kontrak -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_kontrak">Nomor Kontrak</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_kontrak" name="nomor_kontrak"
                                    placeholder="Nomor Kontrak..." required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="status" name="status" required>
                                    <option value=""></option>
                                    <option value="PWTT">PWTT</option>
                                    <option value="PWT">PWT</option>
                                    <option value="Mitra Pegawai">Mitra Pegawai</option>
                                    <option value="Mitra Dokter">Mitra Dokter</option>
                                    <option value="Mitra Onsite">Mitra Onsite</option>
                                    <option value="Outsourcing">Outsourcing</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Mulai" data-language="en" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Seumur Hidup -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="masa_berlaku">Masa Berlaku</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="masa_berlaku" id="masa_berlaku"
                                        value="1">
                                    <label class="form-check-label" for="masa_berlaku">Seumur Hidup</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_berakhir">Tanggal Berakhir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_berakhir" id="tanggal_berakhir"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-kontrak"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-kontrak"
                                    id="btn-attach-kontrak"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-kontrak" name="lampiran-kontrak" accept="application/pdf"
                                    class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-kontrak">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-kontrak" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen kontrak -->
    <div class="modal fade" id="modal-preview-pdf-kontrak" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-kontrak" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Form SK Jabatan --}}
    <div class="modal fade" id="modal-jabatan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-jabatan" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_jabatan">
                        </div>

                        <!-- Nomor Jabatan -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_sk">Nomor SK Jabatan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_sk" name="nomor_sk"
                                    placeholder="Nomor SK Jabatan..." required>
                            </div>
                        </div>

                        <!-- Nama Jabatan -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama_jabatan">Nama Jabatan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan"
                                    placeholder="Nama Jabatan..." required>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_mulai_jabatan" id="tanggal_mulai_jabatan"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Mulai" data-language="en" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_berakhir_jabatan">Tanggal Berakhir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_berakhir_jabatan" id="tanggal_berakhir_jabatan"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-jabatan"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-jabatan"
                                    id="btn-attach-jabatan"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-jabatan" name="lampiran-jabatan" accept="application/pdf"
                                    class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)<br>
                                        upload SK Jabatan dan Uraian Jabatan menjadi satu file
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-jabatan">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-jabatan" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Jabatan -->
    <div class="modal fade" id="modal-preview-pdf-jabatan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-jabatan" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Form STR dan SIP --}}
    <div class="modal fade" id="modal-str" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
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


    {{-- Modal Form SPK dan RKK --}}
    <div class="modal fade" id="modal-spk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-spk" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_spk">
                        </div>

                        <!-- Nomor SPK -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_spk">Nomor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_spk" name="nomor_spk"
                                    placeholder="Nomor..." required>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai_spk">Tanggal Mulai</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_mulai_spk" id="tanggal_mulai_spk"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Mulai" data-language="en" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_berakhir_spk">Tanggal Berakhir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tanggal_berakhir_spk" id="tanggal_berakhir_spk"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-spk"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-spk"
                                    id="btn-attach-spk"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-spk" name="lampiran-spk" accept="application/pdf"
                                    class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-spk">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-spk" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen SPK dan RKK -->
    <div class="modal fade" id="modal-preview-pdf-spk" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-spk" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Form Sertifikat --}}
    <div class="modal fade" id="modal-sertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-sertifikat" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_sertifikat">
                        </div>

                        <!-- Nama Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama_sertifikat">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_sertifikat" name="nama_sertifikat"
                                    placeholder="Nama Sertifikat..." required>
                            </div>
                        </div>

                        <!-- Penyelenggara -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="penyelenggara_sertifikat">Penyelenggara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="penyelenggara_sertifikat"
                                    name="penyelenggara_sertifikat" placeholder="Penyelenggara Sertifikat..." required>
                            </div>
                        </div>

                        <!-- Tahun Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tahun_sertifikat">Tahun</label>
                            <div class="col-sm-10">
                                <input type="text" name="tahun_sertifikat" id="tahun_sertifikat"
                                    class="form-control js-datepicker digits" placeholder="dd/mm/yyyy"
                                    aria-label="Tanggal Berakhir" data-language="en" autocomplete="off">
                            </div>
                        </div>

                        <!-- Jenis Sertifikat -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_sertifikat">Jenis</label>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="jenis_sertifikat" name="jenis_sertifikat" required>
                                    <option value=""></option>
                                    <option value="Pelatihan">Pelatihan</option>
                                    <option value="Sertifikat">Sertifikat</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-sertifikat"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-sertifikat"
                                    id="btn-attach-sertifikat"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-sertifikat" name="lampiran-sertifikat"
                                    accept="application/pdf" class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-sertifikat">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-sertifikat" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Sertifikat -->
    <div class="modal fade" id="modal-preview-pdf-sertifikat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-sertifikat" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Form hasil MCU --}}
    <div class="modal fade" id="modal-mcu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
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



    {{-- Modal Form Dokumen Lainnya --}}
    <div class="modal fade" id="modal-dokumen-lainnya" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-dokumen-lainnya" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id_dokumen_lainnya">
                        </div>

                        <!-- Jenis Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_dokumen_lainnya">Jenis Dokumen</label>
                            <div class="col-sm-10">
                                <select class="form-select select2" id="jenis_dokumen_lainnya" name="jenis_dokumen_lainnya" required>
                                    <option value=""></option>
                                    <option value="KTP">KTP</option>
                                    <option value="KK">KK</option>
                                    <option value="NPWP">NPWP</option>
                                    <option value="BPJS Kesehatan">BPJS Kesehatan</option>
                                    <option value="BPJS Ketenagakerjaan">BPJS Ketenagakerjaan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Nomor Dokumen-->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nomor_dokumen_lainnya">Nomor Dokumen</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nomor_dokumen_lainnya" name="nomor_dokumen_lainnya"
                                    placeholder="Nomor Dokumen..." required>
                            </div>
                        </div>

                        <!-- catatan Dokumen -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="catatan_dokumen">Catatan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="catatan_dokumen" name="catatan_dokumen_lainnya" rows="3" required
                                    placeholder="Catatan..."></textarea>
                            </div>
                        </div>

                        <!-- Dokumen Lainnya -->
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="lampiran-dokumen-lainnya"> Dokumen </label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach-dokumen-lainnya"
                                    id="btn-attach-dokumen-lainnya"> <i class="fa fa-paperclip me-1"></i>
                                    Attach File
                                </button>
                                <input type="file" id="lampiran-dokumen-lainnya" name="lampiran-dokumen-lainnya"
                                    accept="application/pdf" class="d-none">
                                <div>
                                    <small class="text-muted">
                                        Maksimal 1 file (PDF)
                                    </small>
                                </div>
                                <div class="row mt-2" id="preview-images-dokumen-lainnya">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span class="fa fa-times"></span>
                        Batal</button>
                    <button class="btn btn-primary save-btn-dokumen-lainnya" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal lihat dokumen Lainnya -->
    <div class="modal fade" id="modal-preview-pdf-dokumen" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="preview-pdf-dokumen" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('master-data.dokumen.script')
@endsection