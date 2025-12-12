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

        /* .select2-container--bootstrap-5 .select2-selection__rendered {
                        font-size: 18px;
                    }

                    .select2-container--bootstrap-5 .select2-selection__placeholder {
                        font-size: 15px !important;
                        opacity: 1;
                    } */
    </style>

@endsection

@section('breadcrumb-title')
    <h3>Surat Perjalanan Dinas</h3>
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
                            <span> Tambah SPD</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_spd" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Nama Ruangan</th>
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

    {{-- Modal Form spd --}}
    <div class="modal fade" id="modal-spd" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-spd" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">

                        <!-- Nomor Surat -->
                        <label for="surat" class="col-form-label col-sm-2">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" id="surat" name="surat" class="form-control" placeholder="No Surat..." required>
                        </div>

                        <!-- Pegawai -->
                        <label for="pegawai" class="col-form-label col-sm-2">Pegawai</label>
                        <div class="col-sm-10">
                            <select class="form-select select2" name="pegawai" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- Pelaksanaan -->
                        <label for="pelaksanaan" class="col-form-label col-sm-2">Pelaksanaan</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control select2" name="pelaksanaan" required>
                                <option></option>
                                <option value="PD-DN">PD-DN</option>
                                <option value="PD-LN">PD-LN</option>
                                <option value="SIJ">SIJ</option>
                                <option value="Mutasi">Mutasi</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>

                        <!-- Form -->
                        <label for="asal" class="col-form-label col-sm-2">Kota Asal</label>
                        <div class="col-sm-4">
                            <select class="form-select select2" name="asal" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- To -->
                        <label for="tujuan" class="col-form-label col-sm-2">Kota Tujuan</label>
                        <div class="col-sm-4">
                            <select class="form-select select2" name="tujuan" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- Antara Tanggal -->
                        <label for="form_end_date" class="col-form-label col-sm-2">Antara Tanggal</label>
                        <div class="col-sm-4">
                            <input type="text" name="form_end_date" id="form_end_date"
                                class="form-control js-daterangepicker digits" placeholder="dd/mm/yyyy - dd/mm/yyyy"
                                data-language="en" aria-label="Date" required />
                        </div>

                        <!-- Tanggal Masuk -->
                        <label for="tanggal" class="col-form-label col-sm-2">Tanggal Masuk</label>
                        <div class="col-sm-4">
                            <input type="text" name="tanggal" id="tanggal" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- Transportasi -->
                        <label for="drive" class="col-form-label col-sm-2">Transportasi</label>
                        <div class="col-sm-4">
                            <select class="form-select form-control select2" name="pelaksanaan" required>
                                <option></option>
                                <option value="Pesawat">Pesawat</option>
                                <option value="Kereta">Kereta</option>
                                <option value="Kapal Laut">Kapal Laut</option>
                                <option value="Bus">Bus</option>
                                <option value="Mobil">Mobil</option>
                            </select>
                        </div>

                        <!-- Biaya Ditanggung Oleh -->
                        <label class="col-form-label col-sm-2">Biaya Ditanggung Oleh</label>
                        <div class="col-sm-4">
                            <div class="btn-group biaya-group" role="group">
                                <button type="button" class="btn btn-outline-primary active"
                                    data-value="Perusahaan">Perusahaan</button>
                                <button type="button" class="btn btn-outline-primary" data-value="Pribadi">Pribadi</button>
                            </div>
                        </div>
                        <input type="hidden" name="biaya_ditanggung" id="biaya_ditanggung">

                        <!-- Hak Cuti -->
                        <label for="hakcuti" class="col-form-label col-sm-2">Hak Cuti</label>
                        <div class="col-sm-4">
                            <input type="text" name="hakcuti" id="hakcuti" class="form-control" placeholder="Hak Cuti..." />
                        </div>

                        <!-- Cuti Lalu -->
                        <label for="cutilalu" class="col-form-label col-sm-2">Cuti Lalu</label>
                        <div class="col-sm-4">
                            <input type="text" name="cutilalu" id="cutilalu" class="form-control"
                                placeholder="Cuti Lalu..." />
                        </div>

                        <!-- Jatuh Tempo Cuti -->
                        <label for="jatuh_tempo" class="col-form-label col-sm-2">Jatuh Tempo Cuti</label>
                        <div class="col-sm-4">
                            <input type="text" name="jatuh_tempo" id="jatuh_tempo" class="form-control"
                                placeholder="Jatuh Tempo Cuti..." />
                        </div>

                        <!-- Panjar Cuti -->
                        <label for="panjar_cuti" class="col-form-label col-sm-2">Panjar Cuti</label>
                        <div class="col-sm-4">
                            <input type="text" name="panjar_cuti" id="panjar_cuti" class="form-control"
                                placeholder="Panjar Cuti..." />
                        </div>

                        <!-- Keterangan -->
                        <label for="information" class="col-form-label col-sm-2">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea name="keterangan" id="information" class="form-control" style="resize: none;" rows="3"
                                placeholder="Keterangan..." required></textarea>
                        </div>

                        <!-- Acc -->
                        <label for="accepted" class="col-form-label col-sm-2">Menyetujui</label>
                        <div class="col-sm-6 mb-2">
                            <select class="form-select select2" name="menyetujui" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- More Employee -->
                        <div class="col-sm-4">
                            <select class="form-select form-control select2" name="pelaksanaan" required
                                onchange="showMoreOption('hidden_div', this)">
                                <option></option>
                                <option value="Satu Orang">Satu Orang</option>
                                <option value="Lebih Satu Orang">Lebih Satu Orang</option>
                            </select>
                        </div>

                        <!-- More Option Table -->
                        <div id="hidden_div" style="display:none;">
                            <div id="toolbar">
                                <button id="add_row_employee" type="button" class="btn btn-primary btn-sm"><i
                                        class="fa fa-user-plus"></i> Tambah Orang</button>
                            </div>
                            <table id="tabled_employee" class="table table-striped table-bordered" data-toggle="table"
                                data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false" data-width='2%' data-align="center"
                                            class="f-light">ID</th>
                                        <th data-field="field_id" data-visible="false" class="f-light">ID_EMPLOYEE</th>
                                        <th data-field="field_nip" data-align="center" class="f-light">NIP</th>
                                        <th data-field="field_employee" class="f-light">Nama</th>
                                        <th data-width='2%' data-formatter="actionFormatter" data-align="center"
                                            class="f-light">#</th>
                                    </tr>
                                </thead>
                            </table>
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
    @include('sdm.spd.script')
@endsection