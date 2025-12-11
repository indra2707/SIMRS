@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

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
    <div class="modal fade" id="modal-aset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-aset" novalidate="" autocomplete="off">
                        <div class="form-group row my-0 g-lg-2 col-md-12">
                            <div class="col-md-6">
                                @csrf
                                {{-- Hidden Input --}}
                                <div class="mb-2 row">
                                    <input type="hidden" name="id">
                                </div>
                                {{-- Kode --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode">Nomor</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control" name="no_aset" type="text" required
                                            placeholder="Nomor...">
                                    </div>
                                </div>
                                {{-- Jenis --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="jenis">Jenis</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control select2" name="jenis" required>
                                            <option></option>
                                            <option value="Aset">Aset</option>
                                            <option value="Inventaris">Inventaris</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Nama --}}
                                <div class="mb-2 row">
                                    <label class="col-2 col-form-label" for="nama">Nama</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control" name="nama" type="text"
                                            placeholder="Nama..." required>
                                    </div>
                                </div>
                                {{-- Merek --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Merek</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control" name="merek" type="text"
                                            placeholder="Merek..." required>
                                    </div>
                                </div>
                                {{-- Tipe --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="tipe">Tipe</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control" name="tipe" type="text"
                                            placeholder="Tipe..." required>
                                    </div>
                                </div>
                                {{-- No SN --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">No SN</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control" name="no_sn" type="text"
                                            placeholder="No Serial Number..." required>
                                    </div>
                                </div>
                                {{-- Tahun --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Tahun</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control js-datepicker digits" name="tahun"
                                            type="text" placeholder="Tahun Prolehan..." data-language="en" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                {{-- Harga --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Harga</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control rupiah-number" name="harga" type="text"
                                            placeholder="Rp..." required>
                                    </div>
                                </div>
                                {{-- Kategori --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control select2" name="kategori" required>
                                            <option></option>
                                            <option value="Umum">Umum</option>
                                            <option value="ICT">ICT</option>
                                            <option value="Alkes">Alkes</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Lokasi --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_lokasi">Lokasi</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" id="kode_lokasi" name="kode_lokasi"
                                            data-placeholder="---- Pilih Salah Satu ----" required></select>
                                    </div>
                                </div>
                                {{-- kode kondisi aset --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_kondisi_aset">Kondisi</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" id="kode_kondisi_aset"
                                            name="kode_kondisi_aset" data-placeholder="---- Pilih Salah Satu ----"
                                            required></select>
                                    </div>
                                </div>
                                {{-- kode kelompok aset --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_kelompok_aset">Kelompok</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" id="kode_kelompok_aset"
                                            name="kode_kelompok_aset" data-placeholder="---- Pilih Salah Satu ----"
                                            required></select>
                                    </div>
                                </div>
                                {{-- kode Vendor --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="id_vendor">Vendor</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" id="id_vendor" name="id_vendor"
                                            data-placeholder="---- Pilih Salah Satu ----" required></select>
                                    </div>
                                </div>
                                {{-- Satus --}}
                                <div class="media mb-2">
                                    <label class="col-sm-2 col-form-label m-r-10">Status</label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch">
                                            <input class="form-control" name="status" type="checkbox" checked>
                                            <span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
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
