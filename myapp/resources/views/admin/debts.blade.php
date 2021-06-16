@extends('partials.sadmin')
@section('title', 'Dashboard')
@section('content')

<div class="  my-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
            <h2>Debts</h2>
            
            </div>
            <hr>

            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Pat. ID</th>
                    <th>Trx ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Balance</th>
                    <th>Date</th> 
                    <th>Action</th> 
                </thead>
            @foreach($debts as $key => $debt)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$debt->pat_id}}</td>
                <td>{{$debt->trx_id}}</td>
                <td>{{\App\Patients::where("pat_id", $debt->pat_id)->value("first_name")}}</td>
                <td>{{\App\Patients::where("pat_id", $debt->pat_id)->value("last_name")}}</td>
                <td style="color: red;">N{{\App\Medicals::where("trx_id", $debt->trx_id)->sum("price") - $debt->amount_paid}}</td>

                <td>{{Carbon\Carbon::parse($debt->created_at)->format("d F, Y")}}</td>
                
                <td><a href="{{url('admin/receipt/' . $debt->pat_id .'?trx_id=' . $debt->trx_id)}}" class="btn btn-success">View Receipt</a></td>
                
            </tr>
            @endforeach
            </table>

        </div>
    </div>
</div>
@endsection
