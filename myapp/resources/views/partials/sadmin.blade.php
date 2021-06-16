<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset("bootstrap/css/bootstrap.css")}}">
    <link rel="stylesheet" href="{{asset("bootstrap/css/custom.css")}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <title>@yield('title')</title>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">

            <a class="navbar-brand" href="#">Nice Diagnostic Clinics</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <form class="d-flex">
                    <img src="{{asset('icons/profile.png')}}" style=" margin-right: 5px; width: 30px; height: 30px;"
                        alt="">
                    <span>Admin</span>
                </form>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-2">
            <div class="side-bar">
                <img src="{{asset('icons/dashbord.svg')}}" style="font-size: 50px;" alt="">
                <ul>
                    <li><a href="{{url('admin/dashboard')}}"> <i class="bi bi-rainbow"></i> Dashboard</a></li>
                    <li><a href="{{url('admin/referrals')}}"><i class="bi bi-puzzle-fill"></i> Referrals</a></li>
                    <li><a href="{{url('admin/department')}}"><i class="bi bi-puzzle-fill"></i> Department</a></li>
                    <li><a href="{{url('admin/tests")}}'><i class="bi bi-x-diamond-fill"></i> Tests</a></li>
                    <li><a href="{{url('admin/patients')}}"><i class="bi bi-person"></i> Patients</a></li>
                    <li><a href="{{url('admin/staff')}}"><i class="bi bi-person-fill"></i> Staff</a></li>
                    <li><a href="{{url('admin/search')}}"><i class="bi bi-search"></i> Search</a></li>
                    <li><a href="{{url('admin/branches')}}"><i class="bi bi-dice-5-fill"></i> Branches</a></li>
                    <li><a href="{{url('admin/password/change')}}"><i class="bi bi-file-lock-fill"></i> Change
                            Password</a></li>
                                       
                    <li><a href="{{url('admin/debts')}}"><i class="bi bi-file-lock-fill"></i> Debts</a></li>
                    <li><a href="{{url('admin/complete-pay')}}"><i class="bi bi-file-lock-fill"></i> Completed Payment</a></li>
                    <li><a href="{{url("admin/backups")}}"><i class="bi bi-file-lock-fill"></i> Backup</a></li>
                    <li><a href="{{url("/")}}"><i class="bi bi-power"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-10">
            @yield('content')
        </div>
    </div>
    <script src="{{asset("bootstrap/jquery-3.4.1.min.js")}}"></script>
    <script src="{{asset("bootstrap/js/bootstrap.min.js")}}"></script>
    <script>
        $(document).ready(function(){
    
    console.log($("input#pat_id")); 
           
            var idselect = $("#idselect").val();
            console.log("idselect" + idselect);
            console.log(idselect);
            var testdoneid = "";
            var testprice = "price-value";
            var price = "price";
            var selectid = "select-test-" +idselect;
            var total = Array();
            var sdata;
                $("#addbtn").on("click", function(e){
                    e.preventDefault();
                    
                    let uniqueid = Date.now()
                     testdoneid = "testdone-" + uniqueid;
                    
                     testprice = "testprice-" + uniqueid;
                     price = "price-" + uniqueid;
                     selectid = "select-" + uniqueid;
    
                    var testdone =   $('#testdone')
                        .clone().val('') // CLEAR VALUE.
                        .attr("id", testdoneid)
                        .appendTo("#appendcontainer");
    
                    console.log("#"+testdoneid + " .select-test-" + idselect);
                    $("#"+testdoneid + " .price-value").attr('id', testprice);
                    $("#"+testdoneid + " .price-value").text('');
                    $("#"+testdoneid + " .price").attr('id', price);
                    $("#"+testdoneid + " .select-test-" + idselect).attr('id', selectid);
                    $("#"+testdoneid).removeClass("remove-display");
    
                });
    
             
                $('form#create').on('change', 'select', function(){
    
                    var uniqueid = $("#uniqueid").val();
                    var id = $(this).val();
                    var strChosen = $(this).attr('id');
                    console.log(strChosen);
                    // if(strChosen === "select-test"){
                    //     $.get("api/get-test-price/" + id + "?selectid="+strChosen+"&uniqueid="+uniqueid, function(data, status){
                    //         total.push(data);
                    //         $("#price").val(data.price);
                    //         $("#price-value").text("N"+data.price);
                    //         $("#total").text("N"+data.total);
                    //     });
                    // }else{
                        var values = strChosen.substring(7, 20);
                        var prices = price.substring(0, 6);
                        var testprices = testprice.substring(0, 10);
                        var priceid = prices + "" + values;
                        console.log(testprices);
                        var testpriceid = testprices + "" + values;
                        console.log("testpriceid " + testpriceid);
                        $.get("api/get-test-price/" + id + "?selectid="+strChosen+"&uniqueid="+uniqueid, function(data, status){
                           total.push(data);
                        $("#"+ priceid).val(data.price);
                        $("#"+ testpriceid).text("N"+data.price);
                        $("#total").text("N"+data.total);
                        });
                    // }
                });
    
             
                $('form#edit').on('change', 'select', function(){
                    var id = $(this).val();
                    var strChosen = $(this).attr('id');
                        var uniqueid = $("#uniqueid").val();
                        console.log("my unique " + uniqueid);
                        var values = strChosen.substring(11, 12);
                        var prices = price.substring(0, 6);
                        var testprices = testprice.substring(0, 10);
                        var priceid = prices + "" + values;
                        var testpriceid = testprices + "" + values;
                        var pricevalueid = "price-value"+ values;
                        console.log(pricevalueid);
                        $.get("api/get-test-price/" + id + "?selectid="+pricevalueid+"&pat_id="+$("#pat_id").val()+"&uniqueid="+uniqueid, function(data, status){
                           console.log(data.total);
                        $("#price-value"+ values).text("N"+data.price);
                        $("#total").text("N"+data.total);
                        });
                  
                });
            });
            
    
            $("#search-btn").on("click",function(){
                var search = $("#search-id").val();
                var startdate = $("#start-date").val();
                var enddate = $("#end-date").val();
                $("#loading").removeClass("remove-display");
                setTimeout(() => {
                    $.get("api/test/search/?searchitem="+search+"&startdate="+startdate+"&enddate="+enddate,
                    function(data, status){
                        if(data === "noresult"){
                            $("#no-result").removeClass("remove-display");
                            $("#loading").addClass("remove-display");
                            $("#tbody").html($("#no-result").html());
                        }else{
                            $("#loading").addClass("remove-display");
                            $("#no-result").addClass("remove-display");
                            $("#tbody").html(data.table);
                            $("span#total").html(data.total);
                        }
              
                    });
                }, 1000);
            });
    
        $(document).ready( function () {
        $('table').DataTable();
        } );
    </script>

    <script src="{{asset("libraries/datatables/DataTables-1.10.23/js/jquery.dataTables.js")}}"></script>
</body>

</html>