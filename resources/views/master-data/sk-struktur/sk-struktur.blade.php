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
    <h3>SK Struktur</h3>
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
                            <span> Tambah SK Struktur</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_sk_struktur" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nomor SK</th>
                                            <th class="f-light">Tanggal Mulai</th>
                                            <th class="f-light">Tanggal Selesai</th>
                                            <th class="f-light">Keterangan</th>
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

    {{-- Modal Form SK Struktur --}}
    <div class="modal fade" id="modal-sk-struktur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-sk-struktur" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>

                        {{-- Nomor SK --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="no_sk">Nomor SK</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="no_sk" type="text" placeholder="Nomor SK..."
                                    required>
                            </div>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control js-datepicker digits" name="tanggal_mulai"
                                    type="text" data-language="en" placeholder="Tanggal Mulai..." required>
                            </div>
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="tanggal_selesai">Tanggal Selesai</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control js-datepicker digits" name="tanggal_selesai"
                                    type="text" data-language="en" placeholder="Tanggal Selesai..." required>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan" rows="3" placeholder="Keterangan..."
                                    required></textarea>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="media mb-2">
                            <label class="col-sm-2 col-form-label m-r-10">Status</label>
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


    <!-- View Jabatan -->
    <div class="modal fade" id="modal-view-jabatan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Add Button --}}
                    <button class="btn btn-primary add-jabatan">
                        <span class="fa fa-plus"></span>
                        <span> Tambah Jabatan</span>
                    </button>
                    {{-- Table View --}}
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive signal-table">
                            <table id="table_jabatan" class="table table-hover" data-buttons-class="primary"
                                data-toggle="table">
                                <thead class="text-bold text-white text-uppercase text-center">
                                    <tr>
                                        <th class="f-light">No</th>
                                        <th class="f-light">Nomor SK</th>
                                        <th class="f-light">Unit</th>
                                        <th class="f-light">Nama Jabatan</th>
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

    {{-- Modal jabatan --}}
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
                            <input type="hidden" name="id_sk_struktur">
                        </div>

                        {{-- Unit --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="unit">Unit</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="unit" required>
                                    <option value=""></option>
                                    <option value="RS Pertamina Royal Biringkanaya">RS Pertamina Royal Biringkanaya
                                    </option>
                                    <option value="Kantor Pusat PBM">Kantor Pusat PBM</option>
                                </select>
                            </div>
                        </div>

                        {{-- Nama Jabatan --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama_jabatan">Nama Jabatan</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="nama_jabatan" type="text"
                                    placeholder="Nama Jabatan..." required>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="media mb-2">
                            <label class="col-sm-2 col-form-label m-r-10">Status</label>
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
                    <button class="btn btn-primary save-jabatan" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('master-data.sk-struktur.script')
@endsection