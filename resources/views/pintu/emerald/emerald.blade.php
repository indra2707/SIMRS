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

@section('breadcrumb-title')
    <h3>Emerald Access Door Management</h3>
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
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                            <!-- Kiri: Tombol & Filter -->
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary add-btn">
                                    <span class="fa fa-plus"></span>
                                    <span> Tambah Kartu</span>
                                </button>

                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-success sinkronisasi-btn">
                                        <span class="fa fa-spinner"></span>
                                        <span> Sinkronisasi</span>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-danger open-btn">
                                        <span class="fa fa-key"></span>
                                        <span> Buka Pintu</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Table View --}}
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive signal-table">
                                    <table id="table_emerald" class="table table-hover" data-buttons-class="primary"
                                        data-toggle="table">
                                        <thead class="text-bold text-white text-uppercase text-center">
                                            <tr>
                                                <th class="f-light">No</th>
                                                <th class="f-light">User ID</th>
                                                <th class="f-light">UID</th>
                                                <th class="f-light">Nama</th>
                                                <th class="f-light">Card Number</th>
                                                <th class="f-light">Role</th>
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

        {{-- Modal Form --}}
        <div class="modal fade" id="modal-emerald" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Title</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form class="row g-2 form-emerald" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="">

                            <!-- User ID  -->
                            <label for="userid" class="col-form-label col-sm-1">User ID</label>
                            <div class="col-sm-5">
                                <input class="form-control form-control" name="userid" type="text" placeholder="User ID..."
                                    required maxlength="8">
                            </div>

                            <!-- Nama  -->
                            <label for="name" class="col-form-label col-sm-1">Nama</label>
                            <div class="col-sm-5">
                                <input class="form-control form-control" name="name" type="text" placeholder="Nama..."
                                    required>
                            </div>

                            <!-- Card Number  -->
                            <label for="card_number" class="col-form-label col-sm-1">No Card</label>
                            <div class="col-sm-5">
                                <input class="form-control form-control" name="card_number" type="text"
                                    placeholder="Card Number..." required maxlength="10">
                            </div>

                            <!-- Role  -->
                            <label for="role" class="col-form-label col-sm-1">Role</label>
                            <div class="col-sm-5">
                                <select class="form-select form-control select2" name="role"
                                    data-placeholder="---- Pilih Salah Satu ----" required>
                                    <option value="0">User</option>
                                    <option value="14">Admin</option>

                                </select>
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
    @include('pintu.emerald.script')
@endsection