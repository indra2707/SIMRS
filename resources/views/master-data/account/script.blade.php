<script type="text/javascript">
    // select2 global
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        allowClear: true
    });

    // View Data
    function loadAccountView() {
        $.get("{{ route('master-data.account.view') }}", function (data) {
            let role = data.rolle ?? "{{ Session::get('nama_role') }}";

            $('#nik').val(data.nik ?? '');
            $('#nama_pekerja').val(data.nama_pekerja ?? '');
            $('#tanggal_lahir').val(data.tanggal_lahir ?? '');
            $('#email').val(data.email ?? '');
            $('#alamat_domisili').val(data.alamat_domisili ?? '');
            $('#lokasi_kerja').val(data.lokasi_kerja ?? '');
            $('#status_kepegawaian').val(data.status_kepegawaian ?? '');
            $('#nomor_pekerja').val(data.nomor_pekerja ?? '');
            $('#nama_pekerja2').val(data.nama_pekerja ?? '');
            $('#nik2').val(data.nik2 ?? '');
            $('#tanggal_lahir2').val(data.tanggal_lahir ?? '');
            $('#email2').val(data.email ?? '');
            $('#id').val(data.id ?? '');
            $('#nomor_hp').val(data.nomor_hp ?? '');
            $('#nomor_kontak_darurat').val(data.nomor_kontak_darurat ?? '');
            $('#nama_kontak_darurat').val(data.nama_kontak_darurat ?? '');
            $('#alamat_domisili2').val(data.alamat_domisili ?? '');
            $('#agama').val(data.agama ?? '').trigger('change');
            $('#golongan_darah').val(data.golongan_darah ?? '').trigger('change');
            $('#status_pernikahan').val(data.status_pernikahan ?? '').trigger('change');
            $('#hubungan_kontak_darurat')
                .val(data.hubungan_kontak_darurat ?? '')
                .trigger('change');

            $('#nama_pekerja3').text(data.nama_pekerja ?? '-');
            $('#rolle').text(role);
            // $('#foto').attr('src', data.foto ? "{{ asset('uploads/images/foto-pegawai/') }}" + data.foto : "{{ asset('assets/images/avatar/default.png') }}");

            // reset validasi
            $('.form-account').removeClass('was-validated');
        }).fail(function (xhr) {
            console.error('Gagal load account view', xhr.responseText);
        });
    }

    // Save Account
    $(document).on('click', '.save-btn', function (event) {

        var id = $('input[name="id"]').val();
        var url = "";
        var type = "";

        if (id) {
            url = "{{ route('master-data.account.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-account');

        Array.prototype.filter.call(forms, function (form) {

            if (!form.checkValidity()) {

                event.preventDefault();
                event.stopPropagation();

                var invalidInput = form.querySelector(":invalid");

                if (invalidInput) {
                    invalidInput.focus();
                }

            } else {

                var formData = new FormData(form);
                formData.append('_method', 'PUT');

                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: formData,

                    // WAJIB untuk upload file
                    processData: false,
                    contentType: false,

                    beforeSend: function () {

                        $('.save-btn')
                            .html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            )
                            .attr('disabled', 'disabled');
                    },

                    complete: function () {

                        $('.save-btn')
                            .html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {

                        if (xhr.status == 200 && res.success == true) {

                            Alert('success', res.message);

                            $('#form-account').addClass('loading');

                            loadAccountView();

                            $('#form-account').removeClass('loading');

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

                    error: function (xhr) {

                        console.log(xhr.responseText);

                        if (xhr.status === 422) {

                            var errors = xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {
                                $.notify({
                                    icon: 'fa fa-warning',
                                    title: 'Validasi',
                                    message: value[0]
                                }, {
                                    type: 'danger',
                                    delay: 3000
                                });
                            });

                        } else {

                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Error',
                                message: 'Terjadi kesalahan saat menyimpan data.'
                            }, {
                                type: 'danger',
                                delay: 3000
                            });
                        }
                    }
                });
            }

            form.classList.add('was-validated');
        });
    });


    $(document).ready(function () {
        loadAccountView();
    });

</script>