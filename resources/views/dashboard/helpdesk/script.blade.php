<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script type="text/javascript">
    // ICT Pie Chart
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector("#ICT")) {
            fetch("{{ route('dashboard.helpdesk.view') }}")
                .then(response => response.json())
                .then(data => {

                    var options = {
                        series: [
                            data.done ?? 0,
                            data.accept ?? 0,
                            data["on-progress"] ?? 0
                        ],
                        chart: {
                            type: 'pie',
                            height: 280
                        },
                        labels: ['Done', 'Accept', 'On Progress'],
                        colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                        legend: {
                            position: 'bottom',
                            fontSize: '14px',
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val.toFixed(1) + "%";
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " Laporan";
                                }
                            }
                        },
                        stroke: {
                            width: 2
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#ICT"), options);
                    chart.render();
                })
                .catch(error => console.error("Error:", error));
        }
    });


    // Teknik Pie Chart
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector("#Teknik")) {
            fetch("{{ route('dashboard.helpdesk.teknik') }}")
                .then(response => response.json())
                .then(data => {

                    var options = {
                        series: [
                            data.done ?? 0,
                            data.accept ?? 0,
                            data["on-progress"] ?? 0
                        ],
                        chart: {
                            type: 'pie',
                            height: 280
                        },
                        labels: ['Done', 'Accept', 'On Progress'],
                        colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                        legend: {
                            position: 'bottom',
                            fontSize: '14px',
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val.toFixed(1) + "%";
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " Laporan";
                                }
                            }
                        },
                        stroke: {
                            width: 2
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#Teknik"), options);
                    chart.render();
                })
                .catch(error => console.error("Error:", error));
        }
    });


   // ElectroMedis Pie Chart
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector("#ElectroMedis")) {
            fetch("{{ route('dashboard.helpdesk.electromedis') }}")
                .then(response => response.json())
                .then(data => {

                    var options = {
                        series: [
                            data.done ?? 0,
                            data.accept ?? 0,
                            data["on-progress"] ?? 0
                        ],
                        chart: {
                            type: 'pie',
                            height: 280
                        },
                        labels: ['Done', 'Accept', 'On Progress'],
                        colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                        legend: {
                            position: 'bottom',
                            fontSize: '14px',
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val.toFixed(1) + "%";
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " Laporan";
                                }
                            }
                        },
                        stroke: {
                            width: 2
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#ElectroMedis"), options);
                    chart.render();
                })
                .catch(error => console.error("Error:", error));
        }
    });


   // General Affair Pie Chart
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector("#GeneralAffair")) {
            fetch("{{ route('dashboard.helpdesk.general_affair') }}")
                .then(response => response.json())
                .then(data => {

                    var options = {
                        series: [
                            data.done ?? 0,
                            data.accept ?? 0,
                            data["on-progress"] ?? 0
                        ],
                        chart: {
                            type: 'pie',
                            height: 280
                        },
                        labels: ['Done', 'Accept', 'On Progress'],
                        colors: ['#4CAF50', '#1E88E5', '#FB8C00'],
                        legend: {
                            position: 'bottom',
                            fontSize: '14px',
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val.toFixed(1) + "%";
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " Laporan";
                                }
                            }
                        },
                        stroke: {
                            width: 2
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#GeneralAffair"), options);
                    chart.render();
                })
                .catch(error => console.error("Error:", error));
        }
    });

    //Tanggal Range Picker
    $('.js-daterangepicker').datepicker({
        dateFormat: 'dd/mm/yyyy',
        range: true,
        multipleDates: true,
        multipleDatesSeparator: ' - ',
        autoClose: true,
        toggleSelected: false,
        clearButton: true,

        onSelect: function (formattedDate, date, inst) {

            // Jika tombol clear diklik
            if (!formattedDate) {

                $('#tgl_awal').val(null);
                $('#tgl_akhir').val(null);

                $tablePerizinan.bootstrapTable('refresh', {
                    pageNumber: 1
                });

                // Hilangkan autofocus setelah clear
                setTimeout(function () {
                    $('.js-daterangepicker').blur();
                }, 100);

                return;
            }

            if (!date || date.length < 2) return;

            let start = date[0];
            let end = date[1];

            $('#tgl_awal').val(formatDate(start));
            $('#tgl_akhir').val(formatDate(end));

            $tablePerizinan.bootstrapTable('refresh', {
                pageNumber: 1
            });
        },

        onHide: function (inst) {
            setTimeout(function () {
                $('.js-daterangepicker').blur();
            }, 100);
        }
    });

    // helper format dd/mm/yyyy (untuk tampilan datepicker)
    function formatDisplay(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    // helper format Y-m-d (untuk database)
    function formatDate(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${y}-${m}-${d}`;
    }
</script>