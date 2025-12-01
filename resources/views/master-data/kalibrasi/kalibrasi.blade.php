@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Kalibrasi</h3>
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
                            <span> Tambah Kalibrasi</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_kalibrasi" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Kode Kalibrasi</th>
                                            <!-- <th class="f-light">Kode Asset</th> -->
                                            <th class="f-light">No Asset</th>
                                            <th class="f-light">Nama Asset</th>
                                            <th class="f-light">Merek</th>
                                            <th class="f-light">No SN</th>
                                            <th class="f-light">Lokasi</th>
                                            <th class="f-light">Status</th>
                                            <th class="f-light">Tanggal</th>
                                            <th class="f-light">Hari</th>
                                            <th class="f-light">Aktif</th>
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
    <div class="modal fade" id="modal-kalibrasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-kalibrasi" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>

                        <div class="form-group row my-0 g-lg-2 col-md-12">
                            <div class="col-md-12">
                                {{-- Nomor Asset --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode_aset">Nomor Asset</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" id="kode_aset" name="kode_aset"
                                            data-placeholder="---- Pilih Salah Satu ----" required></select>
                                    </div>
                                </div>

                                {{-- Nama Aset --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama_aset">Nama Asset</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="nama_aset" name="nama_aset" type="text"
                                            placeholder="Nama Asset..." readonly>
                                    </div>
                                </div>


                                {{-- No SN --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="no_sn">No SN</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="no_sn" name="no_sn" type="text"
                                            placeholder="No SN..." readonly>
                                    </div>
                                </div>

                                {{-- Lokasi --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="lokasi_name">Lokasi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="lokasi_name" name="lokasi_name" type="text"
                                            placeholder="Lokasi..." readonly>
                                    </div>
                                </div>

                                {{-- Tanggal Kalibrasi --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="tgl_kalibrasi">Tgl Kalibrasi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control js-datepicker digits" name="tgl_kalibrasi"
                                            type="text" placeholder="Tanggal Kalibrasi..." data-language="en" required>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-8 row">
                                        <div
                                            class="form-check radio radio-primary d-flex justify-content-center align-items-center col-md-2">
                                            <input class="form-check-input" id="Laik" type="radio" name="status" required
                                                value="Laik">
                                            <label class="form-check-label" for="Laik">Laik</label>
                                        </div>
                                        <div
                                            class="form-check radio radio-primary d-flex justify-content-center align-items-center col-md-2">
                                            <input class="form-check-input" id="Tidak Laik" type="radio" name="status"
                                                required value="Tidak Laik">
                                            <label class="form-check-label" for="Tidak Laik">Tidak Laik</label>
                                        </div>
                                    </div>
                                </div>


                                {{-- Aktif --}}
                                <div class="media mb-2">
                                    <label class="col-sm-2 col-form-label m-r-10">Aktif</label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch">
                                            <input class="form-control" name="aktif" type="checkbox" checked>
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

@endsection


@section('script')
    @include('master-data.kalibrasi.script')
@endsection