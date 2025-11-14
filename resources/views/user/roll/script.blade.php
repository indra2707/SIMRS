<script type="text/javascript">
    // Tabel
    var $tableRoll = $('#table_roll');

    document.addEventListener('DOMContentLoaded', function () {

        // === Toggle dropdown label + checklist via caret ===
        // === Toggle dropdown label + checklist via caret ===
        document.querySelectorAll('.tree .caret').forEach(caret => {
            caret.addEventListener('click', function (e) {
                const nested = this.parentElement.querySelector('.nested');
                const parentCheckbox = this.querySelector('input[type="checkbox"]');

                // Klik langsung pada checkbox → biarkan fungsi biasa jalan
                if (e.target.tagName.toLowerCase() === 'input') {
                    updateParentState(e.target);
                    return;
                }

                // 🔹 Simpan status sebelum toggle
                let wasIndeterminate = parentCheckbox ? parentCheckbox.indeterminate : false;
                let wasChecked = parentCheckbox ? parentCheckbox.checked : false;

                // Toggle buka/tutup tampilan anak
                if (nested) {
                    nested.classList.toggle('active');
                    this.classList.toggle('caret-down');
                }

                // Jika tidak ada checkbox parent → selesai
                if (!parentCheckbox) return;

                // 🔹 Jika parent sebelumnya indeterminate → restore saja, jangan ubah apa pun
                if (wasIndeterminate) {
                    parentCheckbox.indeterminate = true;
                    parentCheckbox.checked = true; // tetap tampil centang setengah
                    return;
                }

                // 🔹 Kalau bukan indeterminate → toggle seperti biasa
                const newState = !wasChecked;
                parentCheckbox.checked = newState;
                parentCheckbox.indeterminate = false;

                if (nested) {
                    nested.querySelectorAll('input[type="checkbox"]').forEach(child => {
                        child.checked = newState;
                        child.indeterminate = false;
                    });
                }

                updateParentState(parentCheckbox);
            });
        });


        // === Checkbox: parent → child + child → parent ===
        document.querySelectorAll('.tree input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const li = this.closest('li');

                // Jika parent di-check, anak ikut di-check
                if (li && this.classList.contains('parent')) {
                    const children = li.querySelectorAll('ul input[type="checkbox"]');
                    children.forEach(child => child.checked = this.checked);
                }

                // Perbarui parent ke atas
                updateParentState(this);
            });
        });

        // === Fungsi rekursif: update parent ketika child berubah ===
        function updateParentState(childCheckbox) {
            // Cari <ul> yang menaungi checkbox ini
            const parentUl = childCheckbox.closest('ul');
            if (!parentUl) return;

            // Cari <li> induk dari ul tersebut
            const parentLi = parentUl.closest('li');
            if (!parentLi) return;

            // Cari checkbox parent — bisa langsung di <li> atau di dalam <label>
            let parentCheckbox = parentLi.querySelector('label > input[type="checkbox"]');
            if (!parentCheckbox) {
                parentCheckbox = parentLi.querySelector(':scope > input[type="checkbox"]');
            }
            if (!parentCheckbox) return;

            // Ambil semua checkbox anak langsung di dalam <ul> ini
            const siblings = parentUl.querySelectorAll(':scope > li input[type="checkbox"]');
            const siblingsArr = Array.from(siblings);

            const checkedCount = siblingsArr.filter(chk => chk.checked).length;
            const total = siblingsArr.length;

            const allChecked = total > 0 && checkedCount === total;
            const someChecked = checkedCount > 0 && checkedCount < total;

            // Jika semua anak dicentang → parent ikut centang
            // Jika sebagian dicentang → parent.indeterminate
            // Jika tidak ada yang dicentang → parent uncheck
            // Tentukan status parent
            if (allChecked) {
                parentCheckbox.checked = true;
                parentCheckbox.indeterminate = false;
            } else if (someChecked) {
                parentCheckbox.checked = true;
                parentCheckbox.indeterminate = true; // efek “setengah” jika sebagian anak aktif
            } else {
                parentCheckbox.checked = false;
                parentCheckbox.indeterminate = false;
            }

            // Rekursif ke atas
            updateParentState(parentCheckbox);

            // console.log('updateParentState called for', childCheckbox, 'parentUl:', parentUl, 'parentLi:', parentLi);
        }
        // 🔹 Tambahkan baris ini agar global
        window.updateParentState = updateParentState;
    });

    // Open Modal roll
    $(document).on('click', '.add-btn', function () {
        $('.form-roll').removeClass('was-validated');
        $('#modal-roll').modal('show');
        $('.modal-title').text('Form Tambah Roll');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="status"]').prop('checked', true);
    });

    // Save Roll
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('user.roll.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('user.roll.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-roll');
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
                    data: $('.form-roll').serialize(),
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
                            $('#modal-roll').modal('hide');
                            // $('#modal-roll').modal('refresh');
                            $tableRoll.bootstrapTable('refresh');
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


    // Update Access Menu
    $(document).on('click', '.edit-access', function () {
        var id = $('input[name="id"]').val();
        if (!id) {
            Alert('warning', 'ID Role tidak ditemukan!');
            return;
        }

        var url = "{{ route('user.roll.update-menu', ':id') }}".replace(':id', id);
        var type = "PUT";

        // Ambil semua checkbox yang dicentang
        var checkedPermissions = [];
        $('input[name="permissions[]"]:checked').each(function () {
            checkedPermissions.push($(this).val());
        });

        $.ajax({
            type: type,
            url: url,
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                menu: checkedPermissions // kirim ke backend
            },
            beforeSend: function () {
                $('.edit-access').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                ).attr('disabled', 'disabled');
            },
            complete: function () {
                $('.edit-access').html('<span class="fa fa-check"></span> Simpan')
                    .removeAttr('disabled');
            },
            success: function (res, status, xhr) {
                if (xhr.status == 200 && res.success == true) {
                    Alert('success', res.message);
                    $('#modal-access').modal('hide');
                    $tableRoll.bootstrapTable('refresh');
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
                }
            },
        });
    });

    // Page Load Event
    $(function () {
        initTable();
    });


    // Table roll
    function initTable() {
        $tableRoll.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: false,
            showPaginationSwitch: false,
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
            url: "{{ route('user.roll.view') }}",
            columns: [
                [
                    {
                        field: 'nama',
                        sortable: true,
                    },
                    {
                        width: '200%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatusRoll,
                        formatter: function (value, row, index) {
                            return [
                                '<div class="media-body text-center switch-sm icon-state">',
                                '<label class="switch">',
                                '<input type="checkbox" class="update-status" ' + (row.status ===
                                    '1' ? 'checked' : '') + '>',
                                '<span class="switch-state"></span>',
                                '</label>',
                                '</div>'
                            ].join("");
                        }
                    },
                    {
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsRoll,
                        formatter: actionsFunctionroll
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionroll(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-access" href="javascript:void(0)"><i class="fa fa-cogs text-warning"></i> Access Menu</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsRoll = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-roll').modal('show');
            $('.modal-title').text('Form Edit roll');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-access': function (e, value, row, index) {
            $('#modal-access').modal('show');
            $('.modal-title').text('Form Access Menu');
            $('.edit-access').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);

            // Reset semua checkbox
            $('input[name="permissions[]"]').prop('checked', false);

            // Jika field menu tidak kosong, centang yang sesuai
            if (row.menu) {
                try {
                    var permissions = JSON.parse(row.menu);
                    permissions.forEach(val => {
                        $('input[name="permissions[]"][value="' + val + '"]').prop('checked', true);
                    });
                } catch (e) {
                    console.error('Format menu invalid:', e);
                }
            }

            // === Tambahkan baris ini ===
            // Setelah semua checkbox dicentang, perbarui status parent (checked/indeterminate)
            document.querySelectorAll('.tree input[type="checkbox"]').forEach(cb => {
                updateParentState(cb);
            });

            document.querySelectorAll('.tree input[type="checkbox"]').forEach(cb => {
                if (cb.dataset.indeterminate === "true") cb.indeterminate = true;
            });

            // Debug opsional
            setTimeout(() => {
                document.querySelectorAll('.tree label > input.parent').forEach((parentCheckbox, i) => {
                    console.log(`Parent #${i + 1}:`, {
                        label: parentCheckbox.closest('label').innerText.trim(),
                        checked: parentCheckbox.checked,
                        indeterminate: parentCheckbox.indeterminate
                    });
                });
            }, 200);
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('user.roll.delete', ':id') }}";
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
                        $tableRoll.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status roll
    window.updateStatusRoll = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('user.roll.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_rolls',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tableRoll.bootstrapTable('refresh');
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 400) {
                        Alert('error', xhr.responseJSON.message);
                    } else if (xhr.status == 500) {
                        Alert('info',
                            "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                        );
                    }
                }
            });
        }
    }

</script>