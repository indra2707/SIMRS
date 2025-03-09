@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Poliklinik</h3>
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
                            <span> Tambah Poli</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_poli" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Kode BPJS</th>
                                            <th class="f-light">Nama Polikinik</th>
                                            <th class="f-light">Kategori</th>
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

    {{-- Modal Form poli --}}
    <div class="modal fade" id="modal-poli" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-poli" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>
                        {{-- Kode --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="kode">Kode BPJS</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Kode Poliklinik BPJS...">
                            </div>
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Nama Poliklinik</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="nama" type="text"
                                    placeholder="Nama Poliklinik..." required>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select form-control select2" name="kategori" required>
                                    <option></option>
                                    <option value="Rawat Jalan">Rawat Jalan</option>
                                    <option value="Rawat Inap">Rawat Inap</option>
                                    <option value="Penunjang Medis">Penunjang Medis</option>
                                    <option value="Farmasi">Farmasi</option>
                                </select>
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


    {{-- Modal Mapping Tindakan --}}
    <div class="modal fade" id="modal-infos" role="dialog" tabindex="-1" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    {{-- Hidden Input --}}
                    <div class="mb-2 row">
                        <input type="hidden" name="id">
                    </div>
                    {{-- Kode --}}
                    <div class="mb-2 row">
                        <label class="col-sm-2 col-form-label" for="kode">Kode BPJS</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control-sm" name="kode" type="text"
                                placeholder="Kode Poliklinik BPJS..." disabled>
                        </div>
                    </div>
                    {{-- Nama --}}
                    <div class="mb-2 row">
                        <label class="col-sm-2 col-form-label" for="nama">Nama Poliklinik</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control-sm" name="nama" type="text"
                                placeholder="Nama Poliklinik..." disabled>
                        </div>
                    </div>
                    {{-- Kategori --}}
                    <div class="mb-2 row">
                        <label class="col-sm-2 col-form-label" for="nama">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control select2" name="kategori" disabled>
                                <option></option>
                                <option value="Rawat Jalan">Rawat Jalan</option>
                                <option value="Rawat Inap">Rawat Inap</option>
                                <option value="Penunjang Medis">Penunjang Medis</option>
                                <option value="Farmasi">Farmasi</option>
                            </select>
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

                    {{-- modal tambah tindakan --}}
                    <div class="col-sm-12 col-xxl-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="info-home-tab" data-bs-toggle="tab"
                                            href="#info-home" role="tab" aria-controls="info-home"
                                            aria-selected="true"><i class="icofont icofont-bed-patient"></i>Tindakan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-info-tab" data-bs-toggle="tab"
                                            href="#info-profile" role="tab" aria-controls="info-profile"
                                            aria-selected="false"><i class="icofont icofont-capsule"></i>Obat & BHMP</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="info-tabContent">
                                    <div class="tab-pane fade show active" id="info-home" role="tabpanel"
                                        aria-labelledby="info-home-tab">
                                        {{-- Add Button --}}
                                        <button class="btn btn-primary add-tindakan">
                                            <span class="fa fa-plus"></span>
                                            <span> Tambah Tindakan</span>
                                        </button>

                                        {{-- Table View --}}
                                        <div class="col-sm-12 col-lg-12 col-xl-12">
                                            <div class="table-responsive signal-table">
                                                <table id="table_tindakan" class="table table-hover"
                                                    data-buttons-class="primary" data-toggle="table">
                                                    <thead class="text-bold text-white text-uppercase text-center">
                                                        <tr>
                                                            <th class="f-light">Nama Tindakan</th>
                                                            <th class="f-light">Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="info-profile" role="tabpanel"
                                        aria-labelledby="profile-info-tab">
                                        {{-- Add Button --}}
                                        <button class="btn btn-primary add-obat">
                                            <span class="fa fa-plus"></span>
                                            <span> Tambah Obat & BMHP</span>
                                        </button>

                                        {{-- Table View --}}
                                        <div class="col-sm-12 col-lg-12 col-xl-12">
                                            <div class="table-responsive signal-table">
                                                <table id="table_obat" class="table table-hover"
                                                    data-buttons-class="primary" data-toggle="table">
                                                    <thead class="text-bold text-white text-uppercase text-center">
                                                        <tr>
                                                            <th class="f-light">Nama Obat & BMHP</th>
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

                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Tindakan --}}
    <div class="modal fade" id="modal-input-tindakan" tabindex="-1" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-tindakan" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id1">
                        </div>
                        {{-- Hidden kode poli --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="kode">
                        </div>
                        {{-- Tindakan --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Tindakan</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="tindakan" type="text"
                                    placeholder="Nama Tindakan..." required>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-tindakan" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Obat & BMHP --}}
    <div class="modal fade" id="modal-input-obat" tabindex="-1" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-obat" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id1">
                        </div>
                        {{-- Hidden kode poli --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="kode">
                        </div>
                        {{-- Obat --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Nama Obat</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="kode_obat" type="text"
                                    placeholder="Nama Obat & BMHP..." required>
                            </div>
                        </div>
                        {{-- Satus --}}
                        <div class="media mb-2">
                            <label class="col-sm-2 col-form-label m-r-10">Status</label>
                            <div class="media-body switch-sm icon-state">
                                <label class="switch">
                                    <input class="form-control" name="status2" type="checkbox" checked>
                                    <span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-btn-obat" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('master-data.poli.script')
@endsection
