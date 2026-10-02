@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .tabel-baris-tujuan-master th,
        .tabel-baris-tujuan-master td {
            vertical-align: middle;
        }

        .baris-baru-tujuan .form-select,
        .baris-baru-tujuan .form-control {
            margin-bottom: 4px;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Master Lembar Disposisi</h3>
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

                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-primary btn-tambah-master">
                                <span class="fa fa-plus"></span> Tambah Lembar
                            </button>
                        </div>

                        <div class="table-responsive signal-table">
                            <table id="table_disposisi_master" class="table table-hover"
                                data-buttons-class="primary" data-toggle="table">
                                <thead class="text-bold text-white text-uppercase text-center">
                                    <tr>
                                        <th class="f-light">#</th>
                                        <th class="f-light">Nama Lembar</th>
                                        <th class="f-light">Pemilik</th>
                                        <th class="f-light">Jumlah Tujuan</th>
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

    {{-- Modal Tambah / Edit Lembar --}}
    <div class="modal fade" id="modal-form-master" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title judul-modal-master">Tambah Lembar Disposisi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-2 form-master" autocomplete="off">
                        <input type="hidden" name="id">

                        <label for="nama_master" class="col-form-label col-sm-12">
                            Nama Lembar <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="nama_master" id="nama_master"
                                placeholder="Contoh: VICE DIRECTOR HCGA" required>
                        </div>

                        <label for="id_pegawai_pemilik" class="col-form-label col-sm-12">
                            Pemilik / Pemegang Jabatan
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control mb-1 cari-pegawai-pemilik"
                                placeholder="Cari nama pegawai...">
                            <select class="form-select" name="id_pegawai_pemilik" id="id_pegawai_pemilik">
                                <option value="">-- Belum dipilih --</option>
                            </select>
                            <small class="text-muted">
                                Pegawai yang saat ini memegang jabatan ini. Kalau ada mutasi, tinggal ubah di sini.
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success btn-simpan-master" type="button">
                        <span class="fa fa-save"></span> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Kelola Tujuan (baris nama di lembar) --}}
    <div class="modal fade" id="modal-kelola-tujuan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Kelola Tujuan &mdash; <span class="kelola-tujuan-nama-master"></span>
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="kelola-tujuan-id-master">

                    <label class="fw-bold mb-2">Daftar Tujuan Saat Ini</label>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm tabel-baris-tujuan-master">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">Urutan</th>
                                    <th>Nama Pegawai</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="isi-baris-tujuan-master">
                                {{-- diisi via JS --}}
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <label class="fw-bold mb-2">Tambah Baris Baru</label>
                    <div class="baris-baru-tujuan-wrapper">
                        {{-- baris-baris input baru akan ditambahkan di sini via JS --}}
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm btn-tambah-baris-baru mt-1">
                        <span class="fa fa-plus"></span> Tambah Baris
                    </button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-success btn-simpan-baris-baru" type="button">
                        <span class="fa fa-save"></span> Simpan Baris Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('surat.disposisi-master.script')
@endsection
