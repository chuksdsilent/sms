@extends('partials.sadmin')
@section('title', 'Dashboard')
@section('content')
<div class="main-content dashboard">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-bing border-radius">
                    <a href="{{url("admin/overall-test")}}">
                        <div class="card-body">
                            <div class="dashboard-item">
                                <div class="item-number">
                                    <?php $total = 0;?>
                                    @foreach ($medicals as $item)

                                    <?php $total += $item->price; ?>
                                    @endforeach
                                    N{{$total}}
                                </div>
                                <div class="item-title">
                                    Overall Amount
                                </div>
                            </div>

                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bingse border-radius">
                    <a href="{{url("admin/overal-patient")}}">
                        <div class="card-body">
                            <div class="dashboard-item">
                                <div class="item-number">
                                    {{ \App\Patients::count()}}
                                </div>
                                <div class="item-title">
                                    Overall Patients Registered
                                </div>
                            </div>

                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bing border-radius">
                    <a href="{{url("admin/today-test")}}">
                        <div class="card-body">
                            <div class="item-number">
                                <?php $total = 0;?>
                                @foreach ($price as $item)

                                <?php $total += $item->price; ?>
                                @endforeach
                                N{{$total}}
                            </div>
                            <div class="item-title">
                                Amount for Today
                            </div>

                        </div>
                    </a>

                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-4">
                <div class="card card-bingse border-radius">
                    <a href="{{url("admin/today-patient")}}">
                        <div class="card-body">
                            <div class="dashboard-item">
                                <div class="item-number">
                                    {{ \App\Patients::where("created_at", \Carbon\Carbon::today()->format("Y-m-d"))->count()}}
                                </div>
                                <div class="item-title">
                                    Patients Registered Today
                                </div>
                            </div>

                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bingse border-radius">
                    <a href="{{url('admin/loggedin-staff')}}">
                        <div class="card-body">
                            <div class="dashboard-item">
                                <div class="dashboard-item">
                                    <div class="item-number">
                                        {{\App\User::where("loggedin", 1)->count()}}
                                    </div>
                                    <div class="item-title">
                                        Staff Logged in
                                    </div>
                                </div>
                            </div>

                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection