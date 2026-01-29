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
    <h3>Permintaan</h3>
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
                            <span> Tambah Permintaan</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_permintaan" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">No Agenda</th>
                                            <th class="f-light">Nomor Permintaan</th>
                                            <th class="f-light">Nama Permintaan</th>
                                            <th class="f-light">Tanggal</th>
                                            <th class="f-light">Unit</th>
                                            <th class="f-light">Tembusan</th>
                                            <th class="f-light">Status</th>
                                            <th class="f-light">Catatan</th>
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

    {{-- Modal Form Permintaan --}}
    <div class="modal fade" id="modal-permintaan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-permintaan" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id">

                        <!-- Nomor Agenda -->
                        <label for="no_agenda" class="col-form-label col-sm-2">Nomor Agenda</label>
                        <div class="col-sm-4">
                            <input type="text" id="no_agenda" name="no_agenda" class="form-control" maxlength="4" required
                                placeholder="Nomor Agenda...">
                        </div>

                        <!-- Nomor Surat -->
                        <label for="no_surat" class="col-form-label col-sm-1">Nomor Surat</label>
                        <div class="col-sm-5">
                            <input type="text" id="no_surat" name="no_surat" class="form-control"
                                placeholder="Nomor Surat..." required>
                        </div>

                         <!-- Tanggal -->
                        <label for="tanggal" class="col-form-label col-sm-2">Tanggal </label>
                        <div class="col-sm-10">
                            <input type="text" name="tgl" id="tgl" class="form-control js-datepicker digits"
                                placeholder="dd/mm/yyyy" aria-label="Date" data-language="en" required />
                        </div>

                        <!-- Nama Permintaan -->
                        <label for="nama_permintaan" class="col-form-label col-sm-2">Nama Permintaan</label>
                        <div class="col-sm-10">
                            <input type="text" id="nama_permintaan" name="nama_permintaan" class="form-control"
                                placeholder="Nama Permintaan..." required>
                        </div>

                        <!-- Unit -->
                        <label for="id_unit" class="col-form-label col-sm-2">Unit</label>
                        <div class="col-sm-5">
                            <select class="form-select select2" name="id_unit[]" multiple
                                data-placeholder="---- Pilih Salah Satu ----" required>
                            </select>
                        </div>

                        <!-- Status -->
                        <label for="status" class="col-form-label col-sm-1">Status</label>
                        <div class="col-sm-4">
                            <select class="form-select form-control select2" name="status"
                                data-placeholder="---- Pilih Salah Satu ----" required>
                                <option value="Pengajuan Panjar">Pengajuan Panjar</option>
                                <option value="Pengadaan">Pengadaan</option>
                                <option value="Serah Terima">Serah Terima</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <!-- Tembusan -->
                        <label for="tembusan" class="col-form-label col-sm-2">Tembusan</label>
                        <div class="col-sm-10">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="ICT" name="tembusan[]"  value="ICT">
                                    <label class="form-check-label" for="ICT">ICT</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Teknik" name="tembusan[]"  value="Teknik">
                                    <label class="form-check-label" for="Teknik">Teknik</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Alkes" name="tembusan[]" value="Alkes">
                                    <label class="form-check-label" for="Alkes">Alkes</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Umum" name="tembusan[]" value="Umum">
                                    <label class="form-check-label" for="Umum">Umum</label>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <label for="catatan" class="col-form-label col-sm-2">Catatan</label>
                        <div class="col-sm-10">
                            <textarea name="catatan" id="catatan" class="form-control" style="resize: none;" rows="3"
                                placeholder="Catatan..." required></textarea>
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
    @include('logistik.permintaan.script')
@endsection
