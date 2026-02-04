<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-tembusan"),
        allowClear: true
    });

    // Tabel
    var $tableDisposisi = $('#table_disposisi');


    $(function () {
        initTable();
    });

    // Table disposisi
    function initTable() {
        $tableDisposisi.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('logistik.tembusan.view') }}",
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
                }
                ]
            ],
            responseHandler: function (data) {
                console.log('Response data:', data);
                return data;
            }
        });
    }
</script>