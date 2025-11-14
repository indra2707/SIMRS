@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Mutasi</h3>
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
                            <span> Tambah Mutasi</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_mutasi" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">ID</th>
                                            <th class="f-light">Tgl Mutasi</th>
                                            <th class="f-light">ID Asset</th>
                                            <th class="f-light">Nama</th>
                                            <th class="f-light">No. SN</th>
                                            <th class="f-light">ID Lokasi Asal</th>
                                            <th class="f-light">Lokasi Asal</th>
                                            <th class="f-light">ID Lokasi Tujuan</th>
                                            <th class="f-light">Lokasi Tujuan</th>
                                            <th class="f-light">ID Kondisi</th>
                                            <th class="f-light">Kondisi</th>
                                            <th class="f-light">Catatan</th>
                                            <th class="">Action</th>
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
    <div class="modal fade" id="modal-mutasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-mutasi" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>

                        <div class="form-group row my-0 g-lg-2 col-md-12">
                            <div class="col-md-12">

                                {{-- Tanggal Mutasi --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="tgl_mutasi">Tgl Mutasi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control js-datepicker digits" name="tgl_mutasi"
                                            type="text" placeholder="Tanggal Mutasi..." data-language="en" required>
                                    </div>
                                </div>

                                {{-- Nama Asset --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_aset">Nama Asset</label>
                                    <div class="col-sm-10">
                                       <select id="kode_aset" name="kode_aset" class="form-select form-control" required data-placeholder="---- Pilih Salah Satu ----"></select>
                                    </div>
                                </div>

                                 {{-- id Lokasi Lama --}}
                                <!-- <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="lokasi_lama">Id Lokasi Lama</label>
                                    <div class="col-sm-10"> -->
                                        <input class="form-control" id="id_lokasi_lama" name="id_lokasi_lama" type="hidden" placeholder="id Lokasi Lama..." readonly>
                                    <!-- </div>
                                </div> -->

                                 {{-- Lokasi Lama --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="lokasi_lama">Lokasi Lama</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="lokasi_lama" name="lokasi_lama" type="text" placeholder="Lokasi Lama..." readonly>
                                    </div>
                                </div>

                                {{-- Lokasi --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_lokasi">Lokasi Baru</label>
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

                                {{-- Keterangan --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                            placeholder="Keterangan..." required></textarea>
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

@endsection


@section('script')
    @include('master-data.mutasi.script')
@endsection