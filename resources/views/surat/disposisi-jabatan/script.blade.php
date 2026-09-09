<script type="text/javascript">
    var $tableDjabatan = $('#table_djabatan');

    $(".select2").select2({
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#modal-djabatan")
    });

    // Isi dropdown filter & dropdown di modal dengan daftar Approval
    function muatDaftarAproval() {
        $.ajax({
            url: "{{ route('surat.aproval.view') }}",
            type: "GET",
            success: function (res) {
                var options = '<option value="">-- Semua Approval --</option>';
                var optionsModal = '<option></option>';
                res.forEach(function (item) {
                    options += '<option value="' + item.id + '">' + item.nama_aproval + '</option>';
                    optionsModal += '<option value="' + item.id + '">' + item.nama_aproval + '</option>';
                });
                $('#filter_id_aproval').html(options);
                $('select[name="id_aproval"]').html(optionsModal);
            }
        });
    }

    $(function () {
        muatDaftarAproval();
        initTableDjabatan();
    });

    // Refresh tabel saat filter berubah
    $(document).on('change', '#filter_id_aproval', function () {
        $tableDjabatan.bootstrapTable('refresh');
    });

    function initTableDjabatan() {
        $tableDjabatan.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id',
            uniqueId: 'id',
            sidePagination: 'client',
            pagination: true,
            search: true,
            showRefresh: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('surat.disposisi-jabatan.view') }}",
            queryParams: function (params) {
                params.id_aproval = $('#filter_id_aproval').val();
                return params;
            },
            columns: [
                [{
                    field: 'urutan',
                    sortable: true,
                    align: 'center',
                    width: '80px',
                },
                {
                    field: 'id_aproval',
                    title: 'Approval',
                    sortable: true,
                    formatter: function (value, row) {
                        return $('select[name="id_aproval"] option[value="' + value + '"]').text() || '-';
                    }
                },
                {
                    field: 'nama_jabatan',
                    sortable: true,
                },
                {
                    field: 'nama_pekerja',
                    title: 'Pemegang Jabatan',
                    sortable: true,
                    formatter: function (value) {
                        return value || '<span class="text-muted">- kosong -</span>';
                    }
                },
                {
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    width: '100px',
                    events: window.eventsDjabatan,
                    formatter: function () {
                        return [
                            '<a class="btn btn-primary btn-xs btn-edit-djabatan" title="Edit"><i class="fa fa-edit"></i></a> ',
                            '<a class="btn btn-danger btn-xs btn-delete-djabatan" title="Hapus"><i class="fa fa-trash"></i></a>',
                        ].join('');
                    }
                }
                ]
            ],
            responseHandler: function (res) {
                return res;
            }
        });
    }

    $(document).on('click', '.add-btn-djabatan', function () {
        $('.form-djabatan')[0].reset();
        $('.form-djabatan').removeClass('was-validated');
        $('#modal-djabatan').modal('show');
        $('.modal-title').text('Tambah Jabatan Disposisi');
        $('input[name="id"]').val('');
        $('select[name="id_aproval"]').val($('#filter_id_aproval').val() || '').trigger('change');
        $('select[name="id_pegawai"]').val('').trigger('change');

        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-djabatan")
        });
    });

    window.eventsDjabatan = {
        'click .btn-edit-djabatan': function (e, value, row) {
            $('.form-djabatan')[0].reset();
            $('#modal-djabatan').modal('show');
            $('.modal-title').text('Edit Jabatan Disposisi');
            $('input[name="id"]').val(row.id);
            $('select[name="id_aproval"]').val(row.id_aproval).trigger('change');
            $('input[name="urutan"]').val(row.urutan);
            $('input[name="nama_jabatan"]').val(row.nama_jabatan);

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-djabatan"),
                initialValue: row.id_pegawai
            });
        },
        'click .btn-delete-djabatan': function (e, value, row) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Hapus jabatan "' + row.nama_jabatan + '" dari template ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('surat.disposisi-jabatan.delete', ':id') }}".replace(':id', row.id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            Alert(res.success ? 'success' : 'warning', res.message);
                            $tableDjabatan.bootstrapTable('refresh');
                        }
                    });
                }
            });
        }
    };

    $(document).on('click', '.save-btn-djabatan', function () {
        var id = $('input[name="id"]').val();
        var form = document.querySelector('.form-djabatan');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            var invalid = form.querySelector('.form-control:invalid, .form-select:invalid');
            if (invalid) invalid.focus();
            return;
        }

        var payload = {
            id_aproval: $('select[name="id_aproval"]').val(),
            urutan: $('input[name="urutan"]').val(),
            nama_jabatan: $('input[name="nama_jabatan"]').val(),
            id_pegawai: $('select[name="id_pegawai"]').val() || null,
            id_unit: "{{ session('id_unit') }}",
            _token: "{{ csrf_token() }}"
        };

        var url, type;
        if (id) {
            url = "{{ route('surat.disposisi-jabatan.update', ':id') }}".replace(':id', id);
            type = 'PUT';
        } else {
            url = "{{ route('surat.disposisi-jabatan.create') }}";
            type = 'POST';
        }

        $.ajax({
            url: url,
            type: type,
            data: payload,
            beforeSend: function () {
                $('.save-btn-djabatan').attr('disabled', true);
            },
            complete: function () {
                $('.save-btn-djabatan').removeAttr('disabled');
            },
            success: function (res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('#modal-djabatan').modal('hide');
                    $tableDjabatan.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function () {
                Alert('info', 'Silahkan hubungi IT!');
            }
        });
    });
</script>