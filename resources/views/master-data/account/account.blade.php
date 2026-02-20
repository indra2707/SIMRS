@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <style>
        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 38px !important;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>

@endsection

@section('breadcrumb-title')
    <h3>Account</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ $menuTitle }}</li>
    <li class="breadcrumb-item active">{{ $menuSubtitle }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">My Profile</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#"
                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                        class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row mb-2">
                                    <div class="profile-title">
                                        <div class="media">
                                            <!-- <img class="img-70 rounded-circle" alt=""
                                                    src="http://127.0.0.1:8000/assets/images/user/7.jpg"> -->
                                            @if (Session::get('jenis_kelamin') == 'Laki-laki')
                                                <img class="b-r-10" src="{{ asset('assets/images/avatar/sample_l.png') }}"
                                                    alt="" width="75" height="75">
                                            @endif
                                            @if (Session::get('jenis_kelamin') == 'Perempuan')
                                                <img class="b-r-10" src="{{ asset('assets/images/avatar/sample_p.png') }}"
                                                    alt="" width="75" height="75">
                                            @endif
                                            <div class="media-body">
                                                <h5 class="mb-1" id="nama_pekerja3"></h5>
                                                <p id="rolle"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <input class="form-control" type="text" id="nik" placeholder="NIK..." readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" id="nama_pekerja" placeholder="Nama Pekerja..."
                                        readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="text" id="tanggal_lahir"
                                        placeholder="Tanggal Lahir..." readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email-Address</label>
                                    <input class="form-control" id="email" placeholder="Email..." readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat_domisili"
                                        placeholder="Alamat Tempat Tinggal..." readonly rows="3"></textarea>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Detail Profile -->
                <div class="col-xl-8">
                    <form class="card form-account" novalidate="" autocomplete="off">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Detail Profile</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#"
                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                        class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Hidden Input --}}
                                <div class="mb-2 row">
                                    <input type="hidden" id="id" name="id">
                                </div>

                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Company</label>
                                        <input class="form-control" type="text" id="lokasi_kerja" placeholder="Company..."
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <input class="form-control" id="status_kepegawaian" type="text"
                                            placeholder="Status..." readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Pekerja</label>
                                        <input class="form-control" type="text" id="nomor_pekerja"
                                            placeholder="Nomor Pekerja..." readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input class="form-control" id="nama_pekerja2" type="text"
                                            placeholder="Nama Lengkap..." readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input class="form-control js-datepicker digits" id="tanggal_lahir2"
                                            name="tanggal_lahir" type="text" placeholder="dd/mm/yyyy" aria-label="Date"
                                            data-language="en">
                                    </div>
                                </div>
                                <div class=" col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email-Address</label>
                                        <input class="form-control" type="text" id="email2" name="email"
                                            placeholder="Email...">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No Whatsapp</label>
                                        <input class="form-control phone-number" id="nomor_hp" name="nomor_hp" type="text"
                                            placeholder="+62 xxx xxx xxxx">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">No Kontak Darurat</label>
                                        <input class="form-control phone-number" id="nomor_kontak_darurat" type="text"
                                            name="nomor_kontak_darurat" placeholder="+62 xxx xxx xxxx">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kontak Darurat</label>
                                        <input class="form-control" type="text" id="nama_kontak_darurat"
                                            name="nama_kontak_darurat" placeholder="Nama Kontak Darurat...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Hubungan Kontak</label>
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
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" rows="4" id="alamat_domisili2" name="alamat_domisili"
                                            placeholder="Alamat ..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary save-btn" type="button">Update Profile</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('master-data.account.script')
@endsection