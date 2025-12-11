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
    <h3>Asset & Inventaris</h3>
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
                        <button class="btn btn-primary add-btn">
                            <span class="fa fa-plus"></span>
                            <span> Tambah</span>
                        </button>

                        <button class="btn btn-success print-all-btn">
                            <span class="fa fa-print"></span>
                            <span> Print All</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_aset" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Jenis</th>
                                            <th class="f-light">Nama</th>
                                            <th class="f-light">Merek</th>
                                            <th class="f-light">Tipe</th>
                                            <th class="f-light">No SN</th>
                                            <th class="f-light">Tahun</th>
                                            <th class="f-light">Kategori</th>
                                            <th class="f-light">Kelompok</th>
                                            <th class="f-light">Harga</th>
                                            <th class="f-light">Nama Lokasi</th>
                                            <th class="f-light">Nama Kondisi</th>
                                            <th class="f-light">Vendor</th>
                                            <th class="f-light">Umur</th>
                                            <th class="f-light">Batas Umur</th>
                                            <th class="f-light">Sisa Umur</th>
                                            <th class="f-light">Biaya Penyusutan / Bln</th>
                                            <th class="f-light">Akumulasi Nilai Penyusutan</th>
                                            <th class="f-light">Nilai Buku</th>
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

    {{-- Modal Form Aset --}}
    <div class="modal fade" id="modal-aset" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-aset" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">

                        <!-- Nomor Aset -->
                        <label for="no_aset" class="col-form-label col-sm-1">Nomor</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="no_aset" type="text" required
                                placeholder="Nomor...">
                        </div>

                        <!-- Harga -->
                        <label for="harga" class="col-form-label col-sm-1">Harga</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control rupiah-number" name="harga" type="text"
                                placeholder="Rp..." required>
                        </div>


                        <!-- Jenis -->
                        <label for="jenis" class="col-form-label col-sm-1">Jenis</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" name="jenis" required>
                                <option></option>
                                <option value="Aset">Aset</option>
                                <option value="Inventaris">Inventaris</option>
                            </select>
                        </div>

                        <!-- kategori -->
                        <label for="kategori" class="col-form-label col-sm-1">Kategori</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" name="kategori" required>
                                <option></option>
                                <option value="Umum">Umum</option>
                                <option value="ICT">ICT</option>
                                <option value="Alkes">Alkes</option>
                            </select>
                        </div>

                        <!-- Nama  -->
                        <label for="nama" class="col-form-label col-sm-1">Nama</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="nama" type="text" required placeholder="Nama...">
                        </div>

                        <!-- Lokasi  -->
                        <label for="kode_lokasi" class="col-form-label col-sm-1">Lokasi</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control" id="kode_lokasi" name="kode_lokasi"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Merek  -->
                        <label for="merek" class="col-form-label col-sm-1">Merek</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="merek" type="text" placeholder="Merek..."
                                required>
                        </div>

                        <!-- kondisi aset  -->
                        <label for="kode_kondisi_aset" class="col-form-label col-sm-1">Kondisi</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control" id="kode_kondisi_aset" name="kode_kondisi_aset"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Tipe  -->
                        <label for="tipe" class="col-form-label col-sm-1">Tipe</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="tipe" type="text" placeholder="Tipe..." required>
                        </div>

                        <!-- Kelompok  -->
                        <label for="kode_kelompok_aset" class="col-form-label col-sm-1">Kelompok</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control" id="kode_kelompok_aset" name="kode_kelompok_aset"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- No SN  -->
                        <label for="no_sn" class="col-form-label col-sm-1">No. SN</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="no_sn" type="text"
                                placeholder="No Serial Number..." required>
                        </div>

                        <!-- vendor  -->
                        <label for="id_vendor" class="col-form-label col-sm-1">Vendor</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control" id="id_vendor" name="id_vendor"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Tahun Perolehan  -->
                        <label for="tahun" class="col-form-label col-sm-1">Tahun</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control js-datepicker digits" name="tahun" type="text"
                                placeholder="Tahun Prolehan..." data-language="en" required>
                        </div>

                        <!-- Satus  -->
                        <label for="status_aset" class="col-form-label col-sm-1">Status</label>
                        <div class="col-sm-5">
                            <div class="media-body switch-sm icon-state">
                                <label class="switch">
                                    <input class="form-control" name="status" type="checkbox" checked>
                                    <span class="switch-state"></span>
                                </label>
                            </div>
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


    {{-- Modal Info Mutasi --}}
    <div class="modal fade" id="modal-info-mutasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Info Mutasi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <table id="table-info-mutasi" class="table table-hover" data-buttons-class="primary"
                        data-toggle="table">
                        <thead class="text-bold text-white text-uppercase text-center">
                            <tr>
                                <th class="f-light">Tgl Mutasi</th>
                                <th class="f-light">Nama</th>
                                <th class="f-light">No. SN</th>
                                <th class="f-light">Lokasi Asal</th>
                                <th class="f-light">Lokasi Tujuan</th>
                                <th class="f-light">Kondisi</th>
                                <th class="f-light">Catatan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- <div class="modal-footer">
                                                                                                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                                                                                                        <span class="fa fa-times"></span> Batal</button>
                                                                                                </div> -->
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('master-data.aset.script')
@endsection
