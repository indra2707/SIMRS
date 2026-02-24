<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script type="text/javascript">

    let ictChart;
    let teknikChart;
    let elektroMedisChart;
    let generalAffairChart;

    // tanggal
    $(function () {

        function formatDate(date) {
            return ("0" + date.getDate()).slice(-2) + "/" +
                ("0" + (date.getMonth() + 1)).slice(-2) + "/" +
                date.getFullYear();
        }

        let todayDate = new Date();
        let today = formatDate(todayDate);

        // INIT DATEPICKER
        $('.js-datepicker').datepicker({
            dateFormat: 'dd/mm/yyyy',
            autoClose: true,
            onSelect: function () {

                let tgl_awal = $('input[name="tgl_awal"]').val();
                let tgl_akhir = $('input[name="tgl_akhir"]').val();

                console.log("Tanggal dipilih:", tgl_awal, tgl_akhir);

                if (tgl_awal && tgl_akhir) {
                    loadICTChart(tgl_awal, tgl_akhir);
                    loadTeknikChart(tgl_awal, tgl_akhir);
                    loadElektroMedisChart(tgl_awal, tgl_akhir);
                    loadGeneralAffairChart(tgl_awal, tgl_akhir);
                }
            }
        });

        // SET DEFAULT HARI INI
        $('input[name="tgl_awal"]').datepicker().data('datepicker').selectDate(todayDate);
        $('input[name="tgl_akhir"]').datepicker().data('datepicker').selectDate(todayDate);

        // LOAD PERTAMA
        loadICTChart(today, today);
        loadTeknikChart(today, today);
        loadElektroMedisChart(today, today);
        loadGeneralAffairChart(today, today);
    });


    // ICT
    function loadICTChart(tgl_awal = null, tgl_akhir = null) {

        $.ajax({
            url: "{{ route('dashboard.helpdesk.view') }}",
            type: "GET",
            dataType: "json",
            data: {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir
            },
            success: function (data) {

                console.log("ICT Response:", data);

                let seriesData = [
                    data.done ?? 0,
                    data.accept ?? 0,
                    data["on-progress"] ?? 0
                ];

                if (ictChart) {
                    ictChart.updateSeries(seriesData);
                    return;
                }

                ictChart = new ApexCharts(document.querySelector("#ICT"), {
                    series: seriesData,
                    chart: {
                        type: 'pie',
                        height: 280
                    },
                    labels: ['Done', 'Accept', 'On Progress'],
                    colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                    legend: { position: 'bottom' }
                });

                ictChart.render();
            }
        });
    }


    // Teknik
    function loadTeknikChart(tgl_awal = null, tgl_akhir = null) {

        $.ajax({
            url: "{{ route('dashboard.helpdesk.teknik') }}",
            type: "GET",
            dataType: "json",
            data: {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir
            },
            success: function (data) {

                console.log("Teknik Response:", data);

                let seriesData = [
                    data.done ?? 0,
                    data.accept ?? 0,
                    data["on-progress"] ?? 0
                ];

                if (teknikChart) {
                    teknikChart.updateSeries(seriesData);
                    return;
                }

                teknikChart = new ApexCharts(document.querySelector("#Teknik"), {
                    series: seriesData,
                    chart: {
                        type: 'pie',
                        height: 280
                    },
                    labels: ['Done', 'Accept', 'On Progress'],
                    colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                    legend: { position: 'bottom' }
                });

                teknikChart.render();
            }
        });
    }


    // ElectroMedis Pie Chart
    function loadElektroMedisChart(tgl_awal = null, tgl_akhir = null) {
        $.ajax({
            url: "{{ route('dashboard.helpdesk.electromedis') }}",
            type: "GET",
            dataType: "json",
            data: {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir
            },
            success: function (data) {

                console.log("Teknik Response:", data);

                let seriesData = [
                    data.done ?? 0,
                    data.accept ?? 0,
                    data["on-progress"] ?? 0
                ];

                if (elektroMedisChart) {
                    elektroMedisChart.updateSeries(seriesData);
                    return;
                }

                elektroMedisChart = new ApexCharts(document.querySelector("#ElectroMedis"), {
                    series: seriesData,
                    chart: {
                        type: 'pie',
                        height: 280
                    },
                    labels: ['Done', 'Accept', 'On Progress'],
                    colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                    legend: { position: 'bottom' }
                });

                elektroMedisChart.render();
            }
        });
    }


    // General Affair Pie Chart
    function loadGeneralAffairChart(tgl_awal = null, tgl_akhir = null) {

        $.ajax({
            url: "{{ route('dashboard.helpdesk.general_affair') }}",
            type: "GET",
            dataType: "json",
            data: {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir
            },
            success: function (data) {

                console.log("Teknik Response:", data);

                let seriesData = [
                    data.done ?? 0,
                    data.accept ?? 0,
                    data["on-progress"] ?? 0
                ];

                if (generalAffairChart) {
                    generalAffairChart.updateSeries(seriesData);
                    return;
                }

                generalAffairChart = new ApexCharts(document.querySelector("#GeneralAffair"), {
                    series: seriesData,
                    chart: {
                        type: 'pie',
                        height: 280
                    },
                    labels: ['Done', 'Accept', 'On Progress'],
                    colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                    legend: { position: 'bottom' }
                });

                generalAffairChart.render();
            }
        });
    }
</script>