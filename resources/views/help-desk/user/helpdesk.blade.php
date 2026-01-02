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
    <h3>Helpdesk</h3>
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
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-primary add-btn">
                                <span class="fa fa-plus"></span>
                                <span> Tambah Laporan</span>
                            </button>

                            <div class="bs-bars">
                                <input type="text" class="form-control js-daterangepicker text-center"
                                    style="width:220px" placeholder="dd/mm/yyyy - dd/mm/yyyy" data-language="en">
                            </div>
                        </div>
                        <input type="hidden" name="tgl_awal" id="tgl_awal">
                        <input type="hidden" name="tgl_akhir" id="tgl_akhir">


                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_helpdesk" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">No</th>
                                            <th class="f-light">Tiket</th>
                                            <th class="f-light">Judul Laporan</th>
                                            <th class="f-light">Kategori</th>
                                            <th class="f-light">Prioritas</th>
                                            <th class="f-light">Status</th>
                                            <th class="f-light">Tanggal Dibuat</th>
                                            <th class="f-light">Diterima Oleh</th>
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
    <div class="modal fade" id="helpdesk-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-2 form-helpdesk" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id">
                        <input id="f1-first-name" type="hidden" value="{{ Auth::user()->username }}" name="f1-first-name">
                        <input id="f1-last-name" type="hidden" name="department" value="{{ Auth::user()->role }}">

                        <!-- Judul Laporan  -->
                        <label for="judul_laporan" class="col-form-label col-sm-2">Judul Laporan</label>
                        <div class="col-sm-10">
                            <input class="form-control form-control" name="judul_laporan" type="text"
                                placeholder="Judul Laporan..." required>
                        </div>

                        <!-- Kategori Laporan  -->
                        <label for="kategori" class="col-form-label col-sm-2">Kategori Laporan</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control select2" name="kategori" required>
                                <option></option>
                                <option value="IT">IT / Sistem Informasi</option>
                                <option value="Medis">Peralatan Medis / Atem </option>
                                <option value="Teknik">Sarana & Prasarana / Teknik </option>
                            </select>
                        </div>

                        <!-- Prioritas  -->
                        <label for="prioritas" class="col-form-label col-sm-2">Prioritas</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control select2" name="prioritas" required>
                                <option></option>
                                <option value="Rendah">Rendah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Darurat">Darurat</option>
                            </select>
                        </div>

                        <!-- Deskripsi Masalah  -->
                        <label for="keterangan" class="col-form-label col-sm-2">Deskripsi Masalah</label>
                        <div class="col-sm-10">
                            <textarea class="form-control form-control" name="keterangan" id="keterangan" cols="50" rows="10" required
                                placeholder="Deskripsi Masalah..."></textarea>
                        </div>

                        {{-- ATTACH FILE --}}
                        <label class="col-sm-2 col-form-label">Lampiran</label>
                        <div class="col-sm-10">

                            <!-- Button Attach -->
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2 btn-attach" id="btn-attach">
                                <i class="fa fa-paperclip"></i> Attach File
                            </button>

                            <!-- Hidden Input -->
                            <input type="file" id="lampiran" name="lampiran[]" multiple
                                accept="image/jpeg,image/png" class="d-none">

                            <small class="text-muted btn-attach">
                                Maksimal 5 file (JPG / PNG)
                            </small>

                            {{-- PREVIEW --}}
                            <div class="row mt-2" id="preview-images"></div>
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

    <!-- Modal lihat foto -->
    <div class="modal fade" id="modal-preview-image" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title-view">Preview Gambar</h5> -->
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="preview-large" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

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
                                                            class="icon-search"></i></a></li>
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
                                                       
                                                    </div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="message other-message pull-right">
                                                        <img class="rounded-circle float-end chat-user-img img-30"
                                                            src="{{ asset('assets/images/user/12.png') }}" alt="">
                                                        <div class="message-data">
                                                            <span class="message-data-time">10:14 am</span>
                                                        </div>
                                                        
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
    @include('help-desk.user.script')

    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <script>
        $(document).ready(function() {
            var currentHelpdeskId = null;
            var chatChannel = null;

            // ✅ Get current user info
            var currentUserId = parseInt("{{ auth()->user()->id }}");
            var currentUsername = "{{ auth()->user()->username }}";

            console.log('🟢 User Chat Initialized', {
                userId: currentUserId,
                username: currentUsername
            });

            // ========== OPEN CHAT MODAL ==========
            $(document).on('click', '.btn-chat', function() {
                var helpdeskId = $(this).data('helpdesk-id');
                if (!helpdeskId) {
                    console.error('Helpdesk ID tidak ditemukan');
                    return;
                }
                currentHelpdeskId = helpdeskId;

                currentHelpdeskId = helpdeskId;
                console.log('📂 Opening chat for ticket:', helpdeskId);
                loadChatOpponent(helpdeskId);
                loadTicketInfo(helpdeskId);
                loadChatMessages(helpdeskId);
                initChatChannel(helpdeskId);

                $('#chatModal').modal('show');
            });

            // ========== LOAD TICKET INFO ==========
            function loadTicketInfo(helpdeskId) {
                // Load dari Bootstrap Table
                var rowData = $('#table_helpdesk').bootstrapTable('getRowByUniqueId', helpdeskId);

                if (rowData) {
                    $('#ticket-id').text('#' + rowData.id);
                    $('#ticket-department').text(rowData.department || '-');
                    $('#ticket-description').text(rowData.keterangan || '-');

                    // Format status badge
                    var statusBadge = '';
                    switch (rowData.status) {
                        case 'accept':
                            statusBadge = '<span class="badge bg-primary">Accepted</span>';
                            break;
                        case 'on-progress':
                            statusBadge = '<span class="badge bg-warning">In Progress</span>';
                            break;
                        case 'done':
                            statusBadge = '<span class="badge bg-success">Completed</span>';
                            break;
                        default:
                            statusBadge = '<span class="badge bg-secondary">Pending</span>';
                    }
                    $('#ticket-status').html(statusBadge);

                    // Format date
                    if (rowData.created_at) {
                        var date = new Date(rowData.created_at);
                        $('#ticket-created').text(date.toLocaleString('id-ID'));
                    }
                }
            }


            // Toggle emoji picker
            $(document).on('click', '#emoji-btn', function() {
                const picker = document.getElementById('emoji-picker');
                picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
            });

            // Tunggu picker siap baru attach event
            customElements.whenDefined('emoji-picker').then(() => {
                const picker = document.getElementById('emoji-picker');
                picker.addEventListener('emoji-click', event => {
                    const emoji = event.detail.unicode;
                    const input = document.getElementById('input-box');

                    const start = input.selectionStart;
                    const end = input.selectionEnd;
                    const text = input.value;
                    input.value = text.substring(0, start) + emoji + text.substring(end);

                    input.focus();
                    const newPos = start + emoji.length;
                    input.setSelectionRange(newPos, newPos);

                    picker.style.display = 'none';
                });
            });


            // ========== LOAD CHAT MESSAGES ==========
            function loadChatMessages(helpdeskId) {
                $.ajax({
                    url: '/user/chat/' + helpdeskId,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(messages) {
                        console.log('✅ Messages loaded:', messages.length, 'messages');
                        renderMessages(messages);
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        console.error('❌ Failed to load messages:', xhr);
                        $('.chat-history ul').html(
                            '<li class="text-center text-danger py-4">Failed to load messages</li>'
                        );
                    }
                });
            }

            // ========== RENDER MESSAGES ==========
            function renderMessages(messages) {
                var html = '';

                if (!messages || messages.length === 0) {
                    html = '<li class="text-center text-muted py-4">Belum ada pesan. Mulai percakapan!</li>';
                } else {
                    messages.forEach(function(msg) {
                        html += renderSingleMessage(msg);
                    });
                }

                $('.chat-history ul').html(html);
            }

            // ========== RENDER SINGLE MESSAGE ==========
            function renderSingleMessage(msg) {
                var messageUserId = parseInt(msg.user_id);
                var isMe = messageUserId === currentUserId;

                // Get sender info
                var senderName = msg.display_name ||
                    (msg.user && msg.user.nama_lengkap) ||
                    (msg.user && msg.user.username) ||
                    msg.sender_type || 'Support';

                var isAdmin = msg.is_admin || (msg.user && msg.user.role !== 'user');

                var time = new Date(msg.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Pastikan isi pesan di-escape dan newline diganti <br>
                var messageContent = escapeHtml(msg.message || '').replace(/\n/g, '<br>');

                var html = '';

                if (isMe) {
                    // PESAN DARI DIRI SENDIRI (kanan - hijau WhatsApp)
                    html = `
            <li class="clearfix" data-message-id="${msg.id}">
                <div style="
                    background-color: #DCF8C6;
                    color: #000;
                    padding: 9px 13px 10px 15px;
                    border-radius: 18px 18px 7px 18px;
                    max-width: 75%;
                    float: right;
                    clear: both;
                    margin: 4px 10px 12px 30px;
                    box-shadow: 0 1px 0.5px rgba(11,20,26,.13);
                    font-size: 15.8px;
                    line-height: 1.45;
                    position: relative;
                ">
                    <div style="word-wrap: break-word; margin-bottom: 12px;">${messageContent}</div>
                    <span style="font-size: 11.5px; color: #667781; position: absolute; bottom: 7px; right: 12px;">${time}</span>
                </div>
            </li>
        `;
                } else if (isAdmin) {
                    // PESAN ADMIN (kiri - hijau sama seperti punya kamu)
                    html = `
            <li class="clearfix" data-message-id="${msg.id}">
                <div style="
                    background-color: #DCF8C6;
                    color: #000;
                    padding: 10px 14px;
                    border-radius: 18px 18px 18px 7px;
                    max-width: 75%;
                    float: left;
                    clear: both;
                    margin: 6px 30px 12px 12px;
                    box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
                    font-size: 16px;
                    line-height: 1.45;
                    position: relative;
                ">
                    <div style="word-wrap: break-word; margin-bottom: 12px;">${messageContent}</div>
                    <span style="font-size: 11px; color: #888; position: absolute; bottom: 6px; right: 12px;">${time}</span>
                </div>
            </li>
        `;
                } else {
                    // PESAN DARI USER LAIN (kiri - abu-abu)
                    html = `
            <li class="clearfix" data-message-id="${msg.id}">
                <div class="message other-message" style="
                    background-color: #f1f1f1;
                    color: #333;
                    padding: 10px 15px;
                    border-radius: 15px;
                    display: inline-block;
                    max-width: 75%;
                    float: left;
                    clear: both;
                    margin-bottom: 10px;
                ">
                    <div class="message-data mb-1">
                        <span class="message-data-time" style="color: #999; font-size: 11px;">
                            ${senderName} • ${time}
                        </span>
                    </div>
                    <div style="word-wrap: break-word;">${messageContent}</div>
                </div>
            </li>
        `;
                }

                return html;
            }

            // ========== LOAD INFO LAWAN CHAT (ADMIN/SUPPORT) ==========
            function loadChatOpponent(helpdeskId) {
                $.ajax({
                    url: '/user/helpdesk/' + helpdeskId + '/info',
                    type: 'GET',
                    success: function(data) {
                        console.log('📋 Chat opponent data:', data);

                        if (data.success) {
                            // Update nama lengkap
                            $('#nama_lengkap').text(data.nama_lengkap || 'Support Team');

                            // Update username
                            $('#chatOpponentUsername').text(data.username ? '' + data.username : '');

                            // Update judul modal
                            $('.modal-title').text('Chat - ' + (data.judul_laporan || 'Support'));

                            // Update last seen dengan status
                            if (data.is_online) {
                                updateLastSeen('online');
                            } else if (data.last_seen) {
                                updateLastSeen(null, data.last_seen);
                            } else {
                                updateLastSeen();
                            }

                            // Update role badge jika ada
                            if (data.role && data.role !== 'user') {
                                updateAdminRoleBadge(data.role);
                            }
                        }
                    },
                    error: function() {
                        $('#nama_lengkap').text('Support Team');
                        $('#chatOpponentUsername').text('');
                        updateLastSeen(); // Tampilkan offline
                    }
                });
            }

            function updateAdminRoleBadge(role) {
                var badge = $('#admin-role-badge');

                if (!role || role === 'user') {
                    badge.hide();
                    return;
                }

                // Reset class
                badge.removeClass('bg-danger bg-primary bg-success bg-warning bg-info');

                // Set warna dan text berdasarkan role
                switch (role.toLowerCase()) {
                    case 'superadmin':
                        badge.addClass('bg-danger');
                        badge.text('Super Admin');
                        break;
                    case 'admin':
                        badge.addClass('bg-primary');
                        badge.text('Admin');
                        break;
                    case 'support':
                        badge.addClass('bg-success');
                        badge.text('Support');
                        break;
                    case 'it':
                        badge.addClass('bg-info');
                        badge.text('IT');
                        break;
                    case 'medis':
                        badge.addClass('bg-warning');
                        badge.text('Medis');
                        break;
                    case 'teknik':
                        badge.addClass('bg-secondary');
                        badge.text('Teknik');
                        break;
                    default:
                        badge.addClass('bg-secondary');
                        badge.text(role.toUpperCase());
                }

                badge.show(); // Tampilkan badge
            }

            // ✅ Function untuk set default info
            function setDefaultChatInfo() {
                $('#nama_lengkap').text('Support Team');
                $('#chatOpponentUsername').text('');
                $('#admin-role-badge').hide();
                // $('#lastSeen').text('Offline');
            }

            // ========== ESCAPE HTML ==========
            function escapeHtml(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            // ========== SEND MESSAGE ==========
            $(document).on('click', '#send-chat-btn', function() {
                sendMessage();
            });

            $(document).on('keypress', '#input-box', function(e) {
                if (e.which === 13 && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            function sendMessage() {
                var message = $('#input-box').val().trim();

                if (!message) {
                    return;
                }

                if (!currentHelpdeskId) {
                    console.error('❌ No helpdesk ID set');
                    alert('ID Helpdesk tidak valid');
                    return;
                }

                console.log('📤 Sending message:', message);

                $.ajax({
                    url: '/user/chat/' + currentHelpdeskId + '/send',
                    type: 'POST',
                    data: {
                        message: message,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#send-chat-btn').prop('disabled', true);
                    },
                    success: function(response) {
                        console.log('✅ Message sent:', response);

                        if (response.success) {
                            $('#input-box').val(''); // Clear input

                            // Append message to UI
                            if (response.data) {
                                appendMessage(response.data);
                            } else {
                                console.warn('⚠️ No data in response, reloading...');
                                loadChatMessages(currentHelpdeskId);
                            }
                        } else {
                            alert(response.message || 'Gagal mengirim pesan');
                        }
                    },
                    error: function(xhr) {
                        console.error('❌ Send message error:', xhr);

                        var errorMessage = 'Gagal mengirim pesan';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            errorMessage = 'Error: ' + xhr.status;
                        }

                        alert(errorMessage);
                    },
                    complete: function() {
                        $('#send-chat-btn').prop('disabled', false);
                        $('#input-box').focus();
                    }
                });
            }

            // ========== APPEND MESSAGE ==========
            function appendMessage(message) {
                // Check duplicate
                if ($('.chat-history ul li[data-message-id="' + message.id + '"]').length > 0) {
                    console.log('⚠️ Message already exists');
                    return;
                }

                console.log('📝 Appending message:', message.id);

                var chatList = $('.chat-history ul');

                // ✅ HAPUS PLACEHOLDER "Belum ada pesan" jika ada
                var placeholder = chatList.find('li.text-center.text-muted');
                if (placeholder.length > 0) {
                    console.log('🗑️ Removing placeholder message');
                    placeholder.remove();
                }

                // Tambah pesan baru
                var html = renderSingleMessage(message);
                chatList.append(html);
                scrollToBottom();

                // Play sound if from admin
                if (message.is_admin) {
                    playNotificationSound();
                }
            }

            // ========== SCROLL TO BOTTOM ==========
            function scrollToBottom() {
                setTimeout(function() {
                    var chatBox = $('.chat-history');
                    if (chatBox.length) {
                        chatBox.animate({
                            scrollTop: chatBox[0].scrollHeight
                        }, 300);
                    }
                }, 100);
            }

            // ========== INITIALIZE ECHO CHANNEL ==========
            function initChatChannel(helpdeskId) {
                // Leave previous channel
                if (chatChannel) {
                    console.log('⬅️ Leaving channel:', chatChannel);
                    window.Echo.leave(chatChannel);
                }

                chatChannel = 'chat.' + helpdeskId;
                console.log('🟢 USER JOINING CHANNEL:', chatChannel);

                // Subscribe to channel
                window.Echo.channel(chatChannel)
                    .listen('.MessageSent', function(e) {
                        console.log('🔔 NEW MESSAGE RECEIVED:', e);

                        if (e.message) {
                            appendMessage(e.message);

                            // Update status indicator if from admin
                            if (e.message.is_admin) {
                                $('#support-status').html('<span class="text-success">● Active</span>');
                            }
                        }
                    });

                console.log('✅ Echo channel initialized');
            }

            // ========== CLEAN UP ON MODAL CLOSE ==========
            $('#chatModal').on('hidden.bs.modal', function() {
                console.log('❌ Chat modal closed');

                if (chatChannel) {
                    window.Echo.leave(chatChannel);
                    chatChannel = null;
                }

                currentHelpdeskId = null;
                $('.chat-history ul').html('');
                $('#input-box').val('');
            });

            // ========== NOTIFICATION SOUND ==========
            function playNotificationSound() {
                try {
                    var audio = new Audio(
                        'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBzKM0fPTgjMGHm7A7+OZQQ0PVKXh8bhnHQQ4lNXzzn8rBSN0x+/glkAKE16y6OuoVhMJR53e8L9uIQcxjM7z04U2Bhxqvu7mnUIND1Ol4PG4aB4ENpPU8tGAKgUjcsXv45hCDBBbr+frq1kUCUWZ2+/CcSMGMIrL8daIOQcZZrfs6KFODwxPoup8tWYdBDGPzvLPgysFI3DD7+adQgsQ'
                    );
                    audio.play().catch(function(e) {
                        console.log('🔇 Cannot play sound:', e);
                    });
                } catch (e) {
                    console.log('🔇 Audio error:', e);
                }
            }
            const inputBox = document.getElementById('input-box');
            const chatHistory = document.querySelector('.chat-history');

            if (inputBox && chatHistory) {

                // Function untuk adjust chat history height
                function adjustChatHeight() {
                    const inputHeight = inputBox.scrollHeight;
                    const modalBody = document.querySelector('#chatModal .modal-body');
                    const chatHeader = document.querySelector('.chat-header');
                    const chatMessage = document.querySelector('.chat-message');

                    if (modalBody && chatHeader && chatMessage) {
                        const modalHeight = modalBody.offsetHeight;
                        const headerHeight = chatHeader.offsetHeight;
                        const messageHeight = chatMessage.offsetHeight;

                        // Calculate available height untuk chat history
                        const availableHeight = modalHeight - headerHeight - messageHeight;
                        // 40px buffer

                        chatHistory.style.maxHeight = availableHeight + 'px';

                        // Auto scroll ke bawah
                        setTimeout(() => {
                            chatHistory.scrollTop = chatHistory.scrollHeight;
                        }, 50);
                    }
                }

                // Auto-resize textarea DENGAN adjust chat height
                inputBox.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';

                    if (this.scrollHeight > 160) {
                        this.style.height = '160px';
                        this.style.overflowY = 'auto';
                    } else {
                        this.style.overflowY = 'hidden';
                    }

                    // KUNCI: Adjust chat height setiap kali textarea berubah
                    adjustChatHeight();
                });

                // Shift + Enter = baris baru, Enter = kirim
                inputBox.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Adjust saat modal dibuka
                $('#chatModal').on('shown.bs.modal', function() {
                    adjustChatHeight();
                    inputBox.focus();
                });

                // Adjust saat window resize
                $(window).on('resize', function() {
                    if ($('#chatModal').hasClass('show')) {
                        adjustChatHeight();
                    }
                });

                // Override fungsi sendMessage yang sudah ada
                const originalSendMessage = window.sendMessage || sendMessage;
                window.sendMessage = function() {
                    const message = $('#input-box').val().trim();
                    if (!message) return;

                    $.ajax({
                        url: '/admin/chat/' + currentHelpdeskId + '/send',
                        type: 'POST',
                        data: {
                            message: message,
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            $('#send-chat-btn').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#input-box').val('');
                                inputBox.style.height = '40px';
                                inputBox.style.overflowY = 'hidden';

                                if (response.data) {
                                    appendMessage(response.data);
                                }

                                // KUNCI: Adjust height setelah kirim
                                adjustChatHeight();
                            } else {
                                alert(response.message || 'Gagal mengirim pesan');
                            }
                        },
                        error: function() {
                            alert('Gagal mengirim pesan');
                        },
                        complete: function() {
                            $('#send-chat-btn').prop('disabled', false);
                            inputBox.focus();
                        }
                    });
                };
            }
        });
    </script>
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

    <!-- Initialize Laravel Echo (Pusher/Reverb) -->
    {{--
    <script>
        if (typeof window.Echo === 'undefined') {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: 'local',
                wsHost: 'simrs.local',
                wsPort: 6001,
                forceTLS: false,
                encrypted: false,
                disableStats: true
            });

            console.log('✅ Laravel Echo initialized');
        }
    </script> --}}

    <!-- Pastikan Laravel Echo sudah di-initialize (tambahkan jika belum ada) -->

    {{--
    <script>
        // Initialize Echo hanya sekali di halaman
        if (typeof window.Echo === 'undefined') {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: 'local',
                wsHost: 'simrs.local',
                wsPort: 6001,
                forceTLS: false,
                encrypted: false,
                disableStats: true
            });
        }
    </script> --}}
@endsection
