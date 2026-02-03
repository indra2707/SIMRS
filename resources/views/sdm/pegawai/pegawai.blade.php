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

        /* Validasi error untuk input & select biasa (tetap rounded) */
        .form-control.is-invalid,
        .form-select.is-invalid,
        .was-validated .form-control:invalid,
        .was-validated .form-select:invalid {
            border-color: #dc3545 !important;
            border-radius: 0.375rem !important;
            /* sesuaikan dengan rounded form kamu */
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        /* KHUSUS SELECT2 - versi anti-double border */
        .is-invalid-select2 .select2-selection--single {
            border-color: #dc3545 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        /* Hilangkan border ganda di container Select2 */
        .is-invalid-select2 {
            border: none !important;
            /* penting: hapus border container */
        }

        /* Pastikan pesan error di bawahnya rapi */
        .select2-container+.invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            color: #dc3545;
            font-size: 0.875em;
        }


        .f1-steps {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
        }

        .f1-step {
            flex: 1;
            text-align: center;
            min-width: 0;
        }

        .f1-progress {
            position: absolute;
            width: 100%;
            top: 25px;
            left: 0;
            z-index: 0;
        }

        .f1-step-icon {
            position: relative;
            z-index: 1;
            background: #fff;
        }

        .is-invalid+.select2-container .select2-selection {
            border-color: #dc3545;
        }
    </style>
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
                        <button class="btn btn-success import-btn">
                            <span class="fa fa-upload"></span>
                            <span> Import Excel</span>
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

    {{-- Modal Form store pegawai --}}
    <div class="modal fade" id="modal-pegawai" tabindex="-1" role="dialog" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Form Pegawai</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="card g-2">

                        <form class="f1 form-pegawai" id="form-pegawai" novalidate>
                            @csrf
                            <input type="hidden" name="id" value="">
                            <!-- ===================== STEP HEADER ===================== -->
                            <div class="f1-steps">
                                <div class="f1-progress">
                                    <div class="f1-progress-line" data-now-value="0" data-number-of-steps="5"></div>
                                </div>

                                <div class="f1-step active">
                                    <div class="f1-step-icon"><i class="fa fa-building"></i></div>
                                    <p>Perusahaan & Pribadi</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-id-card"></i></div>
                                    <p>Kepegawaian</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-address-book"></i></div>
                                    <p>Kontak</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-graduation-cap"></i></div>
                                    <p>Pendidikan</p>
                                </div>

                                <div class="f1-step">
                                    <div class="f1-step-icon"><i class="fa fa-bank"></i></div>
                                    <p>Bank</p>
                                </div>
                            </div>


                            <!--                    STEP 1 — PERUSAHAAN + PRIBADI        -->
                            <fieldset>
                                <h5 class="mb-3">Data Perusahaan</h5>
                                <div class="row g-2">
                                    <!-- Anak Perusahaan -->
                                    <label for="anak_perusahaan" class="col-form-label col-sm-2">Anak Perusahaan</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="anak_perusahaan" name="anak_perusahaan" class="form-control"
                                            placeholder="Anak Perusahaan..." required value="PT Pertamina Bina Medika IHC">
                                    </div>

                                    <!-- Penempatan -->
                                    <label for="penempatan" class="col-form-label col-sm-2">Penempatan</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="penempatan" name="penempatan" class="form-control"
                                            placeholder="Penempatan..." required value="RS Pertamina Royal Biringkanaya">
                                    </div>

                                    <!-- Lokasi Kerja -->
                                    <label for="lokasi_kerja" class="col-form-label col-sm-2">Lokasi Kerja</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="lokasi_kerja" name="lokasi_kerja" class="form-control"
                                            placeholder="Lokasi Kerja..." required value="RS Pertamina Royal Biringkanaya">
                                    </div>

                                    <!-- Nomor SK Struktur  -->
                                    <label for="id_sk_struktur" class="col-form-label col-sm-2">Nomor SK Struktur</label>
                                    <div class="col-sm-4">
                                        <select class="form-select select2" name="id_sk_struktur"
                                            data-placeholder="---- Pilih Salah Satu ----" required>
                                            <option></option>
                                        </select>
                                    </div>

                                    <!-- Jabatan  -->
                                    <label for="id_jabatan" class="col-form-label col-sm-2">Jabatan</label>
                                    <div class="col-sm-4">
                                        <select class="form-select select2" name="id_jabatan"
                                            data-placeholder="---- Pilih Salah Satu ----" required>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                <h5 class="mb-3">Data Pribadi</h5>
                                <div class="row g-2">

                                    <!-- Status Pegawai -->
                                    <label for="status_kepegawaian" class="col-form-label col-sm-2">Status Pegawai</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" name="status_kepegawaian" required>
                                            <option></option>
                                            <option value="PWTT">PWTT</option>
                                            <option value="PWT">PWT</option>
                                            <option value="Mitra Pegawai">Mitra Pegawai</option>
                                            <option value="Mitra Dokter">Mitra Dokter</option>
                                            <option value="Outsourcing">Outsourcing</option>
                                            <option value="Internship">Internship</option>
                                        </select>
                                    </div>

                                    <!-- Nomor Pekerja  -->
                                    <label for="nomor_pekerja" class="col-form-label col-sm-2">Nomor Pekerja</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="nomor_pekerja" name="nomor_pekerja" class="form-control"
                                            placeholder="Nomor Pekerja..." required>
                                    </div>

                                    <!-- Nama Pekerja  -->
                                    <label for="nama_pekerja" class="col-form-label col-sm-2">Nama Pekerja</label>
                                    <div class="col-sm-4">
                                        <input id="nama_pekerja" name="nama_pekerja" class="form-control"
                                            placeholder="Nama Pekerja..." required>
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <label for="jenis_kelamin" class="col-form-label col-sm-2">Jenis Kelamin</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" name="jenis_kelamin" required>
                                            <option value=""></option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>

                                    <!-- Tanggal Lahir  -->
                                    <label for="tanggal_lahir" class="col-form-label col-sm-2">Tanggal Lahir</label>
                                    <div class="col-sm-4">
                                        <input class="form-control js-datepicker digits" placeholder="Tanggal Lahir..."
                                            name="tanggal_lahir" data-language="en" required>
                                    </div>

                                    <!-- NIK  -->
                                    <label for="nik" class="col-form-label col-sm-2">NIK</label>
                                    <div class="col-sm-4">
                                        <input class="form-control ktp-number" id="nik" name="nik" placeholder="NIK..."
                                            required>
                                    </div>

                                    <!-- Agama -->
                                    <label for="agama" class="col-form-label col-sm-2">Agama</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="agama" name="agama" required>
                                            <option value=""></option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>

                                    <!-- Status Pernikahan -->
                                    <label for="status_pernikahan" class="col-form-label col-sm-2">Status
                                        Pernikahan</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="status_pernikahan"
                                            name="status_pernikahan" required>
                                            <option value=""></option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Cerai">Cerai</option>
                                            <option value="Janda">Janda</option>
                                            <option value="Kawin">Kawin</option>
                                            <option value="Lajang">Lajang</option>
                                        </select>
                                    </div>

                                    <!-- Golongan Darah -->
                                    <label for="golongan_darah" class="col-form-label col-sm-2">Golongan Darah</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="golongan_darah"
                                            name="golongan_darah">
                                            <option value=""></option>
                                            <option value="A">A</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B">B</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB">AB</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O">O</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>

                                    <!-- Disabilitas -->
                                    <label for="disabilitas" class="col-form-label col-sm-2">Disabilitas</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="disabilitas"
                                            name="disabilitas">
                                            <option value="">Pilih</option>
                                            <option value="Tidak" selected>Tidak</option>
                                            <option value="Ya">Ya</option>
                                        </select>
                                    </div>
                                    <div class="f1-buttons">
                                        <button class="btn btn-primary btn-next" type="button">Next</button>
                                    </div>
                                </div>
                            </fieldset>


                            <!--        STEP 2 — KEPEGAWAIAN + KONTAK + ALAMAT          -->
                            <fieldset>
                                <h5 class="mb-3">Data Kepegawaian</h5>
                                <div class="row g-2">

                                    <!-- Golongan  -->
                                    <label for="golongan_upah" class="col-form-label col-sm-2">Golongan</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="golongan_upah"
                                            name="golongan_upah" required>
                                            <option value=""></option>
                                            <option value="Utama">Utama</option>
                                            <option value="Madya">Madya</option>
                                            <option value="Biasa">Biasa</option>
                                        </select>
                                    </div>

                                    <!-- Masa Kerja  -->
                                    <label for="masa_kerja" class="col-form-label col-sm-2">Masa Kerja</label>
                                    <div class="col-sm-4">
                                        <input class="form-control js-datepicker digits" id="masa_kerja" name="masa_kerja"
                                            data-language="en" placeholder="Masa Kerja...">
                                    </div>

                                    <!-- TMT Status Kepegawaian -->
                                    <label for="tmt_status_kepegawaian" class="col-form-label col-sm-2">TMT Status</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits"
                                            placeholder="TMT Status Kepegawaian..." name="tmt_status_kepegawaian"
                                            data-language="en">
                                    </div>

                                    <!-- TMT PWTT -->
                                    <label for="tmt_pwtt" class="col-form-label col-sm-2">TMT PWTT</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits" name="tmt_pwtt"
                                            placeholder="TMT PWTT..." data-language="en">
                                    </div>

                                    <!-- TMT PWT -->
                                    <label for="tmt_pwt" class="col-form-label col-sm-2">TMT PWT</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits" name="tmt_pwt"
                                            placeholder="TMT PWT..." data-language="en">
                                    </div>

                                    <!-- fungsi -->
                                    <label for="fungsi" class="col-form-label col-sm-2">Fungsi</label>
                                    <div class="col-sm-4">
                                        <select type="text" class="form-select form-control select2" id="fungsi"
                                            name="fungsi" required>
                                            <option value=""></option>
                                            <option value="Medis">Medis</option>
                                            <option value="Perawat">Perawat</option>
                                            <option value="Nakes Lain">Nakes Lain</option>
                                            <option value="Non Medis">Non Medis</option>
                                        </select>
                                    </div>

                                    <!-- Sub Fungsi -->
                                    <label for="id_sub_fungsi" class="col-form-label col-sm-2">Sub Fungsi</label>
                                    <div class="col-md-4">
                                        <select class="form-select select2" name="id_sub_fungsi"
                                            data-placeholder="---- Pilih Salah Satu ----" required>
                                            <option></option>
                                        </select>
                                    </div>

                                    <!-- TMT Jabatan -->
                                    <label for="tmt_jabatan" class="col-form-label col-sm-2">TMT Jabatan</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits" name="tmt_jabatan"
                                            placeholder="TMT Jabatan..." data-language="en">
                                    </div>

                                    <!-- TMT Golongan Upah -->
                                    <label for="tmt_golongan_upah" class="col-form-label col-sm-2">TMT Golongan
                                        Upah</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits" name="tmt_golongan_upah"
                                            placeholder="TMT Golongan Upah..." data-language="en">
                                    </div>

                                    <!-- Penyertaan Jabatan AP -->
                                    <label for="penyetaraan_jabatan_ap" class="col-form-label col-sm-2">Penyetaraan
                                        Jabatan</label>
                                    <div class="col-md-4">
                                        <input class="form-control" name="penyetaraan_jabatan_ap"
                                            placeholder="Penyetaraan Jabatan AP...">
                                    </div>

                                    <!-- Penyertaan Golongan Upah -->
                                    <label for="penyetaraan_golongan_upah_ap" class="col-form-label col-sm-2">Penyetaraan
                                        Golongan</label>
                                    <div class="col-md-4">
                                        <input class="form-control" name="penyetaraan_golongan_upah_ap"
                                            placeholder="Penyetaraan Golongan Upah AP...">
                                    </div>

                                    <!-- Tanggal Akhir Kontrak -->
                                    <label for="tanggal_akhir_kontrak" class="col-form-label col-sm-2">Tanggal Akhir
                                        Kontrak</label>
                                    <div class="col-md-4">
                                        <input class="form-control js-datepicker digits" name="tanggal_akhir_kontrak"
                                            placeholder="Tanggal Akhir Kontrak" data-language="en">
                                    </div>

                                    <!-- Unit -->
                                    <label for="id_unit" class="col-form-label col-sm-2">Unit</label>
                                    <div class="col-md-10">
                                        <select class="form-select select2" name="id_unit"
                                            data-placeholder="---- Pilih Salah Satu ----" required>
                                        </select>
                                    </div>

                                    <div class="f1-buttons">
                                        <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                        <button class="btn btn-primary btn-next" type="button">Next</button>
                                    </div>
                                </div>
                            </fieldset>

                            <!--        STEP 3 — KONTAK + ALAMAT          -->
                            <fieldset>
                                <h5 class="mb-3">Data Kontak & Darurat</h5>
                                <div class="row g-2">

                                    <!-- Nomor HP -->
                                    <label for="nomor_hp" class="col-form-label col-sm-2">Nomor HP</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control phone-number" name="nomor_hp"
                                            placeholder="+62 xxx xxx xxxx">
                                    </div>

                                    <!-- Nomor Kontak Darurat -->
                                    <label for="nomor_kontak_darurat" class="col-form-label col-sm-2">Nomor Kontak
                                        Darurat</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control phone-number" name="nomor_kontak_darurat"
                                            placeholder="+62 xxx xxx xxxx">
                                    </div>

                                    <!-- Nama Kontak Darurat -->
                                    <label for="nama_kontak_darurat" class="col-form-label col-sm-2">Nama Kontak
                                        Darurat</label>
                                    <div class="col-md-4">
                                        <input class="form-control" name="nama_kontak_darurat"
                                            placeholder="Nama Kontak Darurat...">
                                    </div>

                                    <!-- Hubungan Kontak Darurat -->
                                    <label for="hubungan_kontak_darurat" class="col-form-label col-sm-2">Hubungan
                                        Kontak</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="hubungan_kontak_darurat"
                                            name="hubungan_kontak_darurat">
                                            <option value=""></option>
                                            <option value="Orang Tua">Orang Tua</option>
                                            <option value="Ayah">Ayah</option>
                                            <option value="Ibu">Ibu</option>
                                            <option value="Suami">Suami</option>
                                            <option value="Istri">Istri</option>
                                            <option value="Saudara Kandung">Saudara Kandung</option>
                                            <option value="Keluarga">Keluarga</option>
                                            <option value="Teman">Teman</option>
                                            <option value="Atasan">Atasan</option>
                                        </select>
                                    </div>

                                    <!-- Email-->
                                    <label for="email" class="col-form-label col-sm-2">Email</label>
                                    <div class="col-md-4">
                                        <input type="email" class="form-control" name="email" placeholder="Email...">
                                    </div>

                                    <!-- Email Dinas-->
                                    <label for="email_dinas" class="col-form-label col-sm-2">Email Dinas</label>
                                    <div class="col-md-4">
                                        <input type="email" class="form-control" name="email_dinas"
                                            placeholder="Email Dinas...">
                                    </div>
                                </div>
                                <hr>

                                <h5 class="mb-3">Alamat Pegawai</h5>
                                <div class="row g-2">

                                    <!-- Alamat KTP-->
                                    <label for="alamat_ktp" class="col-form-label col-sm-2">Alamat KTP</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" name="alamat_ktp"
                                            placeholder="Alamat KTP..."></textarea>
                                    </div>

                                    <!-- Alamat Domisili-->
                                    <label for="alamat_domisili" class="col-form-label col-sm-2">Alamat Domisili</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" name="alamat_domisili"
                                            placeholder="Alamat Domisili..."></textarea>
                                    </div>

                                    <!-- Alamat NPWP-->
                                    <label for="alamat_npwp" class="col-form-label col-sm-2">Alamat NPWP</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="alamat_npwp"
                                            placeholder="Alamat NPWP..."></textarea>
                                    </div>

                                    <div class="f1-buttons">
                                        <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                        <button class="btn btn-primary btn-next" type="button">Next</button>
                                    </div>
                                </div>
                            </fieldset>

                            <!--          STEP 4 — LISENSI + BANKING + SISTEM           -->
                            <fieldset>
                                <h5 class="mb-3">Lisensi Profesi</h5>
                                <div class="row g-2">

                                    <!-- Nomor STR-->
                                    <label for="nomor_str" class="col-form-label col-sm-2">Nomor STR</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nomor_str" placeholder="Nomor STR...">
                                    </div>

                                    <!-- STR Seumur Hidup -->
                                    <label for="str_seumur_hidup" class="col-form-label col-sm-2">STR Seumur Hidup</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="str_seumur_hidup"
                                            name="str_seumur_hidup">
                                            <option value=""></option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>

                                    <!-- Masa Berlaku STR-->
                                    <label for="masa_berlaku_str" class="col-form-label col-sm-2">Masa Berlaku STR</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control js-datepicker digits" name="masa_berlaku_str"
                                            placeholder="Masa Berlaku STR..." data-language="en">
                                    </div>

                                    <!-- Nomor SIP-->
                                    <label for="nomor_sip" class="col-form-label col-sm-2">Nomor SIP</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nomor_sip" placeholder="Nomor SIP...">
                                    </div>

                                    <!-- Masa Berlaku SIP-->
                                    <label for="masa_berlaku_sip" class="col-form-label col-sm-2">Masa Berlaku SIP</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control js-datepicker digits" name="masa_berlaku_sip"
                                            placeholder="Masa Berlaku SIP..." data-language="en">
                                    </div>

                                    <!-- Asuransi Profesi-->
                                    <label for="asuransi_profesi" class="col-form-label col-sm-2">Asuransi Profesi</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="asuransi_profesi"
                                            placeholder="Asuransi Profesi...">
                                    </div>

                                    <!-- Nomor Polis -->
                                    <label for="nomor_polis" class="col-form-label col-sm-2">Nomor Polis</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nomor_polis"
                                            placeholder="Nomor Polis...">
                                    </div>

                                    <!-- Masa Berlaku Asuransi -->
                                    <label for="masa_berlaku_asuransi" class="col-form-label col-sm-2">Masa Berlaku
                                        Asuransi</label>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control js-datepicker digits"
                                            name="masa_berlaku_asuransi" placeholder="Masa Berlaku Asuransi"
                                            data-language="en">
                                    </div>
                                </div>
                                <hr>

                                <h5 class="mb-3">Pendidikan</h5>
                                <div class="row g-2">

                                    <!-- Pendidikan Diploma -->
                                    <label for="pend_diploma" class="col-form-label col-sm-2">Pend. Diploma</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="pend_diploma"
                                            placeholder="Pend. Diploma...">
                                    </div>

                                    <!-- Pendidikan S1 -->
                                    <label for="pend_s1" class="col-form-label col-sm-2">Pend. S1</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="pend_s1" placeholder="Pend. S1...">
                                    </div>

                                    <!-- Pendidikan S2 -->
                                    <label for="pend_s2" class="col-form-label col-sm-2">Pend. S2</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="pend_s2" placeholder="Pend. S2...">
                                    </div>

                                    <!-- Pendidikan S3 -->
                                    <label for="pend_s3" class="col-form-label col-sm-2">Pend. S3</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="pend_s3" placeholder="Pend. S3...">
                                    </div>

                                    <!-- Kampus Terakhir -->
                                    <label for="kampus_terakhir" class="col-form-label col-sm-2">Kampus Terakhir</label>
                                    <div class="col-md-4">
                                        <input class="form-control" name="kampus_terakhir" placeholder="Kampus Terakhir..."
                                            required>
                                    </div>

                                    <!-- Pendidikan Terakhir -->
                                    <label for="jenjang_pendidikan_terakhir" class="col-form-label col-sm-2">Pendidikan
                                        Terakhir</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-control select2" id="jenjang_pendidikan_terakhir"
                                            name="jenjang_pendidikan_terakhir" required>
                                            <option value=""></option>
                                            <option value="SMA / SMK">SMA / SMK</option>
                                            <option value="D3">D3</option>
                                            <option value="D4">D4</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>

                                    <!-- Keterangan -->
                                    <label for="keterangan" class="col-form-label col-sm-2">Keterangan</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="keterangan"
                                            placeholder="Keterangan..."></textarea>
                                    </div>

                                    <div class="f1-buttons">
                                        <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                        <button class="btn btn-primary btn-next" type="button">Next</button>
                                    </div>
                                </div>
                            </fieldset>

                            <!--                    STEP 5 — PERUSAHAAN + PRIBADI        -->
                            <fieldset>
                                <h5 class="mb-3">Banking, BPJS, Pajak</h5>
                                <div class="row g-2">

                                    <!-- Nama Bank -->
                                    <label for="id_bank" class="col-form-label col-sm-2">Nama Bank</label>
                                    <div class="col-md-4">
                                        <select class="form-select select2" name="id_bank"
                                            data-placeholder="---- Pilih Salah Satu ----" required>
                                            <option></option>
                                        </select>
                                    </div>

                                    <!-- Nomor Rekening -->
                                    <label for="nomor_rekening" class="col-form-label col-sm-2">Nomor Rekening</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="nomor_rekening"
                                            placeholder="Nomor Rekening...">
                                    </div>

                                    <!-- Nama Rekening -->
                                    <label for="nama_rekening" class="col-form-label col-sm-2">Nama Rekening</label>
                                    <div class="col-md-4">
                                        <input class="form-control" name="nama_rekening" placeholder="Nama Rekening...">
                                    </div>

                                    <!-- Nomor BPJSTK -->
                                    <label for="nomor_bpjstk" class="col-form-label col-sm-2">Nomor BPJSTK</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control ktp-number" name="nomor_bpjstk"
                                            maxlength="14" placeholder="Nomor BPJSTK...">
                                    </div>

                                    <!-- Nomor BPJS Kesehatan -->
                                    <label for="nomor_bpjskesehatan" class="col-form-label col-sm-2">Nomor BPJS
                                        Kesehatan</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control ktp-number" name="nomor_bpjskesehatan"
                                            maxlength="15" placeholder="Nomor BPJS Kesehatan...">
                                    </div>

                                    <!-- Nomor NPWP -->
                                    <label for="nomor_npwp" class="col-form-label col-sm-2">Nomor NPWP</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control npwp-number" name="nomor_npwp"
                                            placeholder="Nomor NPWP...">
                                    </div>

                                    <hr>
                                    <h5 class="mb-3">System Info</h5>
                                    <div class="row g-2">

                                        <div class="col-md-4 mb-2">
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
                                                        <input type="file" accept="images/jpg, images/png" id="foto"
                                                            name="foto">
                                                        <a href="#" class="avatar-selector-btn">
                                                            <i class="icofont icofont-pencil-alt-5"></i>
                                                        </a>
                                                    </div>
                                                    <!-- Browse Image to Upload Wrapper -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="f1-buttons">
                                            <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                            <button class="btn btn-primary btn-submit save-btn"
                                                type="button">Submit</button>
                                        </div>
                                    </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Import Excel --}}
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Pegawai dari Excel</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>

                </div>

                <form id="form-import" enctype="multipart/form-data">
                    <div class="d-flex justify-content-end me-2 mb-0">
                        <a href="{{ route('pegawai-download-template') }}" class="btn btn-success mt-2"
                            id="btn-download-template">
                            <span class="fa fa-download"></span> Download Template
                        </a>
                    </div>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="file_excel" name="file" accept=".xlsx,.xls">
                            <div class="form-text">Format: .xlsx atau .xls (Maksimal 10MB)</div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Perhatian:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pastikan format file sesuai template</li>
                                <li>Header harus sesuai dengan kolom database</li>
                                <li>Format tanggal: YYYY-MM-DD</li>
                            </ul>
                        </div>

                        <!-- Progress Bar (hidden by default) -->
                        <div class="progress d-none" id="import-progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%">0%</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-import-submit">
                            <span class="fa fa-upload"></span> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script></script>
    @include('sdm.pegawai.script')
    {{--
    <script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
    <script src="{{ asset('assets/js/form-wizard/jquery.backstretch.min.js') }}"></script> --}}
@endsection