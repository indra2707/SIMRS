@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .select2-fixed {
            width: 210px;
        }

        .select2-fixed .select2-container {
            width: 100% !important;
        }

        /* tinggi select tetap */
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>

@endsection

@section(section: 'breadcrumb-title')
    <h3>Kartu Jaga</h3>
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
                            <span> Tambah Kartu Jaga</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_kartu_jaga" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">#</th>
                                            <th class="f-light">Nama Pasien</th>
                                            <th class="f-light">Nama Yang Ambil Kartu Jaga</th>
                                            <th class="f-light">No HP</th>
                                            <th class="f-light">Ruangan</th>
                                            <th class="f-light">No Kartu Jaga</th>
                                            <th class="f-light">Deposit</th>
                                            <th class="f-light">Catatan</th>
                                            <th class="f-light">Created By</th>
                                            <th class="f-light">Updated By</th>
                                            <th class="f-light">Created At</th>
                                            <th class="f-light">Updated At</th>
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

    {{-- Modal Kartu Jaga --}}
    <div class="modal fade" id="modal-kartu-jaga" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-kartu-jaga" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <!-- Nama Pasien  -->
                        <label for="nama_pasien" class="col-form-label col-sm-1">Nama Pasien</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="nama_pasien" type="text"
                                placeholder="Nama Pasien..." required>
                        </div>

                        <!-- Nama Yang Ambil Kartu Jaga  -->
                        <label for="nama" class="col-form-label col-sm-1">Nama</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control" name="nama" type="text"
                                placeholder="Nama Yang Ambil Kartu Jaga..." required>
                        </div>

                        <!-- No HP  -->
                        <label for="no_hp" class="col-form-label col-sm-1">No HP</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control phone-number" name="no_hp" type="text"
                                placeholder="+62..." required>
                        </div>

                        <!-- Ruangan  -->
                        <label for="ruangan" class="col-form-label col-sm-1">Ruangan</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" name="ruangan"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option value="Shapphire">Shapphire</option>
                                <option value="Ruby">Ruby</option>
                                <option value="Emerald">Emerald</option>
                            </select>
                        </div>

                        <!-- No Kartu jaga  -->
                        <label for="no_kartu" class="col-form-label col-sm-1">No Kartu</label>
                        <div class="col-sm-5">
                            <select class="form-select form-control select2" name="no_kartu"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                            </select>
                        </div>

                        <!-- Deposit  -->
                        <label for="deposit" class="col-form-label col-sm-1">Deposit</label>
                        <div class="col-sm-5">
                            <input class="form-control form-control rupiah-number" name="deposit" type="text"
                                placeholder="Rp..." required>
                        </div>

                        <!-- Catatan  -->
                        <label for="catatan" class="col-form-label col-sm-1">Catatan</label>
                        <div class="col-sm-11">
                            <input class="form-control form-control" name="catatan" type="text" placeholder="Catatan...">
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
    </div>
@endsection


@section('script')
    @include('pintu.kartu_jaga.script')
@endsection