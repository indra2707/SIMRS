@extends('layouts.simple.master')
@section('title',  $title)

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
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
                            <span> Tambah Data</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_pegawai" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
                                            <th class="f-light"></th>
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

    {{-- Modal Form  store pegawai --}}
    <div class="modal fade" id="modal-pegawai" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Form Pegawai</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="card">

                        <form class="f1 form-pegawai" id="form-pegawai" method="post">
                            @csrf
                             <input type="hidden" name="id" value="">
                            <!-- ===================== STEP HEADER ===================== -->
                            <div class="f1-steps">
                                <div class="f1-progress">
                                    <div class="f1-progress-line" data-now-value="33" data-number-of-steps="3"></div>
                                </div>

                                <div class="f1-step active">
                                    <div class="f1-step-icon"><i class="fa fa-building"></i></div>
                                    <p>Perusahaan & Pribadi</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-id-card"></i></div>
                                    <p>Kepegawaian & Kontak</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-gears"></i></div>
                                    <p>Lisensi & Sistem</p>
                                </div>
                            </div>

                            <!--                    STEP 1 — PERUSAHAAN + PRIBADI        -->
                            <fieldset>
                                <h5 class="mb-3">Data Perusahaan</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Anak Perusahaan</label>
                                        <input class="form-control" name="anak_perusahaan">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Rumah Sakit</label>
                                        <input class="form-control" name="rumah_sakit">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor SK Struktur</label>
                                        <input class="form-control" name="nomor_sk_struktur">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Jabatan</label>
                                        <input class="form-control" name="jabatan">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Penempatan</label>
                                        <input class="form-control" name="penempatan">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Lokasi Kerja</label>
                                        <input class="form-control" name="lokasi_kerja">
                                    </div>

                                </div>

                                <hr>

                                <h5 class="mb-3">Data Pribadi</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor Pegawai</label>
                                        <input class="form-control" name="nomor_pekerja">
                                    </div>

                                    <div class="col-md-8 mb-2">
                                        <label>Nama Pegawai *</label>
                                        <input class="form-control" name="nama_pekerja" required>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-select form-control select2" name="jenis_kelamin">
                                            <option value=""></option>
                                            <option value="Laki-laki" >Laki-laki</option>
                                            <option value="Perempuan" >Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="agama">Agama</label>
                                        <select class="form-select form-control select2" id="agama" name="agama">
                                            <option value=""></option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>NIK</label>
                                        <input class="form-control" name="nik" maxlength="16">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="status_pernikahan">Status Pernikahan</label>
                                        <select class="form-select form-control select2" id="status_pernikahan" name="status_pernikahan">
                                            <option value=""></option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Cerai">Cerai</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="mb-2">
                                            <label for="golongan_darah">Golongan Darah</label>
                                            <select class="form-select form-control select2" id="golongan_darah" name="golongan_darah">
                                                <option value=""></option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="disabilitas">Disabilitas</label>
                                        <select class="form-select form-control select2" id="disabilitas" name="disabilitas">
                                            <option value="">Pilih</option>
                                            <option value="Tidak">Tidak</option>
                                            <option value="Ya">Ya</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir">
                                    </div>

                                </div>

                                <div class="f1-buttons">
                                    <button class="btn btn-primary btn-next" type="button">Next</button>
                                </div>
                            </fieldset>

                            <!--        STEP 2 — KEPEGAWAIAN + KONTAK + ALAMAT          -->
                            <fieldset>
                                <h5 class="mb-3">Data Kepegawaian</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Golongan Upah</label>
                                        <input class="form-control" name="golongan_upah">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Status Kepegawaian</label>
                                        <input class="form-control" name="status_kepegawaian">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Masa Kerja</label>
                                        <input class="form-control" name="masa_kerja">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>TMT Status Kepegawaian</label>
                                        <input type="date" class="form-control" name="tmt_status_kepegawaian">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>TMT PWTT</label>
                                        <input type="date" class="form-control" name="tmt_pwtt">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>TMT PWT</label>
                                        <input type="date" class="form-control" name="tmt_pwt">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Fungsi</label>
                                        <input class="form-control" name="fungsi">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Sub Fungsi</label>
                                        <input class="form-control" name="sub_fungsi">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>TMT Jabatan</label>
                                        <input type="date" class="form-control" name="tmt_jabatan">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>TMT Golongan Upah</label>
                                        <input type="date" class="form-control" name="tmt_golongan_upah">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Penyetaraan Jabatan AP</label>
                                        <input class="form-control" name="penyetaraan_jabatan_ap">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Penyetaraan Golongan Upah AP</label>
                                        <input class="form-control" name="penyetaraan_golongan_upah_ap">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Tanggal Akhir Kontrak</label>
                                        <input type="date" class="form-control" name="tanggal_akhir_kontrak">
                                    </div>

                                </div>

                                <hr>

                                <h5 class="mb-3">Data Kontak & Darurat</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor HP</label>
                                        <input class="form-control" name="nomor_hp">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor Kontak Darurat</label>
                                        <input class="form-control" name="nomor_kontak_darurat">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nama Kontak Darurat</label>
                                        <input class="form-control" name="nama_kontak_darurat">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Hubungan Kontak Darurat</label>
                                        <input class="form-control" name="hubungan_kontak_darurat">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="email">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Email Dinas</label>
                                        <input class="form-control" name="email_dinas">
                                    </div>

                                </div>

                                <hr>

                                <h5 class="mb-3">Alamat</h5>
                                <div class="row">

                                    <div class="col-md-12 mb-2">
                                        <label>Alamat KTP</label>
                                        <textarea class="form-control" name="alamat_ktp"></textarea>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label>Alamat Domisili</label>
                                        <textarea class="form-control" name="alamat_domisili"></textarea>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label>Alamat NPWP</label>
                                        <textarea class="form-control" name="alamat_npwp"></textarea>
                                    </div>

                                </div>

                                <div class="f1-buttons">
                                    <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                    <button class="btn btn-primary btn-next" type="button">Next</button>
                                </div>
                            </fieldset>

                            <!--          STEP 3 — LISENSI + BANKING + SISTEM           -->
                            <fieldset>
                                <h5 class="mb-3">Lisensi Profesi</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor STR</label>
                                        <input class="form-control" name="nomor_str">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>STR Seumur Hidup</label>
                                        <input class="form-control" name="str_seumur_hidup">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Masa Berlaku STR</label>
                                        <input type="date" class="form-control" name="masa_berlaku_str">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor SIP</label>
                                        <input class="form-control" name="nomor_sip">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Masa Berlaku SIP</label>
                                        <input type="date" class="form-control" name="masa_berlaku_sip">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Asuransi Profesi</label>
                                        <input class="form-control" name="asuransi_profesi">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor Polis</label>
                                        <input class="form-control" name="nomor_polis">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Masa Berlaku Asuransi</label>
                                        <input type="date" class="form-control" name="masa_berlaku_asuransi">
                                    </div>

                                </div>

                                <hr>

                                <h5 class="mb-3">Pendidikan</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-2"><label>Pend. Diploma</label><input class="form-control"
                                            name="pend_diploma"></div>
                                    <div class="col-md-3 mb-2"><label>Pend. S1</label><input class="form-control"
                                            name="pend_s1"></div>
                                    <div class="col-md-3 mb-2"><label>Pend. S2</label><input class="form-control"
                                            name="pend_s2"></div>
                                    <div class="col-md-3 mb-2"><label>Pend. S3</label><input class="form-control"
                                            name="pend_s3"></div>

                                    <div class="col-md-6 mb-2">
                                        <label>Kampus Terakhir</label>
                                        <input class="form-control" name="kampus_terakhir">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label>Jenjang Pendidikan Terakhir</label>
                                        <input class="form-control" name="jenjang_pendidikan_terakhir">
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="keterangan"></textarea>
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">Banking, BPJS, Pajak</h5>
                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <label>Nama Bank</label>
                                        <input class="form-control" name="nama_bank">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor Rekening</label>
                                        <input class="form-control" name="nomor_rekening">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nama Rekening</label>
                                        <input class="form-control" name="nama_rekening">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor BPJSTK</label>
                                        <input class="form-control" name="nomor_bpjstk">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor BPJS Kesehatan</label>
                                        <input class="form-control" name="nomor_bpjskesehatan">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Nomor NPWP</label>
                                        <input class="form-control" name="nomor_npwp">
                                    </div>

                                </div>

                                <hr>

                                <h5 class="mb-3">System Info</h5>
                                <div class="row">

                                    {{-- <div class="col-md-4 mb-2">
                                        <label>Input By</label>
                                        <input class="form-control" name="input_by">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Input Date</label>
                                        <input type="date" class="form-control" name="input_date">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Update By</label>
                                        <input class="form-control" name="update_by">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Update Date</label>
                                        <input type="date" class="form-control" name="update_date">
                                    </div> --}}

                                    <div class="col-md-4 mb-2">
                                        <label>Temp Username</label>
                                        <input class="form-control" name="temp_username">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>Username</label>
                                        <input class="form-control" name="username">
                                    </div>

                                </div>

                                <div class="f1-buttons">
                                    <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                    <button class="btn btn-primary btn-submit save-btn" type="button">Submit</button>
                                </div>

                            </fieldset>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

@section('script')
    @include('sdm.pegawai.script')
    <script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
    <script src="{{ asset('assets/js/form-wizard/jquery.backstretch.min.js') }}"></script>
@endsection
