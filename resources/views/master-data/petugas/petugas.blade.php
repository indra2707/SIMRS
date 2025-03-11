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
                                            <th class="f-light">Nama Petugas</th>
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
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-petugas" novalidate="" autocomplete="off">
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
                                        <select class="form-select form-control" name="kategori"
                                            data-placeholder="---- Pilih Kategori ----" required>
                                            <option></option>
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
                                <div class="form-group row my-0 g-lg-1 col-md-12">
                                    <div class="col-md-2">
                                        <div class="mb-2 row">
                                            <label class="col-sm-2 col-form-label" for="Jenis Kelamin">JK</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" id="validationFormCheck2" type="radio"
                                                name="jenis_kelamin" required="" value="L">
                                            <label class="form-check-label" for="validationFormCheck2">Laki-Laki</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" id="validationFormCheck3" type="radio"
                                                name="jenis_kelamin" required="" value="P">
                                            <label class="form-check-label" for="validationFormCheck3">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="hp">No HP</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm phone-number" name="hp"
                                            type="text" placeholder="No HP...">
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
                                {{-- <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="nama">Tanda Tangan</label>
                                    <div class="file-box col-sm-10 text-center">
                                        <div id="signature-pad"></div>
                                        <textarea id="signature64" class="form-control" name="signed" style="display: none"></textarea>
                                        <div class="file-bottom mt-2">
                                            <button id="clear" class="btn btn-danger btn-sm">Clear
                                                Signature</button>
                                            </div>
                                        </div>
                                    </div> --}}

                                {{-- Upload Fot --}}
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
                                                <input type="file" accept="images/jpg, images/png" id="profil"
                                                    name="profil">
                                                <a href="#" class="avatar-selector-btn">
                                                    <i class="icofont icofont-pencil-alt-5"></i>
                                                </a>
                                            </div>
                                            <!-- Browse Image to Upload Wrapper -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Spesialis --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="spesialis">Spesialis</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control" name="spesialis"
                                            data-placeholder="---- Pilih Spesialis ----"></select>
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
                                        <textarea class="form-control form-control-sm" style="resize: none" name="alamat" id="" cols="30"
                                            rows="3" placeholder="Alamat Lengkap..."></textarea>
                                    </div>
                                </div>

                                {{-- Margin Obat --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="status_dokter">Status</label>
                                    <div class="col-sm-10">
                                        <select class="form-select form-control select2" name="status_dokter"
                                            data-placeholder="---- Pilih Status ----">
                                            <option></option>
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

    <!-- PhotoSwipe Modal Structure -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                </div>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
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
