<script type="text/javascript">

     // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-aset"),
        allowClear: true

    });

    // Tabel
    var $tableRincian = $('#table_rincian');


    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.lokasi.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.lokasi.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-rincian');
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
                    data: $('.form-rincian').serialize(),
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
                            $('#modal-rincian').modal('hide');
                            $tableRincian.bootstrapTable('refresh');
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


    // Table Lokasi
    function initTable() {
        $tableRincian.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
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
            url: "{{ route('sdm.rincian_spd.view') }}",
            columns: [
                [
                    {
                        field: 'no_surat',
                        sortable: true,
                    },
                    {
                        field: 'nama_pegawai',
                        sortable: true,
                    },
                    {
                        field: 'tgl_awal',
                        sortable: true,
                        formatter: function (value, row, index) {
                            return row.tgl_awal + ' - ' + row.tgl_akhir;
                        }
                    },
                    {
                        field: 'nama_kota1',
                        sortable: true,
                    },
                    {
                        field: 'nama_kota2',
                        sortable: true,
                    },
                    {
                        width: '50%',
                        align: 'center',
                        valign: 'middle',
                        formatter: function (value, row, index) {
                            let buttons = '';  // Tombol yang akan ditampilkan

                            // Kondisi untuk menampilkan tombol berdasarkan 'selisih_hari'
                            if (row.status == 'Draft') {
                                buttons += `
                                    <button class="btn btn-pill btn-xs btn-success action-btn">${row.status}</button>
                                `;
                            } else {
                                buttons += `
                                    <button class="btn btn-pill btn-xs btn-danger action-btn">${row.status}</button>
                                `;
                            }

                            return buttons;
                        }
                    },
                    {
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsLokasi,
                        formatter: actionsFunctionLokasi
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionLokasi(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Tambah Biaya</a></a>',
            '<a class="dropdown-item btn-prinr" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a></a>',
            '<a class="dropdown-item btn-tutup" href="javascript:void(0)"><i class="fa fa-lock text-danger"></i> Close</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsLokasi = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-rincian').modal('show');
            $('.modal-title').text('Form Rincian');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama"]').val(row.nama);
        },
        'click .btn-tutup': function (e, value, row, index) {
            e.preventDefault();

            let url = "{{ route('sdm.rincian_spd.update-status', ':id') }}";
            url = url.replace(':id', row.id);

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin Close data ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Close!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        status: 'Close',
                        table: 'tbl_spds',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        if (res.success) {
                            Alert('success', res.message);
                        } else {
                            Alert('warning', res.message);
                        }
                    },
                    error: function () {
                        Alert('error', 'Terjadi kesalahan server');
                    },
                    complete: function () {
                        $tableRincian.bootstrapTable('refresh');
                    }
                });
            });
        }
    }

</script>