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
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form Tarif --}}
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
                                        <a class="btn btn-primary add-btn-harga" href="#" type="button">
                                            <span class="fa fa-plus"></span>
                                            <span> Tambah Tarif</span>
                                        </a>
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
                                <label class="col-sm-1 col-form-label" for="kode">SK Tarif</label>
                                <div class="col-sm-11">
                                    <select class="form-select form-control js-select-2" id="tarif" name="tarif"
                                        data-url="{{ route('master-data.penjamin.select_tarif') }}"
                                        data-placeholder="---- Pilih Salah Satu ----" required></select>
                                </div>
                            </div>
                        </div>

                            <div class="form-group row my-0 g-lg-2 col-md-12">
                                <div class="col-md-6">
                                    {{-- kelas 1 --}}
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">Kelas 1</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="kelas1" type="text"
                                                placeholder="Harga Kelas 1..." required>
                                        </div>
                                    </div>

                                      {{-- kelas 2 --}}
                                      <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">Kelas 2</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" id="rupiah" name="kelas2" type="text"
                                                placeholder="Harga Kelas 2..." required>
                                        </div>
                                    </div>

                                      {{-- kelas 3 --}}
                                      <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">Kelas 3</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="kelas3" type="text"
                                                placeholder="Harga Kelas 3..." required>
                                        </div>
                                    </div>

                                    {{-- isolasi --}}
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">Isolasi</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="isolasi" type="text"
                                                placeholder="Harga isolasi..." required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {{-- Intensif --}}
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">Intensif</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="intensif" type="text"
                                                placeholder="Harga Intensif..." required>
                                        </div>
                                    </div>

                                    {{-- VIP --}}
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">VIP</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="vip" type="text"
                                                placeholder="Harga vip..." required>
                                        </div>
                                    </div>

                                    {{-- VVIP --}}
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="nama">VVIP</label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm rupiah-number" name="vvip" type="text"
                                                placeholder="Harga vvip..." required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-harga" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')
    @include('tarif.tindakan.script')
@endsection
