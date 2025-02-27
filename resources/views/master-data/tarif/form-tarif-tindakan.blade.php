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
        <div class="col-sm-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        {{-- Colum 1 --}}
                        <div class="col-md-6 col-lg-6 col-xl-6 row">
                            <div class="col-md-4">
                                <label class="col-form-label" for="kode">Kode</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Kode..." required>
                            </div>
                            <div class="col-md-8">
                                <label class="col-form-label" for="kode">Tindakan</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Kelompok Tindakan</label>
                                <select class="form-select form-select-sm js-select-2" data-placeholder="---Pilih Tarif Tindakan---" id="kelompok_tindakan" data-url="{{ route('get-select-tarif-tindakan') }}" data-value="1" data-dropdownParent="" required></select>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Tipe</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Kategori Layanan</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Grup Tindakan</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label" for="kode">SK Tarif Aktif</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                        </div>
                        {{-- Colum 2 --}}
                        <div class="col-md-6 col-lg-6 col-xl-6 row">
                            <div class="col-md-4">
                                <label class="col-form-label" for="kode">Status Cito</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="col-form-label" for="kode">Tindakan</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Status Tindakan</label>
                                <input class="form-control form-control-sm" name="kode" type="text"
                                    placeholder="Tindakan..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="kode">Jasa Medis Flat</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="kanban-item">
                                <a class="kanban-box p-2" href="#" data-bs-original-title="" title="">
                                    <span class="kanban-title">Pilih salah satu</span>
                                    <div class="d-flex">
                                        <div class="m-checkbox-inline">
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input" id="inline-1" name="options"
                                                    type="checkbox" data-bs-original-title="" title="">
                                                <label class="form-check-label" for="inline-1">Status Operasi</label>
                                            </div>
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input" id="inline-2" name="options"
                                                    type="checkbox" data-bs-original-title="" title="">
                                                <label class="form-check-label" for="inline-2">Status Cathlab</label>
                                            </div>
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input" id="inline-3" type="checkbox"
                                                    data-bs-original-title="" title="">
                                                <label class="form-check-label" for="inline-3">Tindakan Anestesi</label>
                                            </div>
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input" id="inline-4" type="checkbox"
                                                    data-bs-original-title="" title="">
                                                <label class="form-check-label" for="inline-4">Tindakan Asisten
                                                    Opr</label>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>



                            {{-- <div class="col-md-6 bg-light">
                                <label class="col-form-label" for="kode">Status Cito</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 bg-light">
                                <label class="col-form-label" for="kode">Status Cito</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 bg-light">
                                <label class="col-form-label" for="kode">Status Cito</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 bg-light">
                                <label class="col-form-label" for="kode">Status Cito</label>
                                <div class="media-body switch-sm icon-state">
                                    <label class="switch">
                                        <input class="form-control" name="status" type="checkbox" checked>
                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-5 text-center">
                                <button type="butoon" class="btn btn-secondary"><i class="icon-save"></i>
                                    Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Button Simpan --}}
                <div class="card-body pt-3 campaign-table row">
                    <div class="col-md-5">
                        <h6>Riwayat SK Tarif</h6>
                        <div class="recent-table table-responsive currency-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="f-light">SK</th>
                                        <th class="f-light">Tarif</th>
                                        <th class="f-light">Start Date</th>
                                        <th class="f-light">End Date</th>
                                        <th class="f-light">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Jane Cooper</td>
                                        <td>UK</td>
                                        <td>$9,786</td>
                                        <td>$9,786</td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="table-responsive recent-table transaction-table">
                            <h6>Tarif Jasa Medis Operasi</h6>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="f-light">Kelas</th>
                                        <th class="f-light">Tarif RS</th>
                                        <th class="f-light">Operator</th>
                                        <th class="f-light">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kelas III</td>
                                        <td>55,000</td>
                                        <td>Jane Cooper</td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('master-data.tarif.script')
@endsection
