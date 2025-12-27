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
    <h3>Bank</h3>
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
                            <span> Tambah Bank</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_bank" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nama Bank</th>
                                            <th class="f-light">Singkatan</th>
                                            <th class="f-light">Jenis Bank</th>
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

    {{-- Modal Form Bank --}}
    <div class="modal fade" id="modal-bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-bank" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>
                        
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Nama Bank</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="nama_bank" type="text"
                                    placeholder="Nama Bank..." required>
                            </div>
                        </div>

                        {{-- Singkatan --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="singkatan">Singkatan</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="singkatan" type="text"
                                    placeholder="Singkatan..." required>
                            </div>
                        </div>

                        {{-- Jenis Bank --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="jenis_bank">Jenis Bank</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="jenis_bank" required>
                                    <option value=""></option>
                                    <option value="BUMN">BUMN</option>
                                    <option value="Swasta">Swasta</option>
                                </select>
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

@endsection


@section('script')
    @include('master-data.bank.script')
@endsection