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

        .select2-fixed {
            width: 220px;
            /* kunci lebar */
            min-width: 220px;
            max-width: 220px;
        }
    </style>

@endsection

@section('breadcrumb-title')
    <h3>RS Online Tempat Tidur</h3>
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
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary add-btn">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah</span>
                                </button>
                            </div>
                        </div>

                        {{-- TABLE --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_tt" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">ID TT</th>
                                            <th class="f-light">Kelas</th>
                                            <th class="f-light">Ruang</th>
                                            <th class="f-light">Kode Siranap</th>
                                            <th class="f-light">Jumlah Ruang</th>
                                            <th class="f-light">Kosong</th>
                                            <th class="f-light">Jumlah Terpakai</th>
                                            <th class="f-light">ID TTT</th>
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

    {{-- MODAL FORM TEMPAT TIDUR --}}
    <div class="modal fade" id="modal-tt" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Form Data Tempat Tidur</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-3 form-tt" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">

                        {{-- ID TT --}}
                        <div class="col-md-6">
                            <label class="form-label">ID TT</label>
                            <select class="form-select select2" name="id_tt" required>
                                <option></option>
                            </select>
                        </div>

                        {{-- Jenis Tempat Tidur --}}
                        <!-- <div class="col-md-6">
                            <label class="form-label">Jenis Tempat Tidur</label>
                            <input type="text" name="tt" class="form-control" placeholder="VVIP / Super VIP">
                        </div> -->

                        {{-- Ruangan --}}
                        <div class="col-md-6">
                            <label class="form-label">Ruangan</label>
                            <input type="text" name="ruang" class="form-control" placeholder="Nama Ruangan" required>
                        </div>

                        {{-- Kode Siranap --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Kode Siranap</label>
                            <input type="text" name="kode_siranap" class="form-control" placeholder="Kode Siranap">
                        </div> -->

                        {{-- Jumlah Ruang --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Jumlah Ruang</label>
                            <input type="number" name="jumlah_ruang" class="form-control" value="0">
                        </div> -->

                        {{-- Jumlah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Bed</label>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>

                        {{-- Kosong --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Kosong</label>
                            <input type="number" name="kosong" class="form-control" value="0">
                        </div> -->

                        {{-- Terpakai --}}
                        <div class="col-md-6">
                            <label class="form-label">Terpakai</label>
                            <input type="number" name="terpakai" class="form-control" required>
                        </div>

                        {{-- Terpakai Suspek --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Terpakai Suspek</label>
                            <input type="number" name="terpakai_suspek" class="form-control" value="0">
                        </div> -->

                        {{-- Terpakai Konfirmasi --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Terpakai Konfirmasi</label>
                            <input type="number" name="terpakai_konfirmasi" class="form-control" value="0">
                        </div> -->

                        {{-- Antrian --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Antrian</label>
                            <input type="number" name="antrian" class="form-control" value="0">
                        </div> -->

                        {{-- Prepare --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Prepare</label>
                            <input type="number" name="prepare" class="form-control" value="0">
                        </div> -->

                        {{-- Prepare Plan --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Prepare Plan</label>
                            <input type="number" name="prepare_plan" class="form-control" value="0">
                        </div> -->

                        {{-- Terpakai DBD --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Terpakai DBD</label>
                            <input type="number" name="terpakai_dbd" class="form-control" value="0">
                        </div> -->

                        {{-- Terpakai DBD Anak --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Terpakai DBD Anak</label>
                            <input type="number" name="terpakai_dbd_anak" class="form-control" value="0">
                        </div> -->

                        {{-- Covid --}}
                        <!-- <div class="col-md-3">
                            <label class="form-label">Covid</label>
                            <select name="covid" class="form-select">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div> -->

                        {{-- ID T TT --}}
                        <div class="col-md-3">
                            <!-- <label class="form-label">ID T TT</label> -->
                            <input type="text" name="id_t_tt" class="form-control" placeholder="ID T TT" hidden>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal
                    </button>

                    <button class="btn btn-primary save-btn" type="button">
                        <span class="fa fa-save"></span> Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('rs-online.tempat_tidur.script')
@endsection