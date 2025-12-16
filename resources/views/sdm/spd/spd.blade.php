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
                                            <th class="f-light">No</th>
                                            <th class="f-light">Nomor Surat</th>
                                            <th class="f-light">Nama Pegawai</th>
                                            <th class="f-light">Pelaksanaan</th>
                                            <th class="f-light">Kota Asal</th>
                                            <th class="f-light">Kota Tujuan</th>
                                            <th class="f-light">Pengikut</th>
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
                        <label for="no_surat" class="col-form-label col-sm-2">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" id="no_surat" name="no_surat" class="form-control"
                                placeholder="Nomor Surat..." required>
                        </div>

                        <!-- Pegawai -->
                        <label for="id_pegawai" class="col-form-label col-sm-2">Pegawai</label>
                        <div class="col-sm-10">
                            <select class="form-select select2" name="id_pegawai"
                                data-placeholder="---- Pilih Salah Satu ----" required>
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
                        <label for="id_kota1" class="col-form-label col-sm-2">Kota Asal</label>
                        <div class="col-sm-4">
                            <select class="form-select select2" name="id_kota1"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- To -->
                        <label for="id_kota2" class="col-form-label col-sm-2">Kota Tujuan</label>
                        <div class="col-sm-4">
                            <select class="form-select select2" name="id_kota2"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option></option>
                            </select>
                        </div>

                        <!-- Antara Tanggal -->
                        <label class="col-form-label col-sm-2">Antara Tanggal</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control js-daterangepicker digits"
                                placeholder="dd/mm/yyyy - dd/mm/yyyy" name="tgl" data-language="en" aria-label="Date" required />
                        </div>
                        <input type="hidden" name="tgl_awal" id="tgl_awal">
                        <input type="hidden" name="tgl_akhir" id="tgl_akhir">

                        <!-- Tanggal Masuk -->
                        <label for="tgl_masuk" class="col-form-label col-sm-2">Tanggal Masuk</label>
                        <div class="col-sm-4">
                            <input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- Transportasi -->
                        <label for="kendaraan" class="col-form-label col-sm-2">Transportasi</label>
                        <div class="col-sm-4">
                            <select class="form-select form-control select2" name="kendaraan" required>
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
                                <button type="button" class="btn btn-outline-primary active" data-value="Perusahaan">
                                    Perusahaan
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-value="Pribadi">
                                    Pribadi
                                </button>
                            </div>

                            <!-- nilai yang dikirim ke server -->
                            <input type="hidden" name="ditanggung" id="ditanggung" value="Perusahaan">
                        </div>


                        <!-- Hak Cuti -->
                        <label for="hak_cuti" class="col-form-label col-sm-2">Hak Cuti</label>
                        <div class="col-sm-4">
                            <input type="text" name="hak_cuti" id="hak_cuti" class="form-control" placeholder="Hak Cuti..." />
                        </div>

                        <!-- Cuti Lalu -->
                        <label for="cuti_lalu" class="col-form-label col-sm-2">Cuti Lalu</label>
                        <div class="col-sm-4">
                            <input type="text" name="cuti_lalu" id="cuti_lalu" class="form-control"
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
                        <label for="id_pimpinan" class="col-form-label col-sm-2">Menyetujui</label>
                        <div class="col-sm-6 mb-2">
                            <select class="form-select select2" name="id_pimpinan" data-placeholder="-- Pilih Salah Satu -- "
                                required>
                                <option></option>
                            </select>
                        </div>

                        <!-- More Employee -->
                        <div class="col-sm-4">
                            <select class="form-select form-control select2" name="pengikut1" required
                                onchange="showMoreOption('hidden_div', this)">
                                <option></option>
                                <option value="0">Satu Orang</option>
                                <option value="1">Lebih Satu Orang</option>
                            </select>
                        </div>

                        <!-- More Option Table -->
                        <div id="hidden_div" class="d-none">
                            <div id="toolbar">
                                <button type="button" class="btn btn-primary btn-sm add-pegawai"><i
                                        class="fa fa-user-plus"></i> Tambah Pegawai</button>
                            </div>
                            <table id="table_employee" class="table table-striped table-bordered" data-toggle="table"
                                data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false" data-width='2%' data-align="center"
                                            class="f-light">ID</th>
                                        <th data-field="field_id" data-visible="false" class="f-light">ID_EMPLOYEE</th>
                                        <th data-width='200%' data-field="field_nip" data-align="center" class="f-light">NIP
                                        </th>
                                        <th data-field="field_employee" class="f-light">Nama</th>
                                        <th data-width='100%' data-formatter="actionFormatter" data-align="center"
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


    {{-- Modal Form Pegawai --}}
    <div class="modal" id="modal-pegawai" tabindex="-1" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-pengikut">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id-pengikut" id="id-pengikut" />

                    <div class="form-group row my-0 g-lg-3">
                        <!-- Nama Pegawai -->
                        <label for="pengikut" class="col-form-label col-sm-2">Nama Pegawai</label>
                        <div class="col-sm-10">
                            <select id="pengikut" class="form-select select2" name="pengikut"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option></option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm save-pegawai-btn">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('sdm.spd.script')
@endsection