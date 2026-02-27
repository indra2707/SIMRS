<script type="text/javascript">
    // Tabel
    var $table_kartu_jaga = $('#table_kartu_jaga');

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-kartu-jaga"),
        allowClear: true
    });

    $(document).ready(function () {
        // Inisialisasi Select2 kosong untuk semua ruangan awal
        var $noKartu = $("select[name='no_kartu']");
        $noKartu.select2({
            placeholder: "---- Pilih Salah Satu ----",
            dropdownParent: $("#modal-kartu-jaga")
        }).prop('disabled', true); // disabled awalnya

        // Event saat ruangan berubah
        $("select[name='ruangan']").on("change", function () {
            var ruangan = $(this).val();

            // Reset select2
            $noKartu.val(null).trigger('change');
            $noKartu.empty();

            if (ruangan === "Emerald") {
                $noKartu.prop('disabled', false);
                InitSelect2($("select[name='no_kartu']"), {
                    url: "{{ route('get-select-emerald') }}",
                    dropdownParent: $("#modal-kartu-jaga")
                });

            } else if (ruangan === "Ruby") {
                $noKartu.prop('disabled', false);
                InitSelect2($("select[name='no_kartu']"), {
                    url: "{{ route('get-select-ruby') }}",
                    dropdownParent: $("#modal-kartu-jaga")
                });
            } else {
                // Bukan Emerald → tetap disabled
                $noKartu.prop('disabled', true);
            }
        });
    });

    // Open Modal Kartu Jaga
    $(document).on('click', '.add-btn', function () {
        $('.form-kartu-jaga').removeClass('was-validated');
        $('#modal-kartu-jaga').modal('show');
        $('.modal-title').text('Form Tambah Kartu Jaga');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="nama_pasien"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="no_hp"]').val('');
        $('select[name="ruangan"]').val('').trigger('change');
        $('input[name="no_kartu"]').val('');
        $('input[name="deposit"]').val('Rp 50,000');
    });

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('pintu.kartu-jaga.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('pintu.kartu-jaga.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-kartu-jaga');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-kartu-jaga').serialize(),
                    beforeSend: function () {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-kartu-jaga').modal('hide');
                            $table_kartu_jaga.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-check',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning',
                                allow_dismiss: true,
                                delay: 2000,
                                showProgressbar: true,
                                timer: 300,
                                z_index: 1127,
                                animate: {
                                    enter: 'animated fadeInDown',
                                    exit: 'animated fadeOutUp'
                                },
                            });
                            form.classList.remove('was-validated');
                        }
                    },
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function () {
        initTable();
    });

    // Table Kartu Jaga
    function initTable() {
        $table_kartu_jaga.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            showExport: true,
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
            exportTypes: ['excel', 'pdf'],
            url: "{{ route('pintu.kartu-jaga.view') }}",
            columns: [
                [
                    {
                        field: "id",
                        sortable: true,
                        align: "center",
                        formatter: function (value, row, index) {
                            return index + 1;
                        },
                    },
                    {
                        field: 'nama_pasien',
                        sortable: true,
                    },
                    {
                        field: 'nama',
                        sortable: true,
                    },
                    {
                        field: 'no_hp',
                        sortable: true,
                    },
                    {
                        field: 'ruangan',
                        sortable: true,
                    },
                    {
                        field: 'no_kartu',
                        sortable: true,
                    },
                    {
                        field: 'deposit',
                        sortable: true,
                    },
                    {
                        field: 'created_by',
                        sortable: true,
                        visible: false
                    },
                    {
                        field: 'updated_by',
                        sortable: true,
                        visible: false
                    },
                    {
                        field: 'created_at',
                        sortable: true,
                        visible: false
                    },
                    {
                        field: 'updated_at',
                        sortable: true,
                        visible: false
                    },
                    {
                        width: '100%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatus,
                        formatter: function (value, row, index) {
                            if (row.status === '1') {
                                return `
                                    <div class="text-center">
                                        <button class="btn btn-success btn-xs rounded-pill update-status">
                                            Aktif
                                        </button>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="text-center">
                                        <button class="btn btn-danger btn-xs rounded-pill update-status">
                                            Kartu Hilang
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    },
                    {
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsKartu,
                        formatter: actionsFunctionKartu
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionKartu(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsKartu = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-kartu-jaga').modal('show');
            $('.modal-title').text('Form Edit Kartu Jaga');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama"]').val(row.nama);
            $('input[name="nama_pasien"]').val(row.nama_pasien);
            $('input[name="no_hp"]').val(row.no_hp);
            $('[name="no_kartu"]').val(row.no_kartu).trigger('change');
            $('input[name="deposit"]').val(row.deposit);
            // Set ruangan dulu
            $('[name="ruangan"]').val(row.ruangan).trigger('change');

            // Aktifkan select no_kartu
            var $noKartu = $('[name="no_kartu"]');
            $noKartu.prop('disabled', false);

            // Tambahkan option manual supaya bisa tampil
            var newOption = new Option(row.no_kartu, row.no_kartu, true, true);
            $noKartu.append(newOption).trigger('change');

        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('pintu.kartu-jaga.delete', ':id') }}";
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
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $table_kartu_jaga.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status unit
    window.updateStatus = {
        'click .update-status': function (e, value, row, index) {
            let newStatus = row.status === '1' ? 1 : 0;
            let statusText = newStatus === 1 ? 'Aktifkan kartu ini?' : 'Set kartu menjadi Hilang?';
            Swal.fire({
                title: 'Konfirmasi',
                text: statusText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('pintu.kartu-jaga.update-status', ':id') }}";
                    url = url.replace(':id', row.id);
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            status: newStatus,
                            table: 'kartu_jaga',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $table_kartu_jaga.bootstrapTable('refresh');
                    });
                }
            });
        }
    };
</script>