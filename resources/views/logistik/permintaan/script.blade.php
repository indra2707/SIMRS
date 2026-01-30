<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-permintaan"),
        allowClear: true
    });

    // Tabel
    var $tablePermintaan = $('#table_permintaan');

    // $('#modal-permintaan').on('hidden.bs.modal', function () {
    //     // Reset form validation
    //     $('.form-permintaan').removeClass('was-validated');
    //     $('.form-permintaan input[type="text"]').val('');
    //     $('.form-permintaan input[type="hidden"]').val('');
    //     $('.form-permintaan textarea').val('');
    //     $('.form-permintaan select').val(null).trigger('change');

    //     if ($("select[name='id_unit[]']").hasClass("select2-hidden-accessible")) {
    //         $("select[name='id_unit[]']").select2('destroy');
    //     }
    //     $('.tembusan-checkbox').prop('checked', false);
    //     $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
    //     console.log('Modal closed - Form cleared');
    // });


    $(document).on('click', '.add-btn', function() {
        $('.form-permintaan').removeClass('was-validated');
        $('#modal-permintaan').modal('show');
        $('.modal-title').text('Form Tambah Permintaan');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

        $('input[name="id"]').val('');
        $('input[name="no_surat"]').val('');
        $('input[name="no_agenda"]').val('');
        $('input[name="nama_permintaan"]').val('');
        $('input[name="tgl"]').val('');
        $('select[name="status"]').val('Pengajuan Panjar').trigger('change');
        $('textarea[name="catatan"]').val('');
        $('select[name="id_unit[]"]').val(null).trigger('change');
        $('.tembusan-checkbox').prop('checked', false);

        InitSelect2($("select[name='id_unit[]']"), {
            url: "{{ route('get-select-unit') }}",
            dropdownParent: $("#modal-permintaan")
        });
    });

    // Save Permintaan
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();

        if (id) {
            var url = "{{ route('logistik.permintaan.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('logistik.permintaan.create') }}";
            var type = "POST";
        }

        var forms = document.getElementsByClassName('form-permintaan');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                var formData = $('.form-permintaan').serialize();
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: formData,

                    beforeSend: function() {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },

                    complete: function() {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success === true) {
                            Alert('success', res.message);
                            $('#modal-permintaan').modal('hide');
                            $tablePermintaan.bootstrapTable('refresh');
                        }
                    },

                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                message = Object.values(errors).flat().join('<br>');
                            } else {
                                message = xhr.responseJSON?.message || 'Validasi gagal';
                            }
                        } else if (xhr.status === 500) {
                            message = 'Kesalahan server';
                        }

                        $.notify({
                            title: 'Peringatan',
                            message: message
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            delay: 3000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    $(function() {
        initTable();
    });

    // Table PERMINTAAN
    function initTable() {
        $tablePermintaan.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: false,
            showExport: false,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('logistik.permintaan.view') }}",
            columns: [
                [{
                        title: 'No',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        width: 50,
                        formatter: function(value, row, index) {
                            return index + 1
                        }
                    },
                    {
                        field: 'no_agenda',
                        title: 'No Agenda',
                        sortable: true,
                    },
                    {
                        field: 'no_surat',
                        title: 'No Surat',
                        sortable: true,
                    },
                    {
                        field: 'nama_permintaan',
                        title: 'Nama Permintaan',
                        sortable: true,
                    },
                    {
                        field: 'tgl',
                        title: 'Tanggal',
                        sortable: true,
                    },
                    {
                        field: 'unit',
                        title: 'Unit',
                        sortable: true,
                    },
                    {
                        field: 'tembusan',
                        title: 'Tembusan',
                        sortable: true,
                        visible: false,
                    },
                    {
                        field: 'catatan',
                        title: 'Catatan',
                        sortable: true,
                        visible: false,
                    },
                    {
                        field: 'status',
                        title: 'Status',
                        sortable: true,
                        align: 'center',
                        formatter: function(value, row, index) {
                            let btnClass = 'btn-success';
                            if (value === 'Pengajuan Panjar') {
                                btnClass = 'btn-primary';
                            } else if (value === 'Pengadaan') {
                                btnClass = 'btn-warning';
                            } else if (value === 'Serah Terima') {
                                btnClass = 'btn-info';
                            }
                            return `<button class="btn btn-pill btn-xs ${btnClass} text-center" style="width: 140px;"> ${value} </button>`;
                        }
                    },
                    {
                        field: 'action',
                        title: 'Action',
                        align: 'center',
                        valign: 'middle',
                        width: 100,
                        clickToSelect: false,
                        events: window.eventsPermintaan,
                        formatter: actionsFunctionPermintaan
                    }
                ]
            ],
            responseHandler: function(data) {
                console.log('Response data:', data);
                return data;
            }
        });
    }

    function actionsFunctionPermintaan(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end">',
            `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-permintaan-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    window.eventsPermintaan = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-permintaan').modal('show');
            $('.modal-title').text('Form Edit Permintaan');
            $('input[name="id"]').val(row.id);
            $('input[name="no_surat"]').val(row.no_surat);
            $('input[name="no_agenda"]').val(row.no_agenda);
            $('input[name="tanggal"]').val(row.tanggal);
            $('select[name="status"]').val(row.status).trigger('change');
            $('input[name="nama_permintaan"]').val(row.nama_permintaan);
            $('textarea[name="catatan"]').val(row.catatan);
            $('input[name="tgl"]').val(row.tgl);

            $('input[name="status"]').val(row.status);

            // Parse id_unit
            let selectedUnits = row.id_unit || [];
            if (typeof selectedUnits === 'string') {
                try {
                    selectedUnits = JSON.parse(selectedUnits);
                } catch (e) {
                    selectedUnits = [];
                }
            }
            selectedUnits = selectedUnits.map(id => parseInt(id));

            // Parse tembusan
            let selectedTembusan = row.tembusan || [];
            if (typeof selectedTembusan === 'string') {
                try {
                    selectedTembusan = JSON.parse(selectedTembusan);
                } catch (e) {
                    selectedTembusan = [];
                }
            }

            console.log('Selected Units:', selectedUnits);
            console.log('Selected Tembusan:', selectedTembusan);

            $('.tembusan-checkbox').prop('checked', false);

            if (Array.isArray(selectedTembusan) && selectedTembusan.length > 0) {
                selectedTembusan.forEach(function(value) {
                    $('input.tembusan-checkbox[value="' + value + '"]').prop('checked', true);
                });
            }

            loadMultipleSelect($("select[name='id_unit[]']"), selectedUnits);
        },

        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('logistik.permintaan.delete', ':id') }}";
            url = url.replace(':id', row.id);
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin menghapus data ini?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_surat: row.no_surat
                        },
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $tablePermintaan.bootstrapTable('refresh');
                    });
                }
            })
        },



    }


    function loadMultipleSelect($selectElement, selectedIds) {
        if ($selectElement.hasClass("select2-hidden-accessible")) {
            $selectElement.select2('destroy');
        }
        $selectElement.empty();

        if (selectedIds && selectedIds.length > 0) {
            $.ajax({
                url: "{{ route('get-select-unit') }}",
                type: 'GET',
                data: {
                    ids: selectedIds
                },
                dataType: 'json',
                success: function(response) {
                    let units = response.results || response.data || response;

                    if (Array.isArray(units)) {
                        units.forEach(function(item) {
                            let id = item.id;
                            let text = item.text || item.nama || item.name;
                            var option = new Option(text, id, true, true);
                            $selectElement.append(option);
                        });
                    }

                    $selectElement.select2({
                        ajax: {
                            url: "{{ route('get-select-unit') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data) {
                                let results = data.results || data.data || data;
                                if (Array.isArray(results)) {
                                    return {
                                        results: results.map(item => ({
                                            id: item.id,
                                            text: item.text || item.nama || item
                                                .name
                                        }))
                                    };
                                }
                                return {
                                    results: []
                                };
                            }
                        },
                        dropdownParent: $("#modal-permintaan"),
                        placeholder: "---- Pilih Unit ----",
                        allowClear: true,
                        multiple: true,
                        theme: "bootstrap-5"
                    });

                    $selectElement.val(selectedIds).trigger('change.select2');
                }
            });
        } else {
            $selectElement.select2({
                ajax: {
                    url: "{{ route('get-select-unit') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        let results = data.results || data.data || data;
                        if (Array.isArray(results)) {
                            return {
                                results: results.map(item => ({
                                    id: item.id,
                                    text: item.text || item.nama || item.name
                                }))
                            };
                        }
                        return {
                            results: []
                        };
                    }
                },
                dropdownParent: $("#modal-permintaan"),
                placeholder: "---- Pilih Unit ----",
                allowClear: true,
                multiple: true,
                theme: "bootstrap-5"
            });
        }
    }
</script>



















<script>
    $(document).ready(function() {
        var currentpermintaanId = null;
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
            var permintaanId = $(this).data('permintaan-id');
            if (!permintaanId) {
                console.error('permintaan ID tidak ditemukan');
                return;
            }
            currentpermintaanId = permintaanId;

            currentpermintaanId = permintaanId;
            console.log('📂 Opening chat for ticket:', permintaanId);
            loadChatOpponent(permintaanId);
            loadTicketInfo(permintaanId);
            loadChatMessages(permintaanId);
            initChatChannel(permintaanId);

            $('#chatModal').modal('show');
        });

        // ========== LOAD TICKET INFO ==========
        function loadTicketInfo(permintaanId) {
            // Load dari Bootstrap Table
            var rowData = $('#table_permintaan').bootstrapTable('getRowByUniqueId', permintaanId);

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
        function loadChatMessages(permintaanId) {
            $.ajax({
                url: '/user/chat/' + permintaanId,
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
                // (msg.user && msg.user.nama_lengkap) ||
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
        function loadChatOpponent(permintaanId) {
            $.ajax({
                url: '/user/permintaan/' + permintaanId + '/info',
                type: 'GET',
                success: function(data) {
                    console.log('📋 Chat opponent data:', data);

                    if (data.success) {
                        // Update nama lengkap
                        // $('#nama_lengkap').text(data.nama_lengkap || 'Support Team');

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
                    // $('#nama_lengkap').text('Support Team');
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
            // $('#nama_lengkap').text('Support Team');
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

            if (!currentpermintaanId) {
                console.error('❌ No permintaan ID set');
                alert('ID permintaan tidak valid');
                return;
            }

            console.log('📤 Sending message:', message);

            $.ajax({
                url: '/user/chat/' + currentpermintaanId + '/send',
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
                            loadChatMessages(currentpermintaanId);
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
        function initChatChannel(permintaanId) {
            // Leave previous channel
            if (chatChannel) {
                console.log('⬅️ Leaving channel:', chatChannel);
                window.Echo.leave(chatChannel);
            }

            chatChannel = 'chat.' + permintaanId;
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

            currentpermintaanId = null;
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
                    url: '/admin/chat/' + currentpermintaanId + '/send',
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
