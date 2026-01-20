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
    <h3>Slip Gaji</h3>
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
                            <span> Tambah Slip Gaji</span>
                        </button>

                        <button class="btn btn-danger hapus-all-btn">
                            <span class="fa fa-trash"></span>
                            <span> Hapus All</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_gaji" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nomor Pegawai</th>
                                            <th class="f-light">Bulan</th>
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

    {{-- Modal Form gaji --}}
    <div class="modal fade" id="modal-gaji" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Input Gaji</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-gaji" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id">
                        <!-- Bulan -->
                        <label for="bulan" class="col-form-label col-sm-2">Bulan</label>
                        <div class="col-sm-10">
                            <input type="text" name="bulan" id="bulan" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- Nomor Upload -->
                        <label for="file_zip" class="col-form-label col-sm-2">Upload</label>
                        <div class="col-sm-10">
                            <input type="file" name="file_zip" id="file_zip" class="form-control" placeholder="Upload"/>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary save-btn">Simpan</button>
                </div>

            </div>
        </div>
    </div>


@endsection


@section('script')
    @include('sdm.gaji.script')
@endsection