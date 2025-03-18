@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/photoswipe.css') }}">
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
                        <button class="btn btn-primary add-petugas">
                            <span class="fa fa-plus"></span>
                            <span> Tambah Petugas</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_petugas" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">Profile</th>
                                            <th class="f-light">Kategori</th>
                                            <th class="f-light">Kode BPJS</th>
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

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-petugas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row my-0 g-lg-2 col-md-12">
                        <div class="card-wrapper border rounded-3 p-2 light-card checkbox-checked">
                            <form class="g-3 form-petugas" novalidate="" autocomplete="off">
                                {{-- @csrf --}}
                                {{-- Hidden ID --}}
                                <input type="hidden" name="id" id="id">
                                <div class="row">
                                    <div class="col-sm-12 col-xl-6">
                                        {{-- Kode --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="kode_petugas">Kode Petugas</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="kode_petugas"
                                                    name="kode_petugas" placeholder="Kode Petugas..." title="Kode Petugas"
                                                    required>
                                            </div>
                                        </div>
                                        {{-- Nama Petugas --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="nama">Nama Petugas</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="nama"
                                                    name="nama" placeholder="Nama Petugas..." title="Nama Petugas"
                                                    required>
                                            </div>
                                        </div>
                                        {{-- NIK --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="nik">NIK</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm ktp-number"
                                                    id="nik" name="nik" placeholder="NIK..."
                                                    title="Nomor Induk Kependudukan" required>
                                            </div>
                                        </div>
                                        {{-- Jenis Kelamin --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-9 row">
                                                <div
                                                    class="form-check radio radio-primary d-flex justify-content-center align-items-center col-md-4">
                                                    <input class="form-check-input" id="laki_laki" type="radio"
                                                        name="jenis_kelamin" required value="L">
                                                    <label class="form-check-label" for="laki_laki">Laki-Laki</label>
                                                </div>
                                                <div
                                                    class="form-check radio radio-primary d-flex justify-content-center align-items-center col-md-4">
                                                    <input class="form-check-input" id="perempuan" type="radio"
                                                        name="jenis_kelamin" required value="P">
                                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Status --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="status_petugas">Status
                                                Petugas</label>
                                            <div class="col-sm-9">
                                                <select class="form-select form-control" name="status_petugas"
                                                    data-placeholder="---- Pilih Status Petugas ----">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Nomor Telepon --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="no_hp">No. HP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm phone-number"
                                                    id="no_hp" name="no_hp" placeholder="+62 xxx xxx xxxx"
                                                    title="Nomor Handphone">
                                            </div>
                                        </div>
                                        {{-- Alamat --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="alamat">Alamat</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control form-control-sm" style="resize: none" name="alamat" id="alamat" cols="30"
                                                    rows="3" placeholder="Alamat Lengkap..."></textarea>
                                            </div>
                                        </div>
                                        {{-- Upload Foto --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-2 col-form-label" for="Upload">Foto</label>
                                            <div class="col-sm-10">
                                                <div id="AvatarFileUpload">
                                                    <!-- Image Preview Wrapper -->
                                                    <div class="selected-image-holder">
                                                        <img src="" alt="AvatarInput" id="previews">
                                                    </div>
                                                    <!-- Image Preview Wrapper -->
                                                    <!-- Browse Image to Upload Wrapper -->
                                                    <div class="avatar-selector">
                                                        <input type="file" accept="images/jpg, images/png"
                                                            id="profil" name="profil">
                                                        <a href="#" class="avatar-selector-btn">
                                                            <i class="icofont icofont-pencil-alt-5"></i>
                                                        </a>
                                                    </div>
                                                    <!-- Browse Image to Upload Wrapper -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-6">
                                        {{-- Kode BPJS --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="kode_bpjs">Kode BPJS</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="kode_bpjs"
                                                    name="kode_bpjs" placeholder="Kode BPJS..." title="Kode BPJS">
                                            </div>
                                        </div>
                                        {{-- Kategori --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="kategori">Kategori</label>
                                            <div class="col-sm-9">
                                                <select class="form-select form-control" name="kategori"
                                                    data-placeholder="---- Pilih Kategori ----" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Nomor SIP --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="no_sip">No. SIP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="no_sip"
                                                    name="no_sip" placeholder="Nomor SIP..."
                                                    title="Nomor Surat Izin Praktik">
                                            </div>
                                        </div>
                                        {{-- Tanggal Masa Berlaku No. SIP --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="masa_berlaku_sip">Tgl Berlaku
                                                SIP</label>
                                            <div class="col-sm-9">
                                                <input type="text"
                                                    class="form-control form-control-sm js-datepicker digits"
                                                    id="masa_berlaku_sip" name="masa_berlaku_sip"
                                                    placeholder="Tanggal Berlaku SIP..."
                                                    title="Tanggal Masa Berlaku No. SIP" data-language="en">
                                            </div>
                                        </div>
                                        {{-- Spesialis --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="spesialis">Spesialis</label>
                                            <div class="col-sm-9">
                                                <select class="form-select form-control" name="spesialis"
                                                    data-placeholder="---- Pilih Spesialis ----">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Tindakan Konsul --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="tindakan_konsul">Tindakan
                                                Konsul</label>
                                            <div class="col-sm-9">
                                                <select class="form-select form-control" id="tindakan_konsul"
                                                    name="tindakan_konsul"
                                                    data-placeholder="---- Pilih Tindakan konsul ----">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Tindakan Visite --}}
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label" for="tindakan_visite">Tindakan
                                                Visite</label>
                                            <div class="col-sm-9">
                                                <select class="form-select form-control" id="tindakan_visite"
                                                    name="tindakan_visite"
                                                    data-placeholder="---- Pilih Tindakan Visite ----">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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

    {{-- Modal Signature Pad --}}
    <div class="modal fade" id="modal-signature" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-signature text-center" novalidate="" autocomplete="off">
                        {{-- @csrf --}}
                        <input type="hidden" name="id">
                        <input type="hidden" name="nama">
                        {{-- View Signature Img --}}
                        <div class="file-box img-signature">
                            <div class="image-wrapper">
                                <img class="kbw-signature images-sig" alt="image" class="img-fluid" id="imageViewer">
                            </div>
                            <div class="file-bottom mt-2">
                                <button class="btn btn-info update-signature" type="button"><span
                                        class="fa fa-edit"></span> Update</button>
                            </div>
                        </div>
                        <div class="signatures-pad">
                            <div id="signature-pad"></div>
                            <textarea id="signature64" class="form-control" name="signed" style="display: none"></textarea>
                            <div class="file-bottom mt-2">
                                <button class="btn btn-light clear" type="button"><span class="fa fa-eraser"></span>
                                    Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><span
                            class="fa fa-times"></span> Batal</button>
                    <button class="btn btn-primary save-signature-btn" type="button"><span class="fa fa-check"></span>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/js/photoswipe/photoswipe.min.js') }}"></script>
    <script src="{{ asset('assets/js/photoswipe/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ asset('assets/js/photoswipe/photoswipe.js') }}"></script>
    <script src="{{ asset('assets/js/photoswipe/jqPhotoSwipe.min.js') }}"></script>
    <script src="{{ asset('assets/js/photoswipe/photoswipe-custome.js') }}"></script>
    @include('master-data.petugas.script');
@endsection
