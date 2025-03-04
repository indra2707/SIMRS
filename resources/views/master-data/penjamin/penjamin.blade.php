@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Penjamin</h3>
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
                                <table id="table_penjamin" class="table table-hover" data-toggle="table">
                                    <thead class="bg-secondary text-light text-bold text-uppercase text-center">
                                        <tr>
                                            <th scope="col"><b>Kode Penjamin</b></th>
                                            <th scope="col"><b>Kode Penjamin</b></th>
                                            <th scope="col"><b>Nama penjamin</b></th>
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

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-penjamin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-penjamin" novalidate="" autocomplete="off">
                        @csrf
                        <div class="form-group row my-0 g-lg-2 col-md-12">
                            <div class="col-md-6">
                                {{-- Hidden Input --}}
                                <div class="mb-2 row">
                                    <input type="hidden" name="id">
                                </div>

                                {{-- Kode --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode"><b>Kode</b></label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="kode" type="text"
                                            placeholder="Kode penjamin..." required>
                                    </div>
                                </div>

                                {{-- Nama --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama"><b>Nama</b></label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="nama" type="text"
                                            placeholder="Nama penjamin..." required>
                                    </div>
                                </div>

                                {{-- Kategori --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="coa"><b>COA</b></label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control js-select-2" id="coa" name="coa"
                                            data-url="{{ route('master-data.penjamin.select') }}"
                                            data-placeholder="---- Pilih Salah Satu ----" required></select>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="email"><b>Email</b></label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="email" type="email"
                                            placeholder="Email...">
                                    </div>
                                </div>

                                {{-- Satus --}}
                                <div class="media mb-2">
                                    <label class="col-sm-2 col-form-label m-r-10"><b>Status</b></label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch col-sm-4">
                                            <input class="form-control" name="status" type="checkbox" checked>
                                            <span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                {{-- Tarif --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama"><b>Tarif</b></label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control js-select-2" id="tarif" name="tarif"
                                            data-url="{{ route('master-data.penjamin.select_tarif') }}"
                                            data-placeholder="---- Pilih Salah Satu ----" required></select>
                                    </div>
                                </div>

                                {{-- Telpon --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="telpon"><b>No Telpon</b></label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="telpon" type="number"
                                            placeholder="No Telpon...">
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="alamat"><b>Alamat</b></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm" name="alamat" id="" cols="30" rows="3"
                                            placeholder="Alamat Lengkap..."></textarea>
                                    </div>
                                </div>

                                {{-- Margin Obat --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="margin"><b>Margin</b></label>
                                    <div class=" col-sm-10">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control" type="number" name="margin"
                                                placeholder="Margin Obat..." required><span class="input-group-text">%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="kanban-item">
                                <a class="kanban-box p-2" href="#" data-bs-original-title="" title="">
                                    <span class="kanban-title">Diskon Rawat Jalan</span><br><br>
                                    <div class="d-flex">
                                        <div class="m-checkbox-inline">
                                        </div>
                                    </div>

                                    <div class="form-group row my-0 g-lg-2 col-md-12">
                                        <div class="col-md-4">
                                            {{-- Tindakan --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Tindakan</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_tindakan"
                                                            placeholder="Tidakan..." required><span
                                                            class="input-group-text input-group-sm">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- KOnsultasi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Konsultasi</b></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_konsultasi"
                                                            placeholder="Konsultasi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Sewa Alat --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Sewa
                                                        Alat</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_alat"
                                                            placeholder="Sewa Alat..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Obat --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Obat & BMHP
                                                    </b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_obat"
                                                            placeholder="Obat & BMHP..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- OK --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>OK</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_ok"
                                                            placeholder="Kamar Bedah / OK..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Cathlab --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Cathlab</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_cathlab"
                                                            placeholder="Cathlab..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Radiologi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Radiologi</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_radiologi"
                                                            placeholder="Radiologi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- Laboratorium --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Laboratorium</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_lab"
                                                            placeholder="Laboratorium..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Akomodasi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Akomodasi</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_akomodasi"
                                                            placeholder="Akomodasi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Paket --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Paket</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="rj_paket"
                                                            placeholder="Paket..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="kanban-item">
                                <a class="kanban-box p-2" href="#" data-bs-original-title="" title="">
                                    <span class="kanban-title">Diskon Rawat Inap</span><br><br>
                                    <div class="d-flex">
                                        <div class="m-checkbox-inline">
                                        </div>
                                    </div>

                                    <div class="form-group row my-0 g-lg-2 col-md-12">
                                        <div class="col-md-4">
                                            {{-- Tindakan --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Tindakan</b></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_tindakan"
                                                            placeholder="Tindakan..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- KOnsultasi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Konsultasi</b></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_konsultasi"
                                                            placeholder="Konsultasi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Sewa Alat --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Sewa
                                                        Alat</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_alat"
                                                            placeholder="Sewa Alat..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Obat & BMHP --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Obat &
                                                        BMHP</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_obat"
                                                            placeholder="Obat & BHMP..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- OK --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>OK</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_ok"
                                                            placeholder="Kamar Bedah / OK..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Cathlab --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Cathlab</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_cathlab"
                                                            placeholder="Cathlab..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Radiologi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Radiologi</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_radiologi"
                                                            placeholder="Radiologi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- Laboratorium --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Laboratorium</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_lab"
                                                            placeholder="Laboratorium..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Akomodasi --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="nama"><b>Akomodasi</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_akomodasi"
                                                            placeholder="Akomodasi..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Paket --}}
                                            <div class="mb-2 row">
                                                <label class="col-sm-4 col-form-label" for="nama"><b>Paket</b></label>
                                                <div class=" col-sm-7">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control" type="number" name="ri_paket"
                                                            placeholder="Paket..." required><span
                                                            class="input-group-text">%
                                                        </span>
                                                    </div>
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

@endsection


@section('script')
    @include('master-data.penjamin.script')
@endsection
