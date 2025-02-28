<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- Bootstrap Table js-->
<script src="{{ asset('assets/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js') }}"></script>
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
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon-clipart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script id="menu" src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
{{-- Datepicker --}}
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

<!-- Sweetalert -->
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{-- Select2 --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>


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
    <script src="{{ asset('assets/js/layout-change.js') }}"></script>
@endif

@if (Route::currentRouteName() == 'index')
    <script>
        new WOW().init();
    </script>
@endif
@yield('script')
{{-- Custom JS --}}
<script src="{{ asset('assets/js/custom.js') }}"></script>

