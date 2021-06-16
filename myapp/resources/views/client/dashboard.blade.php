@extends('partials.client')
@section('title', 'Dashboard')
@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-bing border-radius">
                    <div class="card-body">
                        <div class="dashboard-item">
                            <a href="{{url('staff/test/all')}}">
                                <div class="item-number">
                                    {{ \App\Medicals::count()}}
                                </div>
                                <div class="item-title">
                                    Total Test Conducted
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bing border-radius">
                    <div class="card-body">
                        <div class="dashboard-item">
                            <a href="{{url('staff/test/today')}}">
                                <div class="item-number">
                                    {{ \App\Medicals::where("created_at", \Carbon\Carbon::today()->format("Y-m-d"))->count()}}
                                </div>
                                <div class="item-title">
                                    Total Test Conducted Today
                                </div>

                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bing border-radius">
                    <div class="card-body">
                        <div class="dashboard-item">
                            <a href="{{url('staff/test/today')}}">
                                <div class="item-number">
                                    N{{ \App\Medicals::where("created_at", \Carbon\Carbon::today()->format("Y-m-d"))->sum("price")}}
                                </div>
                                <div class="item-title">
                                    Amount Made Today
                                </div>

                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection