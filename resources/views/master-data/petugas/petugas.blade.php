@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

@endsection

@section('breadcrumb-title')
    <h3>Petugas</h3>
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
                        <button class="btn btn-secondary add-petugas">
                            <span class="fa fa-plus"></span>
                            <span> Tambah</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                {{-- <table id="table_petugas" class="table table-hover" data-toggle="table">
                                    <thead class="bg-secondary text-light text-bold text-uppercase text-center">
                                        <tr>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col">Nama COA</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                </table> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-petugas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-coa" novalidate="" autocomplete="off">
                        @csrf
                        <div class="form-group row my-0 g-lg-2 col-md-12">
                            <div class="col-md-6">
                                {{-- Hidden Input --}}
                                <div class="mb-2 row">
                                    <input type="hidden" name="id">
                                </div>

                                {{-- Kategori --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control select2" name="kategori" required>
                                            <option></option>
                                            <option value="Dokter">Dokter</option>
                                            <option value="Dokter Spesialis">Dokter Spesialis</option>
                                            <option value="Dokter Sub Spesialis">Dokter Sub Spesialis</option>
                                            <option value="Perawat">Perawat</option>
                                            <option value="Bidan">Bidan</option>
                                            <option value="Apoteker">Apoteker</option>
                                            <option value="Ahligizi">Ahligizi</option>
                                            <option value="Radiographer">Radiographer</option>
                                            <option value="Rekam Medis">Rekam Medis</option>
                                            <option value="Fisioterapis">Fisioterapis</option>
                                            <option value="Analis">Analis</option>
                                            <option value="Keuangan">Keuangan</option>
                                            <option value="Kasir">Kasir</option>
                                            <option value="ICT">ICT</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Kode --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="Kode">Kode</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="kode" type="text"
                                            placeholder="Kode Petugas..." required>
                                    </div>
                                </div>

                                {{-- NIK --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="NIK">NIK</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm ktp-number" name="nik" type="text"
                                            placeholder="NIK..." required>
                                    </div>
                                </div>

                                {{-- Nama Petugas --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="Nama Petugas">Nama</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="nama" type="text"
                                            placeholder="Nama Petugas..." required>
                                    </div>
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div class="form-group row my-0 g-lg-2 col-md-12">
                                    <div class="col-md-2">
                                        <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label" for="Jenis Kelamin">JK</label>
                                    </div>
                                </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" id="validationFormCheck2" type="radio"
                                                name="radio-stacked" required="">
                                            <label class="form-check-label" for="validationFormCheck2">Laki-Laki</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="validationFormCheck3" type="radio"
                                                name="radio-stacked" required="">
                                            <label class="form-check-label" for="validationFormCheck3">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="hp">No HP</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm phone-number" name="hp" type="text"
                                            placeholder="No HP...">
                                    </div>
                                </div>

                                {{-- Username --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="user">Username</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="user" type="text"
                                            placeholder="Username..." required>
                                    </div>
                                </div>

                                {{-- NO SIP --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="NO SIP">NO SIP</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="sip" type="text"
                                            placeholder="No SIP...">
                                    </div>
                                </div>

                                {{-- Satus --}}
                                <div class="media mb-2">
                                    <label class="col-sm-2 col-form-label m-r-10">Status</label>
                                    <div class="media-body switch-sm icon-state">
                                        <label class="switch col-sm-4">
                                            <input class="form-control" name="status" type="checkbox" checked>
                                            <span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                {{-- Spesialis --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Spesialis</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control js-select-2" id="Spesialis"
                                            name=spesialis"
                                            data-url="{{ route('master-data.spesialis.select_spesialis') }}"
                                            data-placeholder="---- Pilih Salah Satu ----"></select>
                                    </div>
                                </div>

                                {{-- Kode BPJS --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="kode BPJS">BPJS</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="bpjs" type="text"
                                            placeholder="Kode Dokter BPJS...">
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="alamat">Alamat</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm" name="alamat" id="" cols="30" rows="3"
                                            placeholder="Alamat Lengkap..."></textarea>
                                    </div>
                                </div>

                                {{-- Margin Obat --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Status</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control select2" name="status">
                                            <option></option>
                                            <option value="Mitra">Mitra</option>
                                            <option value="PWT/PWTT">PWT/PWTT</option>
                                            <option value="Sub Spesialis">Sub Spesialis/Konsulen/Profesor</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Konsul --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="Konsul">Konsul</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="konsul" type="text"
                                            placeholder="Tindakan Konsultasi Dokter...">
                                    </div>
                                </div>

                                {{-- Visite --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="Visite">Visite</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="visite" type="text"
                                            placeholder="Tindakan Visite Dokter...">
                                    </div>
                                </div>

                                {{-- Tanggal Berakhir SIP --}}
                                <div class="mb-1 row">
                                    <label class="col-sm-2 col-form-label" for="tgl_akhir">Tanggal</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm js-datepicker digits" name="tgl_akhir"
                                            type="text" placeholder="Tanggal Berakhir SIP..." data-language="en">
                                    </div>
                                </div>

                                {{-- Upload Fot --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="Upload">Foto</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" name="foto" type="file"
                                            placeholder="Upload Foto Dokter...">
                                    </div>
                                </div>

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
    @include('master-data.petugas.script')
@endsection
