@extends('layouts.simple.master')

@section('title', 'Default')

@section('css')

@endsection

@section('style')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
	<h3>Default</h3>
@endsection

@section('breadcrumb-items')
	<li class="breadcrumb-item">Dashboard</li>
	<li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row widget-grid">

			<div class="col-xxl-12 col-md-12 box-col-12">
				<div class="card">
					<div class="card-header card-no-border">
						<h5>Filter Tanggal</h5>
					</div>
					<div class="card-body pt-0">
						<div class="row align-items-center">

							<!-- Tanggal Awal -->
							<div class="col-md-2">
								<label class="col-form-label">Tanggal Awal</label>
							</div>
							<div class="col-md-4">
								<input class="form-control js-datepicker digits" name="tgl_awal" type="text"
									placeholder="Tanggal Awal..." data-language="en" required>
							</div>

							<!-- Tanggal Akhir -->
							<div class="col-md-2">
								<label class="col-form-label">Tanggal Akhir</label>
							</div>
							<div class="col-md-4">
								<input class="form-control js-datepicker digits" name="tgl_akhir" type="text"
									placeholder="Tanggal Akhir..." data-language="en" required>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- ICT Chart -->
			<div class="col-xxl-3 col-md-6 box-col-6">
				<div class="card">
					<div class="card-header card-no-border">
						<h5>ICT</h5><span class="f-light f-w-500 f-14">(Total Laporan)</span>
					</div>
					<div class="card-body pt-0">
						<div class="monthly-profit">
							<div id="ICT"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Teknik Chart -->
			<div class="col-xxl-3 col-md-6 box-col-6">
				<div class="card">
					<div class="card-header card-no-border">
						<h5>Teknik</h5><span class="f-light f-w-500 f-14">(Total Laporan)</span>
					</div>
					<div class="card-body pt-0">
						<div class="monthly-profit">
							<div id="Teknik"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Atem Chart -->
			<div class="col-xxl-3 col-md-6 box-col-6">
				<div class="card">
					<div class="card-header card-no-border">
						<h5>Electro Medis</h5><span class="f-light f-w-500 f-14">(Total Laporan)</span>
					</div>
					<div class="card-body pt-0">
						<div class="monthly-profit">
							<div id="ElectroMedis"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- General Affair Chart -->
			<div class="col-xxl-3 col-md-6 box-col-6">
				<div class="card">
					<div class="card-header card-no-border">
						<h5>General Affair</h5><span class="f-light f-w-500 f-14">(Total Laporan)</span>
					</div>
					<div class="card-body pt-0">
						<div class="monthly-profit">
							<div id="GeneralAffair"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

@endsection


@section('script')
    @include('dashboard.helpdesk.script')
@endsection