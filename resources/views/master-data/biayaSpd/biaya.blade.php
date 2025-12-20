@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Biaya-SPD</h3>
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
                            <span> Tambah Data</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_biaya" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Nama</th>
                                            <th class="f-light">Harga Utama</th>
                                            <th class="f-light">Harga Madya</th>
                                            <th class="f-light">Harga Biasa</th>
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

    {{-- Modal Form Biaya --}}
    <div class="modal fade" id="modal-biaya" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-biaya" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <!-- Biaya  -->
                        <label for="nama" class="col-form-label col-sm-1">Nama Biaya</label>
                        <div class="col-sm-11">
                            <input class="form-control form-control" name="nama" type="text" placeholder="Nama Biaya..."
                                required>
                        </div>

                        <!-- harga Utama -->
                        <label for="harga_utama" class="col-form-label col-sm-1">Harga utama</label>
                        <div class="col-sm-3">
                            <input id="harga_utama" class="form-control rupiah-number" name="harga_utama" type="text"
                                placeholder="Rp..." required>
                        </div>

                        <!-- harga Utama -->
                        <label for="harga_madya" class="col-form-label col-sm-1">Harga Madya</label>
                        <div class="col-sm-3">
                            <input id="harga_madya" class="form-control rupiah-number" name="harga_madya" type="text"
                                placeholder="Rp..." required>
                        </div>

                        <!-- harga Utama -->
                        <label for="harga_biasa" class="col-form-label col-sm-1">Harga Biasa</label>
                        <div class="col-sm-3">
                            <input id="harga_biasa" class="form-control rupiah-number" name="harga_biasa" type="text"
                                placeholder="Rp..." required>
                        </div>

                        <!-- Status -->
                        <label for="status" class="col-form-label col-sm-1">Status</label>
                        <div class="col-sm-3 switch-sm icon-state">
                            <label class="switch">
                                <input class="form-control" name="status" type="checkbox" checked>
                                <span class="switch-state"></span>
                            </label>
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
    @include('master-data.biayaSpd.script')
@endsection