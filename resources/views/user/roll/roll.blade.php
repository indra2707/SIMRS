@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Rolls</h3>
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
                            <span> Tambah Roll</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_roll" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Roll</th>
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

    {{-- Modal Form roll --}}
    <div class="modal fade" id="modal-roll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-roll" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-2 row">
                            <input type="hidden" name="id">
                        </div>
                        {{-- Nama --}}
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control" name="nama" type="text" placeholder="Nama..."
                                    required>
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

    {{-- Style --}}
    <style>
        .tree ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .tree label {
            cursor: pointer;
            display: inline-block;
            user-select: none;
        }

        .tree input[type="checkbox"] {
            margin-right: 6px;
            accent-color: #25426aff;
            /* kuning */
        }

        .tree li {
            margin: 3px 20px;
        }

        .tree .nested {
            display: none;
        }

        .tree .active {
            display: block;
        }

        /* === Ikon caret (segitiga) === */
        .caret::before {
            content: "\25B6";
            /* ▶ */
            display: inline-block;
            margin-right: 6px;
            transition: transform 0.2s;
        }

        .tree .caret {
            cursor: pointer;
        }

        .tree label input[type="checkbox"] {
            cursor: pointer;
        }

        /* Hanya panah yang diputar, bukan teksnya */
        .caret.caret-down::before {
            transform: rotate(90deg);
        }

        .tree input[type="checkbox"]:indeterminate {
            background-color: #ffc107;
            /* kuning */
            border-color: #ffc107;
        }
    </style>

    {{-- Modal Form Access Menu --}}
    <div class="modal fade" id="modal-access" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-access" novalidate autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">

                        <div class="tree">
                            <ul>
                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>Dashboard</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Dashboard_Helpdesk"> Dashboard Helpdesk</label>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>Manajemen User</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="User"> User</label>
                                        </li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Roll"> Role</label>
                                        </li>
                                    </ul>
                                </li>

                                  <li>
                                    <label class="caret">
                                        <input type="checkbox" name="permissions[]" value="Helpdesk" class="parent"><b>
                                            Master Pintu</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Emerald">
                                                Emerald</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Ruby">
                                                Ruby</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Sapphire">
                                                Sapphire</label></li>
                                    </ul>
                                </li>

                                 <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>Kartu Jaga</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Kartu Jaga"> Kartu Jaga</label>
                                        </li>
                                    </ul>
                                </li>

                                 <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>RS Online</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="RS Online"> RS Online</label>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>Master Data</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Asset & Inventaris">
                                                Asset & Inventaris</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Lokasi">
                                                Lokasi</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Kondisi Aset"> Kondisi
                                                Aset</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Kelompok Asset">
                                                Kelompok Asset</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Vendor">
                                                Vendor</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Kota">
                                                Kota</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Biaya SPD">
                                                Biaya SPD</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Bank">
                                                Bank</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Fungsi">
                                                Fungsi</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Unit">
                                                Unit</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="SK Struktur">
                                                SK Struktur</label></li>
                                         <li><label><input type="checkbox" name="permissions[]" value="Jenis Kontrak">
                                                Jenis Kontrak</label></li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"> <b>Mutasi</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]"
                                                    value="Mutasi Asset & Inventaris"> Mutasi Asset & Inventaris</label>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"><b> Kalibrasi</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]"
                                                    value="Kalibrasi Alat Kesehatan"> Kalibrasi Alat Kesehatan</label></li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" name="permissions[]" value="Helpdesk" class="parent"><b>
                                            Helpdesk</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Helpdesk.List">
                                                Helpdesk</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Helpdesk.Approval">
                                                Approval</label></li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"><b> SDM</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Pegawai">
                                                Pegawai</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="SPD"> Surat Perjalanan
                                                Dinas</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Rincian SPD"> Rincian
                                                SPD</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Slip Gaji"> Slip Gaji
                                            </label></li>
                                    </ul>
                                </li>

                                <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"><b> Logistik</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Permintaan"> Permintaan</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Tembusan"> Tembusan</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Disposisi"> Disposisi</label></li>
                                    </ul>
                                </li>

                                 <li>
                                    <label class="caret">
                                        <input type="checkbox" name="permissions[]" value="Legal" class="parent"><b>
                                            Legal</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="PKS">
                                                PKS</label></li>
                                        <li><label><input type="checkbox" name="permissions[]" value="Perizinan">
                                                Perizinan</label></li>
                                    </ul>
                                </li>

                                 <li>
                                    <label class="caret">
                                        <input type="checkbox" class="parent"><b> Slip Gaji</b>
                                    </label>
                                    <ul class="nested">
                                        <li><label><input type="checkbox" name="permissions[]" value="Gaji"> Gaji</label></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                        <span class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary edit-access" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('user.roll.script')
@endsection