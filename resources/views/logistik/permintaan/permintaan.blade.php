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

        /* Layout chat flexible */
        .chat-box,
        .chat-right-aside,
        .chat {
            height: 100%;
        }

        .chat {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Chat history - area scrollable */
        .chat-history {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 10px;

            /* min-height: 200px; */
            max-height: none;
            /* Hapus batas tinggi */
        }

        /* Padding bawah dinamis untuk chat history */
        .chat-history::after {
            /* content: ''; */
            display: block;
            /* height: 20px; */
        }

        /* Chat message input area - fixed di bawah */
        .chat-message {
            flex-shrink: 0;
            background: #f8f9fa;
            padding: 6px 8px;
            border-top: 1px solid #e9ecef;
        }

        /* Container input pill */
        .input-pill-container {
            background: white;
            border-radius: 24px;
            padding: 8px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: flex-end;
            gap: 8px;
            transition: all 0.2s ease;
        }

        /* Textarea WhatsApp-style */
        #input-box {
            flex: 1;
            min-height: 38px;
            max-height: 120px;
            height: 38px;
            padding: 6px 8px;
            resize: none;
            overflow-y: auto;
            background: transparent;
            border: none;
            outline: none;
            font-size: 15px;
            line-height: 20px;
            padding: 10px 8px;
            font-family: inherit;
            box-shadow: none !important;
        }

        #input-box:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        #input-box::-webkit-scrollbar {
            width: 6px;
        }

        #input-box::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        /* Buttons */
        .chat-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .chat-btn:hover {
            transform: scale(1.05);
        }

        #emoji-btn {
            background: #f5f5f5;
            font-size: 20px;
        }

        #send-chat-btn {
            background: #0d6efd;
            color: white;
        }

        #send-chat-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Emoji picker */
        #emoji-picker {
            margin-top: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Chat messages styling */
        .chat-history ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .chat-history li {
            margin-bottom: 5px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar untuk chat history */
        .chat-history::-webkit-scrollbar {
            width: 8px;
        }

        .chat-history::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-history::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .chat-history::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Modal full height - PENTING! */
        #chatModal .modal-dialog {
            height: 90vh;
            max-height: 90vh;
        }

        #chatModal .modal-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #chatModal .modal-body {
            padding: 0;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #chatModal .card {
            height: 100%;
            margin: 0;
        }

        #chatModal .card-body {
            height: 100%;
            padding: 0 !important;
            display: flex;
            flex-direction: column;
        }

        #chatModal .chat-box {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #chatModal .chat-right-aside {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Chat header - fixed height */
        .chat-header {
            flex-shrink: 0;
            height: auto;
        }

        .chat-history {
            padding-bottom: 0px !important;
        }

        .chat-message {
            padding-top: 0px !important;
        }

        .card-body,
        .modal-body {
            padding-bottom: 0 !important;
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
                            <input type="text" id="no_agenda" name="no_agenda" class="form-control" maxlength="4"
                                required placeholder="Nomor Agenda...">
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
                                <option value="Tunda">Tunda</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>

                        <!-- Tembusan -->
                        <label for="tembusan" class="col-form-label col-sm-2">Tembusan</label>
                        <div class="col-sm-10">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="ICT"
                                        name="tembusan[]" value="ICT">
                                    <label class="form-check-label" for="ICT">ICT</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Teknik"
                                        name="tembusan[]" value="Teknik">
                                    <label class="form-check-label" for="Teknik">Teknik</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Alkes"
                                        name="tembusan[]" value="Alkes">
                                    <label class="form-check-label" for="Alkes">Alkes</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input tembusan-checkbox" type="checkbox" id="Umum"
                                        name="tembusan[]" value="Umum">
                                    <label class="form-check-label" for="Umum">Umum</label>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <label for="catatan" class="col-form-label col-sm-2">Catatan</label>
                        <div class="col-sm-10">
                            <textarea name="catatan" id="catatan" class="form-control" style="resize: none;" rows="3"
                                placeholder="Catatan..."></textarea>
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
    {{-- modal chat --}}
    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Chat</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="row chat-box">
                                <div class="col-12 pe-0 chat-right-aside">
                                    <div class="chat">
                                        <!-- Chat Header -->
                                        <div class="chat-header clearfix">
                                            <img class="rounded-circle" src="{{ asset('assets/images/user/8.jpg') }}"
                                                alt="">
                                            <div class="about">
                                                <div class="name">
                                                    <div id="nama_lengkap"></div>
                                                    <small class="text-muted" id="chatOpponentUsername"></small>
                                                </div>
                                                <div class="status" id="lastSeen"></div>
                                                <!-- Sudah langsung pakai id="lastSeen" di div status -->
                                            </div>
                                            <ul class="list-inline float-start float-sm-end chat-menu-icons">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="icon-search"></i></a>
                                                </li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="icon-clip"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="icon-headphone-alt"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="icon-video-camera"></i></a></li>
                                            </ul>
                                        </div>

                                        <!-- Chat History -->
                                        <div class="chat-history chat-msg-box custom-scrollbar">
                                            <ul>
                                                <li class="clearfix">
                                                    <div class="message my-message">
                                                        <img class="rounded-circle float-start chat-user-img img-30"
                                                            src="{{ asset('assets/images/user/3.png') }}" alt="">
                                                        <div class="message-data text-end">
                                                            <span class="message-data-time">10:12 am</span>
                                                        </div>
                                                        Are you there?
                                                    </div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="message other-message pull-right">
                                                        <img class="rounded-circle float-end chat-user-img img-30"
                                                            src="{{ asset('assets/images/user/12.png') }}" alt="">
                                                        <div class="message-data">
                                                            <span class="message-data-time">10:14 am</span>
                                                        </div>
                                                        Yes, I'm here!
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Chat Message Input -->
                                        <div class="chat-message clearfix">
                                            <div class="row m-0">
                                                <div class="col-12">
                                                    <!-- Emoji Picker -->
                                                    <div class="px-2">
                                                        <emoji-picker id="emoji-picker"
                                                            style="display: none; width: 100%; height: 350px;"></emoji-picker>
                                                    </div>

                                                    <!-- Input Container -->
                                                    <div class="input-pill-container mx-2 mb-2">
                                                        <button type="button" id="emoji-btn" class="chat-btn">
                                                            😊
                                                        </button>

                                                        <textarea id="input-box" class="form-control" placeholder="Ketik pesan..." rows="1"></textarea>

                                                        <button type="button" id="send-chat-btn" class="chat-btn">
                                                            <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('logistik.permintaan.script')
    <style>
        .chat-header .about .name .font-primary {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Smooth message appearance */
        .chat-history li {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
