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


    $(document).on('click', '.add-btn', function () {
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
    $(document).on('click', '.save-btn', function () {
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
        var validation = Array.prototype.filter.call(forms, function (form) {
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
                        if (xhr.status == 200 && res.success === true) {
                            Alert('success', res.message);
                            $('#modal-permintaan').modal('hide');
                            $tablePermintaan.bootstrapTable('refresh');
                        }
                    },

                    error: function (xhr) {
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

    $(function () {
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
                    formatter: function (value, row, index) {
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
                    formatter: function (value, row, index) {
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
            responseHandler: function (data) {
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
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    window.eventsPermintaan = {
        'click .btn-edit': function (e, value, row, index) {
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
                selectedTembusan.forEach(function (value) {
                    $('input.tembusan-checkbox[value="' + value + '"]').prop('checked', true);
                });
            }

            loadMultipleSelect($("select[name='id_unit[]']"), selectedUnits);
        },

        'click .btn-delete': function (e, value, row, index) {
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
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
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
                success: function (response) {
                    let units = response.results || response.data || response;

                    if (Array.isArray(units)) {
                        units.forEach(function (item) {
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
                            data: function (params) {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function (data) {
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
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
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