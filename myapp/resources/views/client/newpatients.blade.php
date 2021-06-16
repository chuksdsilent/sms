@extends('partials.client')
@section('title', 'New Patient Test')
@section('content')
<style>
    .textfield{
        display: none;
    }
    #otherDepartment{
        display: none;
    }
</style>
<div class=" custom my-4">
    <div class="card">
        <div class="card-body">
            @if (Session::has("msg"))
            <div class="alert alert-success">
                <h6>{{Session::get("msg")}}</h6>
            </div>
            @endif
            <h3>New Patient Test</h3>
            <hr />
            <form  action="{{url('staff/patient/store')}}" method="POST">
                <?php $idselect=Illuminate\Support\Str::random(10); ?>
                <input type="hidden" value="{{Illuminate\Support\Str::random(10)}}" id="uniqueid">
                <input type="hidden" value="{{$idselect}}" id="idselect">
                @csrf

                <label for="">Choose</label>
                <select name="" id="patient_status" class="form-control my-2">
                    <option value="1">New Patient</option>
                    <option value="2">Old Patient</option>
                </select>
                <div class="old-patient" id="old-patient">
                    <label for="">Patient ID</label><input type="text" name="patient_id" id="pat_id"
                        class="form-control my-3">
                </div>
                <div class="my-3">
                    <div id="loading">Loading data...</div>
                    <div class="old_name" id="old_name">
                        <span id="old_first_name" style="color: black; font-size: 16px"></span> <span id="old_last_name"
                            style="color: black; font-size: 16px"></span>
                    </div>
                </div>
                <div class="new-patient" id="new-patien">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="my-3">
                                <label for="" class="form-label">First Name</label><input type="text" name="first_name"
                                    id="first_name" class="form-control" value={{ old("first_name") }}>
                                @if ($errors->first("first_name"))
                                <span>{{$errors->first("first_name")}}</span>
                                @endif
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="my-3">
                                <label for="" class="form-label">Last Name</label><input type="text" name="last_name"
                                    id="last_name" class="form-control" value={{old("last_name")}}>
                                @if ($errors->first("last_name"))
                                <span>{{$errors->first("last_name")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="my-3">
                        <label for="" class="form-label">Phone No.</label><input oninput="maxLengthCheck(this)"
type = "number"
maxlength = "12"
name="phone" id="phone"
                            class="form-control" value={{ old("phone") }}
                          
                          
                            >
                        @if ($errors->first("phone"))
                        <span>{{$errors->first("phone")}}</span>
                        @endif
                    </div>
                    <div class="my-3">
                        <label for="" class="form-label">Date of Birth</label><input type="date" name="age"
                            id="dateofbirth" class="form-control" value={{old("age")}}>
                        @if ($errors->first("age"))
                        <span>{{$errors->first("age")}}</span>
                        @endif
                    </div>
                    <div class="my-3">
                        <label for="" class="form-label">Gender</label>
                        <select name="gender" id="" class="form-control">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                        @if ($errors->first("gender"))
                        <span>{{$errors->first("gender")}}</span>
                        @endif
                    </div>

                </div>

                <div class="my-3">
                    <label for="" class="form-label">Referred By</label>
                    <select name="referred_by" id="referred_by" class="form-control">@foreach ($referrals as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                        <option value="others">Others</option>
                    </select>
                    <input type="text" name="referred" class="form-control mt-3" id="others">
                    @if ($errors->first("referred_by"))
                    <span>{{$errors->first("referred_by")}}</span>
                    @endif
                </div>

                <div class="my-3">
                    <label for="" class="form-label">Payment Type</label>
                    <select name="paymentType" id="paymentType" class="form-control">
                       <option value="Cash">Cash</option>
                       <option value="Transfer">Transfer</option>
                    </select>
                </div>
                
                <input type="text" name="otherDepartment" class="form-control" id="otherDepartment">

                <div class="add d-flex flex-column justify-content-start my-4">
                    <a href="#" id="addbtn" class="btn btn-danger">Add New Test</a>
                    @if ($errors->first("test_id"))
                    <div style="display: block; font-size: 12px; color: rgb(223, 20, 20);;">
                        <span>{{$errors->first("test_id")}}</span>
                    </div>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        Patient Tests
                    </div>
                    <div class="col-md-4">
                        Prices
                    </div>
                    <div class="col-md-4">
                        Department
                    </div>
                </div>
                
                <div id="testdone" class="remove-display">
                    <div class="row my-4">
                        <div class="col-md-4">
                            <select name="test_id[]"  class="form-control createNewRow"
                                id="select-{{Illuminate\Support\Str::random(10)}}">

                                <option value="" disabled selected>Select Patient Test</option>
                                <!-- <option value="others">Others</option> -->

                                @foreach ($tests as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4 test-price">
                            <input type="hidden" name="price[]" value="" id="price" class="price">
                            {{-- <div class="price-value" id="testprice-{{$idselect}}"></div> --}}
                        <div class="price-value" id="testprice">0</div>
                        
                        </div>
                        
                        <div class="col-md-4">
                            
                            <select name="department[]" id="department" class="form-control">
                            @foreach($departments as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            <option value="6">others</option>
                            </select>
                    </div>
                    {{-- <div class="col-md-1">
                        <span style="color: red; font-weight: bolder; cursor: pointer; display: block;">X</span>
                    </div> --}}
                </div>
        </div>
        <!-- <div class="textfield">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="hidden" name="othertest[]" value="others"> 
                     <input type="text" name="test_id[]"  id="" class="form-control">
                </div>
                <div class="col-md-6">
                     <input type="text"  name="price[]" id="otherprice" class="form-control text-price-value" value="0">
                
                </div>
            </div>
        </div> -->
        <div id="appendcontainer"></div>
        <!-- <div id="textfieldcontainer"></div> -->

        <div class="my-3">
            <div class="row">
                <div class="col-md-4">
                    Total
                </div>
                <div class="col-md-4">
                        <!-- <input type="text" name="" id="newtotal" value="0"> -->
                    <div id="total"></div>
                </div>
            </div>
        </div>
        <div class="my-3"><label for="">Amount Paid</label><input type="text" name="amountpaid" id=""
                class="form-control my-3">
        </div>
        <div class="my-3"><label for="">Discount%</label><input type="text" name="discount" id=""
                class="form-control my-3">
        </div>
        <div class="my-3">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>

        </form>
    </div>
</div>
</div>

 
<script>
  // This is an old version, for a more recent version look at
  // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
  function maxLengthCheck(object)
  {
    if (object.value.length > object.maxLength)
      object.value = object.value.slice(0, object.maxLength)
  }
</script>
@endsection