@extends('layouts.simple.master')
@section('title', 'JS Grid Tables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/jsgrid.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
<h3>JS Grid Tables</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Tables</li>
<li class="breadcrumb-item active">JS Grid Tables</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Basic Scenario</h5>
					<span>Grid with filtering, editing, inserting, deleting, sorting and paging. Data provided by controller.</span>
				</div>
				<div class="card-body">
					<div id="basicScenario"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/jsgrid/jsgrid.min.js')}}"></script>
<script src="{{asset('assets/js/jsgrid/griddata.js')}}"></script>
<script src="{{asset('assets/js/jsgrid/jsgrid.js')}}"></script>
@endsection
