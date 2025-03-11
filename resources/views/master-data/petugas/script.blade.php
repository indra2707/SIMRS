<script type="text/javascript">
    // Variable Name

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
    var optionsSelect2DataStatusDokter = [{
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

    $('#clear').click(function(e) {
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




















    var $table = $('#table_petugas');
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
        // Read Selected Image as DataURL
        console.warn(e.target.files);

        reader.readAsDataURL(e.target.files[0]);
    });


    // Open Modal
    $(document).on('click', '.add-petugas', function() {
        $('#modal-petugas').modal('show');
        $('.modal-title').text('Form Tambah Petugas');
        clearFormInputFields('.form-petugas');
        InitSelect2($("select[name='spesialis']"), {
            url: "{{ route('master-data.spesialis.select_spesialis') }}",
            dropdownParent: $("#modal-petugas"),
            // initialsValue: $("#jabatan").data("value") ? $("#jabatan").data("value") : null,
        });
        InitSelect2Array($("select[name='kategori']"), {
            data: optionsSelect2DataKategori,
            initialValue: ''
        });
        InitSelect2Array($("select[name='status_dokter']"), {
            data: optionsSelect2DataStatusDokter,
            initialValue: ''
        });
        sig.signature('clear');
        imageInput.value = '';
        imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";

    });

    // Save
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
                    // data: $('.form-petugas').serialize(),
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
                            $('#modal-petugas').modal('hide');
                            $table.bootstrapTable('refresh');
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
                            $('#modal-petugas').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            var errors = xhr.responseJSON.errors;
                            $.notify({
                                icon: 'fa fa-check',
                                title: error,
                                message: xhr.responseJSON.message
                            }, {
                                type: 'danger',
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
                        } else if (xhr.status == 500) {
                            $.notify({
                                icon: 'icon-info-alt',
                                title: 'error',
                                message: "Silahkan hubungi IT Rumah Sakit!"
                            }, {
                                type: 'danger',
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
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function() {
        initTable();
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
                        field: 'kategori',
                        sortable: false,
                        formatter: function(value, row, index) {
                            return [
                                '<div class="product-names">',
                                '<div class="light-product-box">',
                                '<a class="fancybox" href="{{ asset('assets/images/big-lightgallry/012.jpg') }}"><img class="img-fluid" src="{{ asset('assets/images/big-lightgallry/012.jpg') }}" alt="laptop"></a>',
                                '</div>',
                                '<a href="javascript:void(0)" style="cursor: default">' + row.kode +
                                '</a>',
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
                        field: 'nama',
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
                        // title: 'ACTIONS',
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
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-petugas').modal('show');
            $('.modal-title').text('Form Edit Petugas');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
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

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('master-data.petugas.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
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
                    $table.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        $.notify({
                            icon: 'fa fa-check',
                            title: error,
                            message: xhr.responseJSON.message
                        }, {
                            type: 'danger',
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
                    } else if (xhr.status == 500) {
                        $.notify({
                            icon: 'icon-info-alt',
                            title: 'error',
                            message: "Silahkan hubungi IT Rumah Sakit!"
                        }, {
                            type: 'danger',
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
</script>
