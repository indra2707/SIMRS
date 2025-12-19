<script type="text/javascript">

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-rincian"),
        allowClear: true
    });

    // Tabel
    var $tableRincian = $('#table_rincian');
    var $table_detail = $('#table_detail');

    // klik 2x readonly
    function onDblClick(el) {
        const element = $(el);
        element.prop('readonly', !element.prop('readonly'));
    }

    // ketika user memilih biaya
    $('#biaya').on('select2:select', function (e) {
        let selected = e.params.data;
        let biayaId = selected.id;

        // Ambil langsung dari select2
        if (selected.harga !== undefined) {
            $('#harga').val(selected.harga);
            return;
        }

        // Fallback AJAX
        let url = "{{ route('sdm.rincian_spd.detail', ':id') }}".replace(':id', biayaId);

        $.ajax({
            url: url,
            type: 'GET',
            success: function (res) {
                $('#harga').val(res.harga || '');
            },
            error: function () {
                $('#harga').val('');
            }
        });
    });


    // Save Detail biaya
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val('');
        console.log('ID submit:', id); // DEBUG

        var url = "{{ route('sdm.rincian_spd.update', ':id') }}";
        url = url.replace(':id', id);
        var type = "PUT";
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


    // Table Rincian
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
                        events: window.eventsRincian,
                        formatter: actionsFunctionRincian
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    //Detail table
    function initTable1(id_pegawai, no_surat) {
        if (!id_pegawai || !no_surat) return;

        $table_detail.bootstrapTable('destroy').bootstrapTable({
            height: 300,
            locale: 'en-US',
            // search: true,
            // pagination: true,
            pageSize: 50,
            // showRefresh: true,
            showFooter: true,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,

            url: "{{ route('sdm.rincian_spd.view_detail') }}",
            queryParams: function (params) {
                return {
                    id_pegawai: id_pegawai,
                    no_surat: no_surat
                };
            },

            columns: [
                {
                    field: 'no',
                    sortable: true,
                    align: 'center',
                    formatter: function (value, row, index) {
                        return index + 1;
                    }
                },
                {
                    field: 'nama_biaya',
                    sortable: true,
                },
                {
                    field: 'harga',
                    sortable: true,
                    align: 'right',
                    formatter: function (value, row, index) {
                        if (!value) return;
                        return parseInt(value).toLocaleString('id-ID');
                    }
                },
                {
                    field: 'jumlah',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'total',
                    sortable: true,
                    align: 'right',
                    footerFormatter: function (data) {
                        let total = 0;

                        data.forEach(function (row) {
                            let harga = parseFloat(row.harga) || 0;
                            let jumlah = parseFloat(row.jumlah) || 0;
                            total += harga * jumlah;
                        });

                        return total.toLocaleString('id-ID');
                    },
                    formatter: function (value, row, index) {
                        let harga = parseFloat(row.harga) || 0;
                        let jumlah = parseFloat(row.jumlah) || 0;
                        return (harga * jumlah).toLocaleString('id-ID');
                    }
                },
                {
                    width: '100%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.eventsDetail,
                    formatter: actionsFunctionDetail
                },
            ],

            responseHandler: function (res) {
                return res.data;
            }
        });
    }


    //Mengambil data footer
    function getTotalTable() {
        let data = $('#table_detail').bootstrapTable('getData');
        let total = 0;

        data.forEach(function (row) {
            let harga = parseFloat(row.harga) || 0;
            let jumlah = parseFloat(row.jumlah) || 0;
            total += harga * jumlah;
        });

        return total;
    }

    // format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // onclick select panjar
    function checkAlert(evt) {
        const panjarInput = document.getElementById('panjar');

        if (evt.target.value === "Panjar") {
            let total = getTotalTable();
            panjarInput.value = formatRupiah(total);
        } else {
            panjarInput.value = '0';
        }
    }

    //reload data panjar
    $('#table_detail').on('load-success.bs.table', function () {
        if ($('#jenis').val() === 'Panjar') {
            $('#panjar').val(formatRupiah(getTotalTable()));
        }
    });

    //save rincian biaya spd
    $(document).on('click', '.save-detail', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('sdm.rincian_spd.update_detail', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('sdm.rincian_spd.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-detail');
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
                    data: $('.form-detail').serialize(),
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
                            $('#modal-detail').modal('hide');
                            $table_detail.bootstrapTable('refresh');
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


    // Open Modal Detail
    $(document).on('click', '.add-btn', function () {
        $('.form-detail').removeClass('was-validated');
        $('#modal-detail').modal('show');
        $('#modal-rincian').modal('hide');
        $('.modal-title').text('Form Tambah Rincian Biaya');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

        $('input[name="id"]').val('');
        $('input[name="harga"]').val('').prop('readonly', true);
        $('input[name="jumlah"]').val('');
        $('select[name="biaya"]').val('').trigger('change');

        InitSelect2($("select[name='biaya']"), {
            url: "{{ route('get-select-biaya') }}",
            dropdownParent: $("#modal-detail")
        });
    });

    $('#modal-detail').on('hidden.bs.modal', function () {
        $('#modal-rincian').modal('show');
    });

    //Action Detail
    function actionsFunctionDetail(value, row1, index) {
        return `
        <div class="d-flex justify-content-center align-items-center gap-3">
            <a class="btn-edit-detail" href="javascript:void(0)">
                <i class="fa fa-edit text-primary"></i>
            </a>
            <a class="btn-delete-detail" href="javascript:void(0)">
                <i class="fa fa-trash text-danger"></i>
            </a>
        </div>`;
    }

    //Format Rupiah
    function formatRupiah(angka) {
        if (!angka) return '';
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    //event button detail
    window.eventsDetail = {
        'click .btn-edit-detail': function (e, value, row1, index) {
            $('.form-detail').removeClass('was-validated');
            $('#modal-detail').modal('show');
            $('#modal-rincian').modal('hide');
            $('.modal-title').text('Form Edit Rincian Biaya');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

            $('input[name="no_surat"]').val(row1.no_surat);
            $('input[name="id_pegawai"]').val(row1.id_pegawai);
            $('input[name="id"]').val(row1.id_detail);
            $('input[name="harga"]').val(formatRupiah(row1.harga)).prop('readonly', true);
            $('input[name="jumlah"]').val(row1.jumlah);

            InitSelect2($("select[name='biaya']"), {
                url: "{{ route('get-select-biaya') }}",
                dropdownParent: $("#modal-detail"),
                initialValue: row1.id_biaya
            });
        },
        'click .btn-delete-detail': function (e, value, row1, index) {
            var url = "{{ route('sdm.rincian_spd.delete', ':id') }}";
            url = url.replace(':id', row1.id_detail);
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
                        $table_detail.bootstrapTable('refresh');
                    });

                }
            })
        }
    }


    //Action Rincian
    function actionsFunctionRincian(value, row, index) {

        if (row.status === 'Close') {
            return [
                '<div class="dropdown icon-dropdown">',
                '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
                '<i class="icon-more-alt"></i>',
                '</button>',
                '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
                '<a class="dropdown-item btn-prinr" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a></a>',
                '</div>',
                '</div>',
            ].join("");
        } else {
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
    }

    // Handle events button actions
    window.eventsRincian = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-rincian').modal('show');
            $('.modal-title').text('Form Rincian');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_surat"]').val(row.no_surat);
            $('input[name="id_pegawai"]').val(row.id_pegawai);
            $('input[name="nama"]').val(row.nama_pegawai);
            $('input[name="panjar"]').val(row.panjar ?? '').val('');
            $('input[name="tanggal"]').val(row.tanggal ?? '');
            $('select[name="jenis"]').val(row.jenis ?? '').trigger('change');

            if (row.id_menyetujui) {
                InitSelect2($("select[name='id_menyetujui']"), {
                    url: "{{ route('get-select-pegawai') }}",
                    dropdownParent: $("#modal-rincian"),
                    initialValue: row.id_menyetujui
                });
            } else {
                InitSelect2($("select[name='id_menyetujui']"), {
                    url: "{{ route('get-select-pegawai') }}",
                    dropdownParent: $("#modal-rincian")
                });
                $('select[name="id_menyetujui"]').val('').trigger('change');
            }

            if (row.id_menyetujui) {
                InitSelect2($("select[name='id_mengajukan']"), {
                    url: "{{ route('get-select-pegawai') }}",
                    dropdownParent: $("#modal-rincian"),
                    initialValue: row.id_mengajukan
                });
            } else {
                InitSelect2($("select[name='id_mengajukan']"), {
                    url: "{{ route('get-select-pegawai') }}",
                    dropdownParent: $("#modal-rincian")
                });
                $('select[name="id_mengajukan"]').val('').trigger('change');
            }

            // load table detail
            initTable1(row.id_pegawai, row.no_surat);

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