@extends('layouts.simple.master')
@section('title', $title)

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ $menuTitle }}</li>
    <li class="breadcrumb-item active">{{ $menuSubtitle }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="header-top">
                            <h5 class="m-0">All Campaigns</h5>
                            <div class="card-header-right-icon">
                                <div class="dropdown icon-dropdown">
                                    {{-- Button Add Form Tarif --}}
                                    <a href="{{ route('master-data.tarif-tindakan.form') }}"
                                        class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Tarif</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 campaign-table">
                        <div class="recent-table table-responsive currency-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="f-light">AD Platform</th>
                                        <th class="f-light">Campaign</th>
                                        <th class="f-light">GEO</th>
                                        <th class="f-light">Profitability</th>
                                        <th class="f-light">Max Participation Avai.</th>
                                        <th class="f-light">Status</th>
                                        <th class="f-light">Create</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-icon facebook">
                                            <div>
                                                <div class="social-circle"><i class="fa fa-facebook"></i></div>
                                            </div>
                                        </td>
                                        <td>Jane Cooper</td>
                                        <td>UK</td>
                                        <td>
                                            <div class="change-currency"><i class="font-success me-1"
                                                    data-feather="trending-up"></i>45.6%</div>
                                        </td>
                                        <td>$9,786</td>
                                        <td>
                                            <button class="btn badge-light-primary">Active</button>
                                        </td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-icon instagram">
                                            <div>
                                                <div class="social-circle"><i class="fa fa-instagram"></i></div>
                                            </div>
                                        </td>
                                        <td>Floyd Miles</td>
                                        <td>DE</td>
                                        <td>
                                            <div class="change-currency"><i class="font-danger me-1"
                                                    data-feather="trending-down"></i>12.3%</div>
                                        </td>
                                        <td>$19,7098</td>
                                        <td>
                                            <button class="btn badge-light-primary">Active</button>
                                        </td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-icon pinterest">
                                            <div>
                                                <div class="social-circle"><i class="fa fa-pinterest"></i></div>
                                            </div>
                                        </td>
                                        <td>Guy Hawkins</td>
                                        <td>ES</td>
                                        <td>
                                            <div class="change-currency"><i class="font-success me-1"
                                                    data-feather="trending-up"></i>65.6%</div>
                                        </td>
                                        <td>$90,986</td>
                                        <td>
                                            <button class="btn badge-light-primary">Active</button>
                                        </td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-icon twitter">
                                            <div>
                                                <div class="social-circle"><i class="fa fa-twitter"></i></div>
                                            </div>
                                        </td>
                                        <td> Travis Wright</td>
                                        <td>ES</td>
                                        <td>
                                            <div class="change-currency"><i class="font-danger me-1"
                                                    data-feather="trending-down"></i>35.6%</div>
                                        </td>
                                        <td>$23,654</td>
                                        <td>
                                            <button class="btn badge-light-light disabled">Inactive</button>
                                        </td>
                                        <td>
                                            <button class="plus-btn">+ </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-icon you-tube">
                                            <div>
                                                <div class="social-circle"><i class="fa fa-youtube-play"></i></div>
                                            </div>
                                        </td>
                                        <td>Mark Green</td>
                                        <td>UK</td>
                                        <td>
                                            <div class="change-currency"><i class="font-success me-1"
                                                    data-feather="trending-up"></i>45.6%</div>
                                        </td>
                                        <td>$12,796</td>
                                        <td>
                                            <button class="btn badge-light-light disabled" type="button">Inactive</button>
                                        </td>
                                        <td>
                                            <button class="plus-btn" type="button">+ </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_5.js') }}"></script>
@endsection
