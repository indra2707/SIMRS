@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Tambah Tarif</h3>
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
                            <span> Tambah Traif</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_tindakan_tarif" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Kode Tindakan</th>
                                            <th class="f-light">Nama Tindakan</th>
                                            <th class="f-light">Kategori</th>
                                            <th class="f-light">Cito (%)</th>
                                            <th class="f-light">Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- <ul class="nav-menus">
                        <li class="language-nav">
                            <div class="actions_wrapper">
                                <div class="current_action">
                                    <div class="action_icon">
                                        <i class="icon-more-alt"></i>
                                    </div>
                                </div>
                                <div class="more_actions">
                                    <div class="action_icon" data-value="de"><i class="flag-icon flag-icon-de"></i><span
                                            class="action-txt">Deutsch</span></div>
                                    <div class="action_icon" data-value="es"><i class="flag-icon flag-icon-es"></i><span
                                            class="action-txt">Español</span></div>
                                    <div class="action_icon" data-value="fr"><i class="flag-icon flag-icon-fr"></i><span
                                            class="action-txt">Français</span></div>
                                    <div class="action_icon" data-value="pt"><i class="flag-icon flag-icon-pt"></i><span
                                            class="action-txt">Português<span> (BR)</span></span></div>
                                    <div class="action_icon" data-value="cn"><i class="flag-icon flag-icon-cn"></i><span
                                            class="action-txt">简体中文</span></div>
                                    <div class="action_icon" data-value="ae"><i class="flag-icon flag-icon-ae"></i><span
                                            class="action-txt">لعربية <span> (ae)</span></span></div>
                                </div>
                            </div>
                        </li>
                    </ul> --}}
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

    {{-- Modal Form Tarif--}}
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
                        <div class="row">
                            <div class="mb-2 row">
                                <div class="col-sm-10">
                                    <input type="hidden" name="id">
                                </div>
                            </div>

                            {{-- Kode --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="kode">Kode Tindakan</label>
                                <div class="col-sm-10">
                                    <input class="form-control form-control-sm" name="kode_tarif" type="text">
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Nama Tindakan</label>
                                <div class="col-sm-10">
                                    <input class="form-control form-control-sm" name="tindakan" type="text"
                                        placeholder="Nama Tindakan..." required>
                                </div>
                            </div>
                            {{-- Kategori --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="kategori">Kategori</label>
                                <div class="col-sm-10">
                                    <select class="form-select form-control select2" name="kategori" required>
                                        <option></option>
                                        <option value="Tindakan">Tindakan</option>
                                        <option value="Konsultasi">Konsultasi</option>
                                        <option value="Sewa Alat">Sewa Alat</option>
                                        <option value="Kamar Bedah">Kamar Bedah</option>
                                        <option value="Cathlab">Cathlab</option>
                                        <option value="Radiologi">Radiologi</option>
                                        <option value="Laboratorium">Laboratorium</option>
                                        <option value="Akomodasi">Akomodasi</option>
                                        <option value="Paket">Paket</option>
                                    </select>
                                </div>
                            </div>
                            {{-- Nilai Cito --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Nilai Cito</label>
                                <div class="col-sm-2">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control numInput" type="text" name="cito" id="nilai_cito"
                                            placeholder="Nilai Cito..." title="Satuan persen"
                                            onkeypress='validateNumber(event)'>
                                        <span class="input-group-text">%</span>
                                    </div>
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

                            {{-- COA --}}
                            <div class="kanban-item">
                                <a class="kanban-box p-1 col-sm-11" href="#" data-bs-original-title=""
                                    title="">
                                    <span class="kanban-title">Mapping COA</span><br><br>
                                    <div class="d-flex">
                                        <div class="m-checkbox-inline">
                                        </div>
                                    </div>

                                    <div class="form-group row my-0 g-lg-2 col-md-12">
                                        <div class="col-md-4">
                                            {{-- COA RAWAT JALAN --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="coa_rj">COA RJ</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_pendapatan_rj" name="coa_pendapatan_rj"
                                                        data-url="{{ route('master-data.coa.select') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>

                                            {{-- COA RAWAT iNAP --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="coa_ri">COA RI</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_pendapatan_ri" name="coa_pendapatan_ri"
                                                        data-url="{{ route('master-data.coa.select1') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- COA REDUKSI RJ --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="reduksi_rj">Reduksi RJ</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_reduksi_rj" name="coa_reduksi_rj"
                                                        data-url="{{ route('master-data.coa.select2') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>

                                            {{-- COA REDUKSI RI --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="reduksi_ri">Reduksi RI</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_reduksi_ri" name="coa_reduksi_ri"
                                                        data-url="{{ route('master-data.coa.select3') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- COA MCU INSITE --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="insite">MCU Insite</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_mcu_insite" name="coa_mcu_insite"
                                                        data-url="{{ route('master-data.coa.select4') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>

                                            {{-- MCU ONSITE --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="onsite">MCU Onsite</label>
                                                <div class=" col-sm-7">
                                                    <select class="form-select form-control js-select-2"
                                                        id="coa_mcu_onsite" name="coa_mcu_onsite"
                                                        data-url="{{ route('master-data.coa.select5') }}"
                                                        data-placeholder="---- Pilih ----"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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

    {{-- Modal Form Harga Tindakan --}}
    <div class="modal fade" id="modal-harga-tindakan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-harga-tindakan needs-validation" novalidate="" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="mb-2 row">
                                <div class="col-sm-10">
                                    <input type="hidden" name="id">
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="kode">Kode Tindakan</label>
                                <div class="col-sm-10">
                                    <input class="form-control form-control-sm" name="kode_tarif" type="text"
                                        disabled>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Nama Tindakan</label>
                                <div class="col-sm-10">
                                    <input class="form-control form-control-sm" name="tindakan" type="text" disabled
                                        placeholder="Nama Tindakan..." required>
                                </div>
                            </div>
                            {{-- Kategori --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Kategori</label>
                                <div class="col-sm-10">
                                    <select class="form-select form-control select2" name="kategori" required disabled>
                                        <option></option>
                                        <option value="Tindakan">Tindakan</option>
                                        <option value="Konsultasi">Konsultasi</option>
                                        <option value="Sewa Alat">Sewa Alat</option>
                                        <option value="Kamar Bedah">Kamar Bedah</option>
                                        <option value="Cathlab">Cathlab</option>
                                        <option value="Radiologi">Radiologi</option>
                                        <option value="Laboratorium">Laboratorium</option>
                                        <option value="Akomodasi">Akomodasi</option>
                                        <option value="Paket">Paket</option>
                                    </select>
                                </div>
                            </div>
                            {{-- Nilai Cito --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Nilai Cito</label>
                                <div class="col-sm-2">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control numInput" type="text" name="cito" disabled
                                            id="nilai_cito" placeholder="Nilai Cito..." title="Satuan persen">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            {{-- Satus --}}
                            <div class="media mb-2">
                                <label class="col-sm-2 col-form-label m-r-10">Status</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked disabled>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <div class="header-top">
                                <div class="card-header-right-icon">
                                    <div class="dropdown icon-dropdown">
                                        {{-- Button Add Form Tarif --}}
                                        <button class="btn btn-primary add-btn-harga">
                                            <span class="fa fa-plus"></span>
                                            <span> Tambah Tarif</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-0 campaign-table">
                                <div class="recent-table table-responsive currency-table">
                                    <table id="table_harga_tarif" class="table table-hover" data-buttons-class="primary"
                                        data-toggle="table">
                                        <thead class="text-bold text-white text-uppercase text-center">
                                            <tr>
                                                <th class="f-light">SK Tarif</th>
                                                <th class="f-light">Kelas 1</th>
                                                <th class="f-light">Kelas 2</th>
                                                <th class="f-light">Kelas 3</th>
                                                <th class="f-light">Kelas Isolasi</th>
                                                <th class="f-light">Kelas Intensif</th>
                                                <th class="f-light">Kelas VIP</th>
                                                <th class="f-light">Kelas VVIP</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </a>
                </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Form modal input harga --}}
    <div class="modal fade" id="modal-harga-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-harga-detail needs-validation" novalidate="" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="mb-2 row">
                                <div class="col-sm-10">
                                    <input type="hidden" name="id">
                                </div>
                            </div>

                            {{-- SK Tarif --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="kode">SK Tarif</label>
                                <div class="col-sm-10">
                                    <select class="form-select form-control js-select-2" id="tarif" name="tarif"
                                        data-url="{{ route('master-data.penjamin.select_tarif') }}"
                                        data-placeholder="---- Pilih Salah Satu ----" required></select>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label" for="nama">Kelas 1</label>
                                <div class="col-sm-10">
                                    <input class="form-control form-control-sm" name="kelas1" type="text"
                                        placeholder="Harga Kelas 1..." required>
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

                            {{-- input --}}


                        </div>
                        </a>
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
