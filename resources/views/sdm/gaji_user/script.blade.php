<script type="text/javascript">
    var $tableGajiUser = $('#table_gaji_user');

    $(function () {
        initTable();
    });

    function initTable() {
        $tableGajiUser.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            pagination: true,
            sidePagination: 'client',
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            showColumns: true,
            showPaginationSwitch: true,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('sdm.gaji.user.view') }}",

            columns: [
                {
                    title: 'No',
                    align: 'center',
                    formatter: function (value, row, index) {
                        return index + 1;
                    }
                },
                {
                    field: 'nomor_pekerja',
                    title: 'Nomor Pegawai',
                    sortable: true
                },
                {
                    field: 'bulan',
                    title: 'Bulan',
                    sortable: true
                },
                {
                    title: 'Aksi',
                    align: 'center',
                    clickToSelect: false,
                    events: window.eventsGaji,
                    formatter: actionsFunctionGaji
                }
            ],

            responseHandler: function (res) {
                return res;
            }
        });
    }

    function actionsFunctionGaji(value, row, index) {
        return `
        <button class="btn btn-xs btn-outline-success btn-print" title="Print"> 
            <i class="fa fa-print"></i> Print
        </button>
    `;
    }

    window.eventsGaji = {
        'click .btn-print': function (e, value, row, index) {
            if (row.file) {
                window.open('{{ url("/") }}/' + row.file, '_blank');
            } else {
                alert('File tidak ditemukan');
            }
        }
    }
</script>