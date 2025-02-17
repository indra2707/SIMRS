<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- Bootstrap Table js-->
<script src="{{ asset('assets/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js') }}"></script>
{{-- <script src="{{ asset('assets/bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap-table/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap-table/dist/extensions/fixed-header/bootstrap-table-fixed-header.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.js') }}"></script> --}}
<script src="{{ asset('assets/tableExport.jquery.plugin/tableExport.min.js') }}"></script>
<script src="{{ asset('assets/tableExport.jquery.plugin/libs/jsPDF/jspdf.umd.min.js') }}"></script>

<!-- feather icon js-->
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<!-- scrollbar js-->
<script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('assets/js/config.js') }}"></script>
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/icons/icons-notify.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon-clipart.js')}}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script id="menu" src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('assets/js/header-slick.js') }}"></script>

<!-- Sweetalert -->
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
{{-- <script src="{{asset('assets/js/sweet-alert/app.js')}}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (Route::current()->getName() != 'popover')
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
@endif

<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>


@if (Route::current()->getName() == 'index')
	<script src="{{asset('assets/js/layout-change.js')}}"></script>
@endif

@if (Route::currentRouteName() == 'index')
    <script>
        new WOW().init();
    </script>
@endif

{{-- Custom JS --}}
<script src="{{ asset('assets/js/custom.js') }}"></script>

@yield('script')
