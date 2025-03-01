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
                        <button class="btn btn-secondary add-btn">
                            <span class="fa fa-plus"></span>
                            <span> Tambah</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_poli" class="table table-hover" data-toggle="table">
                                    <thead class="bg-secondary text-light text-bold text-uppercase text-center">
                                        <tr>
                                            {{-- <th scope="col">No</th> --}}
                                            <th scope="col"><b>Kode BPJS</b></th>
                                            <th scope="col"><b>Nama Polikinik</b></th>
                                            <th scope="col"><b>Kategori</b></th>
                                            <th scope="col"><b>Status</b></th>
                                            <th scope="col"><b>Action</b></th>
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
                            <label class="col-sm-2 col-form-label" for="kode"><b>Kode BPJS</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Kode Poliklinik BPJS...">
                            </div>
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Nama Poliklinik</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="nama" type="text"
                                    placeholder="Nama Poliklinik..." required>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Kategori</b></label>
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
                            <label class="col-sm-2 col-form-label m-r-10"><b>Status</b></label>
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
    <div class="modal fade" id="modal-kelompok" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
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
                            <label class="col-sm-2 col-form-label" for="kode"><b>Kode BPJS</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Kode Poliklinik BPJS..." disabled>
                            </div>
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Nama Poliklinik</b></label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="nama" type="text"
                                    placeholder="Nama Poliklinik..." disabled>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama"><b>Kategori</b></label>
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
                            <label class="col-sm-2 col-form-label m-r-10"><b>Status</b></label>
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
                                    <li class="nav-item"><a class="nav-link active" id="info-home-tab"
                                            data-bs-toggle="tab" href="#info-home" role="tab"
                                            aria-controls="info-home" aria-selected="true"><i
                                                class="icofont icofont-bed-patient"></i>Tindakan</a></li>
                                    <li class="nav-item"><a class="nav-link" id="profile-info-tab" data-bs-toggle="tab"
                                            href="#info-profile" role="tab" aria-controls="info-profile"
                                            aria-selected="false"><i class="icofont icofont-capsule"></i>Obat & BHMP</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="info-tabContent">
                                    <div class="tab-pane fade show active" id="info-home" role="tabpanel"
                                        aria-labelledby="info-home-tab">
                                        {{-- Add Button --}}
                                            <button class="btn btn-secondary add-tindakan">
                                                <span class="fa fa-plus"></span>
                                                <span> Tambah Tindakan</span>
                                            </button>

                                        {{-- Table View --}}
                                        <div class="col-sm-12 col-lg-12 col-xl-12">
                                            <div class="table-responsive signal-table">
                                                <table id="table_tindakan" class="table table-hover" data-toggle="table">
                                                    {{-- <thead
                                                        class="bg-secondary text-light text-bold text-uppercase text-center">
                                                        <tr>
                                                            <th scope="col"><b>Nama Tindakan</b></th>
                                                            <th scope="col"><b>Status</b></th>
                                                            <th scope="col"><b>Action</b></th>
                                                        </tr>
                                                    </thead> --}}
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Modal Form Input Tindakan --}}
                                        <div class="modal fade" id="modal-input-tindakan" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true"
                                            data-bs-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Title</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-wizard form-tindakan" novalidate=""
                                                            autocomplete="off">
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
                                                                <label class="col-sm-2 col-form-label"
                                                                    for="nama"><b>Tindakan</b></label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control form-control-sm"
                                                                        name="tindakan" type="text"
                                                                        placeholder="Nama Tindakan..." required>
                                                                </div>
                                                            </div>
                                                            {{-- Satus --}}
                                                            <div class="media mb-2">
                                                                <label
                                                                    class="col-sm-2 col-form-label m-r-10"><b>Status</b></label>
                                                                <div class="media-body switch-sm icon-state">
                                                                    <label class="switch">
                                                                        <input class="form-control" name="status"
                                                                            type="checkbox" checked>
                                                                        <span class="switch-state"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-danger" type="button"
                                                            data-bs-dismiss="modal">
                                                            <span class="fa fa-times"></span> Batal</button>
                                                        <button class="btn btn-primary save-btn-tindakan" type="button"><span
                                                                class="fa fa-check"></span>
                                                            Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="info-profile" role="tabpanel"
                                        aria-labelledby="profile-info-tab">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                            with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                            recently with desktop publishing software like Aldus PageMaker including
                                            versions of Lorem Ipsum</p>
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

@endsection


@section('script')
    @include('master-data.poli.script')
@endsection
