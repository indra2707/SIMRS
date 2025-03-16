<script type="text/javascript">
    // Variable Name
    var $table = $('#table_petugas');
    // Options Select2 Array kategori
    var optionsSelect2DataKategori = [{
        id: 'Dokter',
        text: 'Dokter'
    }, {
        id: 'Dokter Spesialis',
        text: 'Dokter Spesialis'
    }, {
        id: 'Dokter Sub Spesialis',
        text: 'Dokter Sub Spesialis'
    }, {
        id: 'Perawat',
        text: 'Perawat'
    }, {
        id: 'Bidan',
        text: 'Bidan'
    }, {
        id: 'Apoteker',
        text: 'Apoteker'
    }, {
        id: 'Ahligizi',
        text: 'Ahligizi'
    }, {
        id: 'Radiographer',
        text: 'Radiographer'
    }, {
        id: 'Rekam Medis',
        text: 'Rekam Medis'
    }, {
        id: 'Fisioterapis',
        text: 'Fisioterapis'
    }, {
        id: 'Analis',
        text: 'Analis'
    }, {
        id: 'Keuangan',
        text: 'Keuangan'
    }, {
        id: 'Kasir',
        text: 'Kasir'
    }, {
        id: 'ICT',
        text: 'ICT'
    }];

    // Status Dokter Option Data
    var optionsSelect2DataStatusPetugas = [{
        id: 'Mitra',
        text: 'Mitra'
    }, {
        id: 'PWT/PWTT',
        text: 'PWT/PWTT'
    }, {
        id: 'Sub Spesialis',
        text: 'Sub Spesialis/Konsulen/Profesor'
    }];

    var sig = $('#signature-pad').signature({
        syncField: '#signature64',
        syncFormat: 'PNG'
    });

    $('.clear').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });




    // $('#removeSignature').click(function() {
    //     var destroy = $(this).text() === 'Remove';
    //     $(this).text(destroy ? 'Re-attach' : 'Remove');
    //     $('#signature-pad').signature(destroy ? 'destroy' : {});
    // });

    // $('#disableSignature').click(function() {
    //     var enable = $(this).text() === 'Enable';
    //     $(this).text(enable ? 'Disable' : 'Enable');
    //     $('#signature-pad').signature(enable ? 'enable' : 'disable');
    // });


    var reader = new FileReader();

    // Main Wrapper Selector
    const avatarFileUpload = document.getElementById('AvatarFileUpload');
    // Preview Wrapper Selector
    const imageViewer = avatarFileUpload.querySelector('.selected-image-holder>img');
    // Image Selector button Selector
    const imageSelector = avatarFileUpload.querySelector('.avatar-selector-btn');
    // Image Input File Selector
    const imageInput = avatarFileUpload.querySelector('input[name="profil"]');

    /** Trigger Browsing Image to Upload */
    imageSelector.addEventListener('click', e => {
        e.preventDefault()
        // Trigger Image input click
        imageInput.click()
    });

    /** IF Selected Image has change */
    imageInput.addEventListener('change', e => {
        // Open File eader
        reader.onload = function() {
            // Preview Image
            imageViewer.src = reader.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });


    // Open Modal
    $(document).on('click', '.add-petugas', function() {
        $('#modal-petugas').modal('show');
        $('.modal-title').text('Form Tambah Petugas');
        clearFormInputFields('.form-petugas');
        InitSelect2Array($("select[name='kategori']"), {
            data: optionsSelect2DataKategori,
            dropdownParent: $("#modal-petugas"),
            initialValue: ''
        });
        InitSelect2($("select[name='spesialis']"), {
            url: "{{ route('get-select-spesialis') }}",
            dropdownParent: $("#modal-petugas"),
            initialValue: ''
        });
        InitSelect2Array($("select[name='status_petugas']"), {
            data: optionsSelect2DataStatusPetugas,
            dropdownParent: $("#modal-petugas"),
            initialValue: ''
        });

        InitSelect2($("select[name='tindakan_konsul']"), {
            url: "{{ route('get-select-spesialis') }}",
            dropdownParent: $("#modal-petugas"),
            initialValue: ""
        });
        InitSelect2($("select[name='tindakan_visite']"), {
            url: "{{ route('get-select-spesialis') }}",
            dropdownParent: $("#modal-petugas"),
            initialValue: ""
        });
        // sig.signature('clear');
        imageInput.value = '';
        imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";
    });

    // Save Form Add/Update
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.petugas.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.petugas.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-petugas');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                const fileInput = $('#profil')[0],
                    file = fileInput.files[0],
                    maxSize = 1 * 1024 * 1024,
                    allowedTypes = ['image/jpeg', 'image/png',
                        'image/jpg'
                    ]; // Example allowed types
                // Validate file type
                if (file && !allowedTypes.includes(file.type)) {
                    Alert('warning',
                        'Jenis berkas tidak valid. Jenis yang diperbolehkan: JPEG, PNG, JPG.');
                    return;
                }
                // Validate file size
                if (file && file.size > maxSize) {
                    Alert('warning', 'Ukuran file melebihi batas maksimal 1 MB');
                    return;
                }
                let myformData = new FormData(form);
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: myformData,
                    enctype: 'multipart/form-data',
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
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                        }
                        $('#modal-petugas').modal('hide');
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Save Signatures
    $(document).on('click', '.save-signature-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.petugas.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.petugas.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-petugas');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                const fileInput = $('#profil')[0],
                    file = fileInput.files[0],
                    maxSize = 1 * 1024 * 1024,
                    allowedTypes = ['image/jpeg', 'image/png',
                        'image/jpg'
                    ]; // Example allowed types
                // Validate file type
                if (file && !allowedTypes.includes(file.type)) {
                    Alert('warning',
                        'Jenis berkas tidak valid. Jenis yang diperbolehkan: JPEG, PNG, JPG.');
                    return;
                }
                // Validate file size
                if (file && file.size > maxSize) {
                    Alert('warning', 'Ukuran file melebihi batas maksimal 1 MB');
                    return;
                }
                let formData = new FormData(form);
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
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
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                        }
                        $('#modal-petugas').modal('hide');
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    // ---------------------------------------------------------------------------------------------
    // init table
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
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
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.petugas.view') }}",
            columns: [
                [{
                        field: 'profile',
                        sortable: false,
                        formatter: function(value, row, index) {
                            return [
                                '<div class="product-names">',
                                '<div class="light-product-box">',
                                '<a class="fancybox" href="{{ asset('/uploads/images/profil') }}' +
                                '/' + row.foto + '" data-fancybox="gallery" data-caption="' + row
                                .nama + '">',
                                '<img class="img-fluid img-40" src="{{ asset('/uploads/images/profil') }}' +
                                '/' + row.foto + '" alt="laptop">',
                                '</a>',
                                '</div>',
                                '<div>',
                                '<h6 class="mt-1 mb-0">' + row.nama + '</h6>',
                                '<span class="f-light">' + row.kode_petugas + '</span>',
                                '</div>',
                                '</div>',
                            ].join("");
                        }
                    }, {
                        field: 'kategori',
                        sortable: true,
                    },
                    {
                        field: 'kode_bpjs',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        field: 'status',
                        sortable: true,
                        events: window.operateChange,
                        formatter: function(value, row, index) {
                            return [
                                '<div class="media-body text-center switch-sm icon-state">',
                                '<label class="switch">',
                                '<input type="checkbox" class="update-status" ' + (row
                                    .status ===
                                    '1' ? 'checked' : '') + '>',
                                '<span class="switch-state"></span>',
                                '</label>',
                                '</div>'
                            ].join("");
                        }
                    },
                    {
                        width: '5%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: actionsFunction
                    }
                ]
            ],
            responseHandler: function(data) {
                return data;
            }
        });
    }

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-signature" href="javascript:void(0)"><i class="fa fa-bookmark-o text-warning"></i> Signature</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-signature': function(e, value, row, index) {
            $('#modal-signature').modal('show');
            $('.modal-title').text('Tanda Tangan');
            if (row.signature) {
                $('.img-signature').show();
                $('.images-sig').attr('src', "{{ asset('/uploads/images/signature') }}" + "/" + row.signature);
                $('.signatures-pad').hide();
            } else {
                $('.img-signature').hide();
                $('.images-sig').attr('src', "{{ asset('assets/images/avatar/user2.png') }}");
                $('.signatures-pad').show();
                sig.signature('clear');
                $("#signature64").val('');
            }
        },
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-petugas').modal('show');
            $('.modal-title').text('Form Edit Petugas');
            $('input[name="id"]').val(row.id);
            $('input[name="kode_petugas"]').val(row.kode_petugas);
            $('input[name="nama"]').val(row.nama);
            InitCleaveJs($("input[name='nik']"), {
                type: 'ktp',
                initValue: row.nik
            });
            if (row.jenis_kelamin === 'Laki-laki') {
                $("#laki_laki").prop("checked", true);
            } else {
                $("#perempuan").prop("checked", true);
            }
            InitSelect2Array($("select[name='status_petugas']"), {
                data: optionsSelect2DataStatusPetugas,
                dropdownParent: $("#modal-petugas"),
                initialValue: row.status_petugas
            });
            InitCleaveJs($("input[name='no_hp']"), {
                type: 'phone',
                initValue: row.no_hp
            });
            $('textarea[name="alamat"]').val(row.alamat);
            imageInput.value = '';
            imageViewer.src = "{{ asset('/uploads/images/profil') }}" + '/' + row.foto;
            $('input[name="kode_bpjs"]').val(row.kode_bpjs);
            InitSelect2Array($("select[name='kategori']"), {
                data: optionsSelect2DataKategori,
                dropdownParent: $("#modal-petugas"),
                initialValue: row.kategori
            });
            $('input[name="no_sip"]').val(row.no_sip);
            $('input[name="masa_berlaku_sip"]').val(row.masa_berlaku_sip);
            InitSelect2($("select[name='spesialis']"), {
                url: "{{ route('get-select-spesialis') }}",
                dropdownParent: $("#modal-petugas"),
                initialValue: row.kode_spesialis
            });
            InitSelect2($("select[name='tindakan_konsul']"), {
                url: "{{ route('get-select-spesialis') }}",
                dropdownParent: $("#modal-petugas"),
                initialValue: row.tindakan_konsul
            });
            InitSelect2($("select[name='tindakan_visite']"), {
                url: "{{ route('get-select-spesialis') }}",
                dropdownParent: $("#modal-petugas"),
                initialValue: row.tindakan_visite
            });
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('master-data.petugas.delete', ':id') }}";
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
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                $.notify({
                                    icon: 'fa fa-check',
                                    title: 'Success',
                                    message: res.message
                                }, {
                                    type: 'success',
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
                        }
                    }).done(function() {
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Update Signature
    $(document).on('click', '.update-signature', function() {
        $('.signatures-pad').show();
        $('.img-signature').hide();
        sig.signature('clear');
        $("#signature64").val('');
    });

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('get-select-update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}",
                    table:'petugas'
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                        $table.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                    $table.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
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


    // View Image FancyBox
    // $(".fancybox").each(function(index, ele) {
    //     ele.jqPhotoSwipe({
    //         galleryOpen: function(gallery) {
    //             //with `gallery` object you can access all methods and properties described here http://photoswipe.com/documentation/api.html
    //             //console.log(gallery);
    //             //console.log(gallery.currItem);
    //             //console.log(gallery.getCurrentIndex());
    //             //gallery.zoomTo(1, {x:gallery.viewportSize.x/2,y:gallery.viewportSize.y/2}, 500);
    //             gallery.toggleDesktopZoom();
    //         }
    //     });
    // });

    // fine fancybox class in table
    // var table = $table.bootstrapTable();
    // table.find(".fancybox").jqPhotoSwipe({
    //         galleryOpen: function(gallery) {
    //             //with `gallery` object you can access all methods and properties described here http://photoswipe.com/documentation/api.html
    //             //console.log(gallery);
    //             //console.log(gallery.currItem);
    //             //console.log(gallery.getCurrentIndex());
    //             //gallery.zoomTo(1, {x:gallery.viewportSize.x/2,y:gallery.viewportSize.y/2}, 500);
    //             gallery.toggleDesktopZoom();
    //         }
    //     });
    // table.$(".fancybox").jqPhotoSwipe({
    //     galleryOpen: function(gallery) {
    //         //with `gallery` object you can access all methods and properties described here http://photoswipe.com/documentation/api.html
    //         //console.log(gallery);
    //         //console.log(gallery.currItem);
    //         //console.log(gallery.getCurrentIndex());
    //         //gallery.zoomTo(1, {x:gallery.viewportSize.x/2,y:gallery.viewportSize.y/2}, 500);
    //         gallery.toggleDesktopZoom();
    //     }
    // });

    // $(document).on("click", ".fancybox", function () {
    //     console.warn("clicked");

    //     var image = $(this).attr("data-image");
    //     $.fancybox.open(image);
    // });
    // Page Load Event
    $(function() {
        initTable();
    });
</script>
