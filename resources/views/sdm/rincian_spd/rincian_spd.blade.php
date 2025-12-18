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
    <h3>Rincian SPD</h3>
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
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_rincian" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Nomor Surat</th>
                                            <th class="f-light">Nama Pegawai</th>
                                            <th class="f-light">Tgl Berangkat</th>
                                            <th class="f-light">Kota Asal</th>
                                            <th class="f-light">Kota Tujuan</th>
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

    {{-- Modal Form rincian --}}
    <div class="modal fade" id="modal-rincian" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-rincian" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">

                        <!-- Nomor Surat -->
                        <label for="no_surat" class="col-form-label col-sm-1">Nomor Surat</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="no_surat" type="text" required
                                placeholder="Nomor Surat..." readonly>
                        </div>

                        <!-- Nama Petugas -->
                        <label for="nama" class="col-form-label col-sm-1">Nama</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="nama" type="text" required
                                placeholder="Nama Pegawai..." readonly>
                        </div>

                        <!-- Nama Mengajukan -->
                        <label for="id_mengajukan" class="col-form-label col-sm-1">Mengajukan</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" id="id_mengajukan" name="id_mengajukan"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Nama Menyetujui -->
                        <label for="id_menyetujui" class="col-form-label col-sm-1">Menyetujui</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" id="id_menyetujui" name="id_menyetujui"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- Jenis -->
                        <label for="jenis" class="col-form-label col-sm-1">Jenis</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" name="jenis" required>
                                <option></option>
                                <option value="Panjar">Panjar</option>
                                <option value="SP3">SP3</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <label for="tanggal" class="col-form-label col-sm-1">Tanggal</label>
                        <div class="col-sm-5">
                            <input type="text" name="tanggal" id="tanggal" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- panjar -->
                        <label for="panjar" class="col-form-label col-sm-1">Panjar</label>
                        <div class="col-sm-11">
                            <input class="form-control form-control rupiah-number" name="panjar" type="text"
                                placeholder="..." readonly>
                        </div>
                    </form><br>

                    <!-- Rincian Detail -->
                    {{-- Add Button --}}
                    <button class="btn btn-primary add-btn">
                        <span class="fa fa-plus"></span>
                        <span> Tambah Biaya</span>
                    </button><br><br>
                    {{-- Table View --}}
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive signal-table">
                            <table id="table_lokasi" class="table table-hover" data-buttons-class="primary"
                                data-toggle="table">
                                <thead class="text-bold text-white text-uppercase text-center">
                                    <tr>
                                        <th class="f-light">No</th>
                                        <th class="f-light">Jenis Biaya</th>
                                        <th class="f-light">Tarif PJL</th>
                                        <th class="f-light">Jumlah</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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


    {{-- Modal detail --}}
    <div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-detail" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">
                        <input type="hidden" name="no_surat">

                        <!-- Biaya  -->
                        <label for="biaya" class="col-form-label col-sm-1">Biaya</label>
                        <div class="col-sm-11">
                            <select class="form-select form-control" id="biaya" name="biaya"
                                data-placeholder="---- Pilih Salah Satu ----" required></select>
                        </div>

                        <!-- harga  -->
                        <label for="harga" class="col-form-label col-sm-1">Harga</label>
                        <div class="col-sm-5">
                            <input id="harga" class="form-control" name="harga" type="text" placeholder="Harga..." readonly
                                ondblclick="onDblClick(this)">
                        </div>

                        <!-- Jumlah  -->
                        <label for="jumlah" class="col-form-label col-sm-1">Jumlah</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="jumlah" type="number" placeholder="Jumlah..."
                                required>
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
    @include('sdm.rincian_spd.script')
@endsection