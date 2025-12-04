@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')

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
                        <button class="btn btn-primary add-btn">
                            <span class="fa fa-plus"></span>
                            <span> Tambah Data</span>
                        </button>
                        {{-- Table View --}}
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="table-responsive signal-table">
                                <table id="table_helpdesk" class="table table-hover" data-buttons-class="primary"
                                    data-toggle="table">
                                    <thead class="text-bold text-white text-uppercase text-center">
                                        <tr>
                                            <th class="f-light">id</th>
                                            <th class="f-light">keterangan</th>
                                            <th class="f-light">tanggal</th>
                                            <th class="f-light">Status</th>
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
    <div class="modal fade" id="helpdesk-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-helpdesk" method="post" action="{{ route('user.helpdesk-store') }}">

                        @csrf
                        <div class="mb-2">
                            <label for="f1-first-name">Username</label>
                            <input class="form-control" id="f1-first-name" type="text"
                                value="{{ Auth::user()->username }}" name="f1-first-name" disabled required>
                        </div>
                        <div class="mb-2">
                            <label for="f1-last-name">Department</label>
                            <input class="f1-last-name form-control" id="f1-last-name" type="text" name="department"
                                value="{{ Auth::user()->role }}" disabled required>
                        </div>

                        <div class="mb-2">
                            <label for="">Keterangan</label>
                            <textarea class="f1-last-name form-control" name="keterangan" id="keterangan" cols="50" rows="10"></textarea>
                        </div>
                        <div class="f1-buttons d-flex justify-content-end mb-2 mt-4">
                            <button class="btn btn-primary save-btn" type="button">Submit</button>
                        </div>


                    </form>
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
                                                    <div class="name">Kori Thomas  <span
                                                            class="font-primary f-12">Typing...</span></div>
                                                    <div class="status">Last Seen 3:55 PM</div>
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
                                                    <li class="list-inline-item toogle-bar"><a href="#"><i
                                                                class="icon-menu"></i></a></li>
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
                                                    <div class="col-xl-12 d-flex align-items-center">
                                                        <i class="fa fa-comment text-primary"
                                                            style="font-size: 28px; margin-right: 10px;"></i>
                                                        <div style="display: flex; gap: 0; width: 100%;">
                                                            <input type="text" id="input-box" name="input-box"
                                                                placeholder="Masukkan teks..."
                                                                style="flex: 1; border-radius: 0.25rem 0 0 0.25rem; margin: 0; padding: 8px;">
                                                            <button type="button" id="send-chat-btn"
                                                                style="border-radius: 0 0.25rem 0.25rem 0; margin: 0; padding: 0 12px; display: flex; align-items: center; justify-content: center; background-color: #0d6efd; color: white; border: none;">
                                                                <i class="fa fa-paper-plane"
                                                                    style="margin: 0; padding: 0;"></i>
                                                            </button>
                                                        </div>



                                                    </div>

                                                </div>
                                            </div>
                                            <!-- end chat-message-->
                                            <!-- chat end-->
                                            <!-- Chat right side ends-->
                                        </div>
                                    </div>
                                    <div class="col ps-0 chat-menu">

                                        <div class="tab-content" id="info-tabContent">
                                            <div class="tab-pane fade show active" id="info-home" role="tabpanel"
                                                aria-labelledby="info-home-tab">
                                                <div class="people-list">
                                                    <div class="user-profile">
                                                        <div class="image">
                                                            <div class="avatar text-center"><img alt=""
                                                                    src="{{ asset('assets/images/user/2.jpg') }}"></div>
                                                            <div class="icon-wrapper"><i
                                                                    class="icofont icofont-pencil-alt-5"></i></div>
                                                        </div>
                                                        <div class="user-content text-center">
                                                            <h5 class="text-uppercase">mark jenco</h5>
                                                            <div class="social-media">
                                                                <ul class="list-inline">
                                                                    <li class="list-inline-item"><a
                                                                            href="https://www.facebook.com/"
                                                                            target="_blank"><i
                                                                                class="fa fa-facebook"></i></a>
                                                                    </li>
                                                                    <li class="list-inline-item"><a
                                                                            href="https://accounts.google.com/"
                                                                            target="_blank"><i
                                                                                class="fa fa-google-plus"></i></a></li>
                                                                    <li class="list-inline-item"><a
                                                                            href="https://twitter.com/" target="_blank"><i
                                                                                class="fa fa-twitter"></i></a></li>
                                                                    <li class="list-inline-item"><a
                                                                            href="https://www.instagram.com/"
                                                                            target="_blank"><i
                                                                                class="fa fa-instagram"></i></a></li>
                                                                    <li class="list-inline-item"><a
                                                                            href="https://rss.app/" target="_blank"><i
                                                                                class="fa fa-rss"></i></a></li>
                                                                </ul>
                                                            </div>
                                                            <hr>
                                                            <div class="follow text-center">
                                                                <div class="row">
                                                                    <div class="col border-right"><span>Following</span>
                                                                        <div class="follow-num">236k</div>
                                                                    </div>
                                                                    <div class="col"><span>Follower</span>
                                                                        <div class="follow-num">3691k</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="text-center">
                                                                <p class="mb-0">Mark.jecno23@gmail.com</p>
                                                                <p class="mb-0">+91 365 - 658 - 1236</p>
                                                                <p class="mb-0">Fax: 123-4560</p>
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

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('help-desk.user.script')

    <script>
        $(document).ready(function() {
            var currentHelpdeskId = null;
            var chatChannel = null;

            // ========== CHAT FUNCTIONALITY ==========

            // Open Chat Modal ketika tombol chat diklik
            $(document).on('click', '.btn-chat', function() {
                var helpdeskId = $(this).data('helpdesk-id');
                if (!helpdeskId) {
                    console.error('Helpdesk ID tidak ditemukan');
                    return;
                }

                currentHelpdeskId = helpdeskId;
                console.log('Opening chat for ticket:', helpdeskId);

                loadTicketInfo(helpdeskId);
                loadChatMessages(helpdeskId);
                initChatChannel(helpdeskId);

                $('#chatModal').modal('show');
            });

            // Load Ticket Info dari table row
            function loadTicketInfo(helpdeskId) {
                // Cari data dari table bootstrap
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

                    // Format created date
                    if (rowData.created_at) {
                        var date = new Date(rowData.created_at);
                        $('#ticket-created').text(date.toLocaleString('id-ID'));
                    }
                }
            }

            // Load Chat Messages
            function loadChatMessages(helpdeskId) {
                $.ajax({
                    url: '/user/chat/' + helpdeskId,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(messages) {
                        console.log('Messages loaded:', messages.length, 'messages');
                        renderMessages(messages);
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        console.error('Failed to load messages:', xhr);
                        $('.chat-history ul').html(
                            '<li class="text-center text-danger py-4">Failed to load messages</li>');
                    }
                });
            }

            // Render Messages
            function renderMessages(messages) {
                var html = '';

                if (!messages || messages.length === 0) {
                    html = '<li class="text-center text-muted py-4">No messages yet. Start the conversation!</li>';
                } else {
                    messages.forEach(function(msg) {
                        html += renderSingleMessage(msg);
                    });
                }

                $('.chat-history ul').html(html);
            }

            // Render Single Message
            function renderSingleMessage(msg) {
                var isUser = msg.sender_type === 'user';
                var time = new Date(msg.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                var html = '';

                if (isUser) {
                    // User message (kanan - biru)
                    html = `
                <li class="clearfix" data-message-id="${msg.id}">
                    <div class="message my-message" style="background-color: #0d6efd; color: white; padding: 10px 15px; border-radius: 15px; display: inline-block; max-width: 75%; float: right; clear: both; margin-bottom: 5px;">
                        <div class="message-data text-end mb-1">
                            <span class="message-data-time" style="color: #e0e0e0; font-size: 11px;">You • ${time}</span>
                        </div>
                        <div style="text-align: left;">${escapeHtml(msg.message)}</div>
                    </div>
                </li>
            `;
                } else {
                    // Admin message (kiri - hijau)
                    html = `
                <li class="clearfix" data-message-id="${msg.id}">
                    <div class="message other-message" style="background-color: #28a745; color: white; padding: 10px 15px; border-radius: 15px; display: inline-block; max-width: 75%; float: left; clear: both; margin-bottom: 5px;">
                        <div class="message-data mb-1">
                            <span class="message-data-time" style="color: rgba(255,255,255,0.8); font-size: 11px;">Support Team • ${time}</span>
                        </div>
                        ${escapeHtml(msg.message)}
                    </div>
                </li>
            `;
                }

                return html;
            }

            // Escape HTML untuk keamanan
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

            // Send Message ketika tombol diklik
            $(document).on('click', '#send-chat-btn', function() {
                sendMessage();
            });

            // Send Message ketika Enter ditekan
            $(document).on('keypress', '#input-box', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    sendMessage();
                }
            });

            // Function untuk mengirim pesan
            function sendMessage() {
                var message = $('#input-box').val().trim();

                if (!message) {
                    console.log('Message is empty');
                    return;
                }

                if (!currentHelpdeskId) {
                    console.error('No helpdesk ID set');
                    Alert('error', 'ID Helpdesk tidak valid');
                    return;
                }

                console.log('Sending message:', message);

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
                        console.log('Message sent:', response);

                        if (response.success) {
                            $('#input-box').val(''); // Clear input

                            // Tambahkan pesan langsung ke UI
                            if (response.data) {
                                appendMessage(response.data);
                            }
                        } else {
                            Alert('error', response.message || 'Gagal mengirim pesan');
                        }
                    },
                    error: function(xhr) {
                        console.error('Send message error:', xhr);

                        var errorMessage = 'Gagal mengirim pesan';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            errorMessage = 'Error: ' + xhr.status;
                        }

                        Alert('error', errorMessage);
                    },
                    complete: function() {
                        $('#send-chat-btn').prop('disabled', false);
                        $('#input-box').focus();
                    }
                });
            }

            // Append new message (untuk realtime)
            function appendMessage(message) {
                // Cek apakah pesan sudah ada (avoid duplicate)
                if ($('.chat-history ul li[data-message-id="' + message.id + '"]').length > 0) {
                    console.log('Message already exists, skipping');
                    return;
                }

                var html = renderSingleMessage(message);
                $('.chat-history ul').append(html);
                scrollToBottom();

                // Play sound jika pesan dari admin
                if (message.sender_type === 'admin') {
                    playNotificationSound();
                }
            }

            // Scroll to bottom
            function scrollToBottom() {
                setTimeout(function() {
                    var chatBox = $('.chat-history');
                    chatBox.animate({
                        scrollTop: chatBox[0].scrollHeight
                    }, 300);
                }, 100);
            }

            // Initialize Laravel Echo untuk REALTIME CHAT
            // Replace bagian initChatChannel di JavaScript user dengan ini:

            // Initialize Laravel Echo untuk REALTIME CHAT
            function initChatChannel(helpdeskId) {
                // Leave previous channel jika ada
                if (chatChannel) {
                    console.log('🔴 Leaving previous channel:', chatChannel);
                    window.Echo.leave(chatChannel);
                }

                chatChannel = 'chat.' + helpdeskId;
                console.log('🟢 USER JOINING CHANNEL:', chatChannel);
                console.log('🟢 Echo instance:', window.Echo);

                // Subscribe to channel
                var channel = window.Echo.channel(chatChannel);

                console.log('🟢 Channel object:', channel);

                channel.listen('.MessageSent', function(e) {
                        console.log('🔔 NEW MESSAGE RECEIVED (REALTIME):', e);
                        console.log('🔔 Message details:', {
                            id: e.message.id,
                            sender_type: e.message.sender_type,
                            message: e.message.message,
                            created_at: e.message.created_at
                        });

                        // Update status indicator
                        if (e.message.sender_type === 'admin') {
                            console.log('✅ Message from ADMIN detected');
                            $('#support-status').html('<span class="text-success">● Active</span>');
                        } else {
                            console.log('✅ Message from USER detected');
                        }

                        // Append new message
                        if (e.message) {
                            console.log('📝 Appending message to UI...');
                            appendMessage(e.message);
                        }
                    })
                    .error(function(error) {
                        console.error('❌ Echo channel error:', error);
                    });

                // Listen to all events for debugging
                channel.listenToAll((event, data) => {
                    console.log('📡 All events:', event, data);
                });

                console.log('✅ Echo channel initialized for USER');

                // Test connection
                setTimeout(function() {
                    console.log('🔍 Checking channel subscription...');
                    console.log('🔍 Current channels:', window.Echo.connector.channels);
                }, 2000);
            }

            // Juga tambahkan ini di bagian atas script untuk monitoring Echo connection
            window.Echo.connector.pusher.connection.bind('connected', function() {
                console.log('✅ Pusher Connected!');
            });

            window.Echo.connector.pusher.connection.bind('disconnected', function() {
                console.log('❌ Pusher Disconnected!');
            });

            window.Echo.connector.pusher.connection.bind('error', function(err) {
                console.error('❌ Pusher Error:', err);
            });

            // Log semua channel subscriptions
            window.Echo.connector.pusher.bind_global(function(eventName, data) {
                console.log('📡 Global Pusher Event:', eventName, data);
            });

            // Clean up when modal closed
            $('#chatModal').on('hidden.bs.modal', function() {
                console.log('Chat modal closed');

                // Leave Echo channel
                if (chatChannel) {
                    window.Echo.leave(chatChannel);
                    chatChannel = null;
                    console.log('Left chat channel');
                }

                // Reset
                currentHelpdeskId = null;
                $('.chat-history ul').html('');
                $('#input-box').val('');
                $('#support-status').text('Online');
            });

            // Notification sound
            function playNotificationSound() {
                try {
                    var audio = new Audio(
                        'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBzKM0fPTgjMGHm7A7+OZQQ0PVKXh8bhnHQQ4lNXzzn8rBSN0x+/glkAKE16y6OuoVhMJR53e8L9uIQcxjM7z04U2Bhxqvu7mnUIND1Ol4PG4aB4ENpPU8tGAKgUjcsXv45hCDBBbr+frq1kUCUWZ2+/CcSMGMIrL8daIOQcZZrfs6KFODwxPoup8tWYdBDGPzvLPgysFI3DD7+adQgsQ'
                    );
                    audio.play().catch(function(e) {
                        console.log('Cannot play sound:', e);
                    });
                } catch (e) {
                    console.log('Audio error:', e);
                }
            }
        });
    </script>

    <!-- Pastikan Laravel Echo sudah di-initialize (tambahkan jika belum ada) -->

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
    </script>
@endsection
