@extends('layouts.simple.master')
@section('title', $title)

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
                            <span> Tambah</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive currency-table">
                                <table id="table_icd9" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">No SK</th>
                                            <th class="f-light">Tanggal Efektif Mulai</th>
                                            <th class="f-light">Tanggal Efektif Berakhir</th>
                                            <th class="f-light">Deskripsi</th>
                                            <th class="f-light">status</th>
                                            <th #">Action</th>
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
    <div class="modal fade" id="modal-sk-tarif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-wizard form-sk-tarif" novalidate="" autocomplete="off">
                        @csrf
                        {{-- Hidden Input --}}
                        <div class="mb-1 row">
                            <input type="hidden" name="id">
                        </div>
                        {{-- No SK --}}
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label" for="no_sk">No SK</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm" name="no_sk" type="text"
                                    placeholder="No SK..." required>
                            </div>
                        </div>
                        {{-- Tanggal Efektif Mulai --}}
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label" for="tgl_mulai">Tgl Mulai</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm js-datepicker digits" name="tgl_mulai"
                                    type="text" placeholder="Tanggal Efektif Mulai..." data-language="en" required>
                            </div>
                        </div>
                        {{-- Tanggal Efektif Berakhir --}}
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label" for="tgl_akhir">Tgl Berakhir</label>
                            <div class="col-sm-10">
                                <input class="form-control form-control-sm js-datepicker digits" name="tgl_akhir"
                                    type="text" placeholder="Tanggal Efektif Berakhir..." data-language="en" required>
                            </div>
                        </div>
                        {{-- Deskripsi --}}
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label" for="deskripsi">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control form-control-sm w-100" name="deskripsi" id="deskripsi" style="resize: none;"
                                    cols="3" required></textarea>
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
    @include('tarif.sk-tarif.script')
@endsection
