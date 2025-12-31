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

        .input-group .btn {
            z-index: 1;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .input-group:hover {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .chat-history .message {
            font-size: 1.4em !important;
            line-height: 1.5;
            padding: 12px 16px;
        }

        .chat-history .message .message-data-time {
            font-size: 0.65em !important;
            opacity: 0.9;
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

    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chat</h5>

                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-chat">

                        <div class="card">
                            <div class="card-body p-0">
                                <div class="row chat-box">
                                    <!-- Chat right side start-->
                                    <div class="col pe-0 chat-right-aside">
                                        <!-- chat start-->
                                        <div class="chat">
                                            <!-- chat-header start-->
                                            <div class="chat-header clearfix"><img class="rounded-circle"
                                                    src="{{ asset('assets/images/user/8.jpg') }}" alt="">
                                                <div class="about">
                                                    <div class="name">
                                                        <div id="nama_lengkap"></div>
                                                        <small class="text-muted" id="chatOpponentUsername"></small>
                                                    </div>
                                                    <div class="status" id="lastSeen"></div>
                                                </div>
                                                <ul class="list-inline float-start float-sm-end chat-menu-icons">
                                                    <!-- <li class="list-inline-item"><a href="#"><i class="icon-search"></i></a>
                                                                                                                    </li>
                                                                                                                    <li class="list-inline-item"><a href="#"><i class="icon-clip"></i></a>
                                                                                                                    </li>
                                                                                                                    <li class="list-inline-item"><a href="#"><i
                                                                                                                                class="icon-headphone-alt"></i></a></li>
                                                                                                                    <li class="list-inline-item"><a href="#"><i
                                                                                                                                class="icon-video-camera"></i></a></li>
                                                                                                                    <li class="list-inline-item toogle-bar"><a href="#"><i
                                                                                                                                class="icon-menu"></i></a></li> -->
                                                </ul>
                                            </div>
                                            <!-- chat-header end-->
                                            <div class="chat-history chat-msg-box custom-scrollbar">
                                                <ul>
                                                    <li class="clearfix">
                                                        <div class="message my-message"
                                                            style="background-color: #0d6efd; color: white; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 75%;">
                                                            <img class="rounded-circle float-start chat-user-img img-30"
                                                                src="{{ asset('assets/images/user/3.png') }}"
                                                                alt="">
                                                            <div class="message-data text-end">
                                                                <span class="message-data-time"
                                                                    style="color: #e0e0e0;">10:12 am</span>
                                                            </div>
                                                            Are we meeting today? Project has been already finished and I
                                                            have results to show you.
                                                        </div>
                                                    </li>
                                                    <li class="clearfix">
                                                        <div class="message other-message pull-right"
                                                            style="background-color: #0d6efd; color: white; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 75%;">
                                                            <img class="rounded-circle float-end chat-user-img img-30"
                                                                src="{{ asset('assets/images/user/12.png') }}"
                                                                alt="">
                                                            <div class="message-data">
                                                                <span class="message-data-time"
                                                                    style="color: #e0e0e0;">10:14 am</span>
                                                            </div>
                                                            Well I am not sure. The rest of the team is not here yet. Maybe
                                                            in an hour or so?
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- end chat-history-->
                                            <div class="chat-message clearfix">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <!-- Bar input chat menyatu (rounded pill) -->
                                                        <div class="d-flex align-items-center bg-white border rounded-pill shadow-sm p-1"
                                                            style="height: 50px;">
                                                            <!-- Tombol Emoji -->
                                                            <button type="button" id="emoji-btn"
                                                                class="btn btn-light rounded-pill mx-1"
                                                                style="height: 40px; width: 40px; display: flex; align-items: center; justify-content: center;">
                                                                <span style="font-size: 20px;">😊</span>
                                                            </button>

                                                            <!-- Input teks -->
                                                            <input type="text" id="input-box"
                                                                class="form-control border-0 flex-grow-1 mx-2"
                                                                placeholder="Masukkan teks..."
                                                                style="height: 40px; background: transparent; outline: none; box-shadow: none;">

                                                            <!-- Tombol Kirim -->
                                                            <button type="button" id="send-chat-btn"
                                                                class="btn btn-primary rounded-pill mx-1"
                                                                style="height: 40px; width: 40px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fa fa-paper-plane"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Emoji Picker (di bawah bar input) -->
                                                        <div class="mt-3">
                                                            <emoji-picker id="emoji-picker"
                                                                style="display: none; width: 100%; height: 350px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);"></emoji-picker>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end chat-message-->
                                            <!-- chat end-->
                                            <!-- Chat right side ends-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
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
                var senderName = msg.display_name || msg.user?.nama_lengkap || msg.user?.username || msg
                    .sender_type || 'Support';
                var isAdmin = msg.is_admin || (msg.user && msg.user.role !== 'user');

                var time = new Date(msg.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                var html = '';

                if (isMe) {
                    // Tampilkan "You (username)" — username diambil dari currentUsername
                    var usernameDisplay = currentUsername ? `(${currentUsername})` : '';
                    html = `
            <li class="clearfix" data-message-id="${msg.id}">
                <div class="message my-message" style="background-color: #0d6efd; color: white; padding: 10px 15px; border-radius: 15px; display: inline-block; max-width: 75%; float: right; clear: both; margin-bottom: 10px;">
                    <div style="text-align: right;">${escapeHtml(msg.message)} <span style="font-size: 11px; color: #e0e0e0;">${time}</span></div>
                    <div class="message-data text-end mb-1">
                       
                    </div>
                </div>
            </li>
        `;
                } else if (isAdmin) {
                    // Admin/Support message (kiri - hijau)
                    html = `
            <li class="clearfix" data-message-id="${msg.id}">
                <div class="message other-message" style="background-color: #28a745; color: white; padding: 10px 15px; border-radius: 15px; display: inline-block; max-width: 75%; float: left; clear: both; margin-bottom: 10px;">
                   <span style="font-size: 11px; color: #e0e0e0;">${time}</span> ${escapeHtml(msg.message)} 
                    <div class="message-data text-end mb-1">
                            
                    </div>
                </div>
            </li>
        `;
                } else {
                    // Other user message (kiri - abu-abu) - jarang terjadi
                    html = `
                                                                                            <li class="clearfix" data-message-id="${msg.id}">
                                                                                                <div class="message other-message" style="background-color: #f1f1f1; color: #333; padding: 10px 15px; border-radius: 15px; display: inline-block; max-width: 75%; float: left; clear: both; margin-bottom: 10px;">
                                                                                                    <div class="message-data mb-1">
                                                                                                        <span class="message-data-time" style="color: #999; font-size: 11px;">
                                                                                                            ${senderName} • ${time}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    ${escapeHtml(msg.message)}
                                                                                                </div>
                                                                                            </li>
                                                                                        `;
                }

                return html;
            }

            // ========== LOAD INFO LAWAN CHAT (ADMIN/SUPPORT) ==========
            function loadChatOpponent(helpdeskId) {
                $.ajax({
                    url: '/user/helpdesk/' + helpdeskId + '/info', // route baru
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            $('#nama_lengkap').text(data.nama_lengkap || 'Support');
                            $('#chatOpponentUsername').text(data.username ? '@' + data.username : '');
                            $('.modal-title').text('Chat - ' + data.judul_laporan);

                        }
                    },
                    error: function() {
                        $('#nama_lengkap').text('Support');
                        $('#chatOpponentUsername').text('');
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
                $('#lastSeen').text('Offline');
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

                var html = renderSingleMessage(message);
                $('.chat-history ul').append(html);
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
        });
    </script>

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
