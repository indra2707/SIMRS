@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ $menuTitle }}</li>
    <li class="breadcrumb-item active">{{ $menuSubtitle }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="header-top">
                            <div class="card-header-right-icon">
                                <div class="dropdown icon-dropdown">
                                    {{-- Button Add Form Tarif --}}
                                    <button class="btn btn-primary add-btn">
                                        <span class="fa fa-plus"></span>
                                        <span> Tambah</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 campaign-table">
                        <div class="recent-table table-responsive currency-table">
                            <table id="table_tindakan_tarif" class="table table-hover" data-buttons-class="primary"
                                data-toggle="table">
                                <thead class="text-bold text-white text-uppercase text-center">
                                    <tr>
                                        <th class="f-light">No.</th>
                                        <th class="f-light">Kode Tarif</th>
                                        <th class="f-light">Tindakan</th>
                                        <th class="f-light">Tarif RS</th>
                                        <th class="f-light">Kelompok</th>
                                        <th class="f-light">Tipe</th>
                                        <th class="f-light">Kategori</th>
                                        <th class="f-light">Group</th>
                                        <th class="f-light">Cito</th>
                                        <th class="f-light">Status Operasi</th>
                                        <th class="f-light">Status Tindakan</th>
                                        <th class="f-light">Flat</th>
                                        <th class="f-light">#</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-tarif-tindakan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-tarif-tindakan needs-validation" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="row">
                            {{-- Colum 1 --}}
                            <div class="col-md-6 col-lg-6 col-xl-6 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="kode">Kode</label>
                                    <input class="form-control form-control-sm" name="kode" type="text"
                                        placeholder="Kode..." required>
                                </div>
                                <div class="col-md-8">
                                    <label class="col-form-label" for="nama_tindakan">Tindakan</label>
                                    <input class="form-control form-control-sm" name="nama_tindakan" type="text"
                                        placeholder="Tindakan..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="kode">Kelompok Tindakan</label>
                                    <select class="form-select form-select-sm js-select-2"
                                        data-placeholder="---Pilih Tarif Tindakan---" id="kelompok_tindakan"
                                        data-url="{{ route('get-select-tarif-tindakan') }}" required></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="tipe">Tipe</label>
                                    <input class="form-control form-control-sm" name="tipe" type="text"
                                        placeholder="Tindakan..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="kategori_layanan">Kategori Layanan</label>
                                    <input class="form-control form-control-sm" name="kategori_layanan" type="text"
                                        placeholder="Tindakan..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="group_tindakan">Grup Tindakan</label>
                                    <input class="form-control form-control-sm" name="group_tindakan" type="text"
                                        placeholder="Tindakan..." required>
                                </div>
                            </div>
                            {{-- Colum 2 --}}
                            <div class="col-md-6 col-lg-6 col-xl-6 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="status_cito">Status Cito</label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch">
                                            <input class="form-control" name="status_cito" type="checkbox">
                                            <span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="col-form-label" for="nilai_cito">Nilai Cito</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control numInput" type="text" name="nilai_cito"
                                            id="nilai_cito" placeholder="Nilai Cito..." title="Satuan persen"
                                            onkeypress='validateNumber(event)' required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    {{-- <input class="form-control form-control-sm" name="kode" type="text"
                                        placeholder="Tindakan..." required> --}}
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="status_tindakan">Status Tindakan</label>
                                    <input class="form-control form-control-sm" name="status_tindakan"
                                        id="status_tindakan" type="text" placeholder="Tindakan..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="flat">Jasa Medis Flat</label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch">
                                            <input class="form-control" name="flat" id="flat" type="checkbox">
                                            <span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="kanban-item">
                                    <a class="kanban-box p-2" href="#">
                                        <span class="kanban-title">Pilih salah satu</span>
                                        <div class="d-flex">
                                            <div class="m-checkbox-inline">
                                                <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                    <input class="form-check-input one-checked" id="status_operasi"
                                                        name="status_operasi" type="checkbox">
                                                    <label class="form-check-label" for="status_operasi">Status
                                                        Operasi</label>
                                                </div>
                                                <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                    <input class="form-check-input one-checked" id="status_cathlab"
                                                        name="status_cathlab" type="checkbox">
                                                    <label class="form-check-label" for="status_cathlab">Status
                                                        Cathlab</label>
                                                </div>
                                                <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                    <input class="form-check-input one-checked" id="tindakan_anestesi"
                                                        name="tindakan_anestesi" type="checkbox">
                                                    <label class="form-check-label" for="tindakan_anestesi">Tindakan
                                                        Anestesi</label>
                                                </div>
                                                <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                    <input class="form-check-input one-checked" id="tindakan_asisten"
                                                        name="tindakan_asisten" type="checkbox">
                                                    <label class="form-check-label" for="tindakan_asisten"
                                                        name="tindakan_asisten">Tindakan Asisten
                                                        Opr</label>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
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
    @include('tarif.tindakan.script')
@endsection
