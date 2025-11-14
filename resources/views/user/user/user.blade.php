@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Users</h3>
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
                            <span> Tambah User</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_user" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Nama Lengkap</th>
                                            <th class="f-light">Username</th>
                                            <th class="f-light">Password</th>
                                            <th class="f-light">Email</th>
                                            <th class="f-light">Role</th>
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

    {{-- Modal Form User --}}
    <div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-user" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama_lengkap">Nama</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="nama_lengkap" type="text"
                                    placeholder="Nama..." required>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="username">Username</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="username" type="text"
                                    placeholder="Username..." required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-2 row form-group">
                            <label class="col-sm-2 col-form-label" for="password">Password</label>
                            <div class="col-sm-10 position-relative">
                                <input id="password" class="form-control" type="password" name="password"
                                    placeholder="*********" required>
                                <i class="fa fa-eye fa-lg position-absolute top-50 end-0 translate-middle-y me-4 text-secondary cursor-pointer"
                                    id="togglePassword"></i>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="email" type="email" placeholder="Email..."
                                    required>
                            </div>
                        </div>

                        {{-- kode Roll --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="role">Roll</label>
                            <div class="col-sm-10">
                                <select class="form-select form-control" id="role" name="role"
                                    data-placeholder="---- Pilih Salah Satu ----" required></select>
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

@endsection


@section('script')
    @include('user.user.script')
@endsection