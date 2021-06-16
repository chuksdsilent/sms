<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset("bootstrap/css/bootstrap.css")}}">
    <link rel="stylesheet" href="{{asset("bootstrap/css/client.css")}}">
    <link rel="stylesheet" href="{{asset("libraries/datatables/DataTables-1.10.23/css/jquery.dataTables.css")}}">
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
                    {{-- <img src="{{asset('icons/profile.png')}}" style=" margin-right: 5px; width: 30px; height:
                    30px;"
                    alt=""> --}}
                    <span class="mx-1"> {{\App\Staff::where("user_id", Auth::user()->id)->value("first_name")}} </span>
                    <span class="mx-1"> {{\App\Staff::where("user_id", Auth::user()->id)->value("last_name")}} </span>
                </form>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-2">
            <div class="side-bar">

                <ul>
                    <li><a href="{{url("staff/dashboard")}}"><i class="bi bi-rainbow"></i> Dashboard</a></li>
                    {{-- <li><a href="{{url("staff/patient/search")}}">Search</a></li> --}}
                    <li><a href="{{url("staff/patient/create")}}"><i class="bi bi-person"></i> Create Patient
                            Test</a></li>
                    <li><a href="{{url("staff/tests")}}"><i class="bi bi-x-diamond-fill"></i> Tests</a></li>
                    <li><a href="{{url("staff/password/change")}}"><i class="bi bi-file-lock-fill"></i> Change
                            Password</a></li>
                    <li><a href="{{url("staff/debts")}}"><i class="bi bi-file-lock-fill"></i> Debts</a></li>
                    <li><a href="{{url("/staff/login")}}"><i class="bi bi-power"></i> Logout</a></li>
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
        $("input#pat_id").keyup( function(e){
            console.log(this.value);
            $("#loading").css({"display": "block"});
           
            $.get("api/get-info/" + $(this).val(), function(data, status){
                if(data.first_name === null){
                    $("#loading").css({"display":"block"});
                    $("#loading").html("Result not found");
                    $("#others").css({"display":"none"});
                }else{
                    $("#loading").css({"display":"none"});
                    $("#old_name").css({"display":"block"});
                    $("#first_name").val(data.first_name);
                    $("#last_name").val(data.last_name);
                    $("#dateofbirth").val(data.dateofbirth);
                    $("#phone").val(data.phone);
                

                }
            });
        }).on('keydown', function(e) {
        if (e.keyCode==8)
            $("#old_name").css({"display":"none"});
        });
        $("#referred_by").change(function(){
            const ref = $("#referred_by").val();
            if(ref === "others"){
                
                $("#others").css({"display": "block"});
            }else{
                $("#others").css({"display": "none"});
            }
        })
        $("#patient_status").change(function(){
            const ref = $("#patient_status").val();
            if(ref === "2"){
                $("#old-patient").css({"display": "block"});
                $("#new-patient").css({"display": "none"});
            }else{
                $("#new-patient").css({"display": "block"});
                $("#old-patient").css({"display": "none"});
            }
        })
        var idselect = $("#idselect").val();
        console.log("idselect" + idselect);
        console.log(idselect);
        var testdoneid = "";
        var testprice = "price-value";
        var price = "price";
        var selectid = "select-test-" +idselect;
        
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

                $("#"+testdoneid + " .price-value").attr('id', testprice);
                $("#"+testdoneid + " .price-value").text('');
                $("#"+testdoneid + " .price").attr('id', price);
                $("#"+testdoneid + " .select-test-" + idselect).attr('id', selectid);
                $("#"+testdoneid).removeClass("remove-display");
                $("#"+testdoneid + " span").attr('class', uniqueid);
            console.log(testdone);
                
            });



            $('form').on('change', '#department', function(){
                var id = $(this).val();
                console.log(id)

                if(id === "6"){
                    $("#otherDepartment").css({"display": "block"});

                }

            });
            $('form').on('change', '.createNewRow', function(){

                    
                    var id = $(this).val();
                    // console.log("finished...")
                    if(id === "others"){

                        let uniqueid = Date.now()
                        let textfieldid = "textfield-" + uniqueid;
                        $("#"+testdoneid ).remove();
                        var done =   $('.textfield')
                        .clone().val('') // CLEAR VALUE.
                        .attr("id", textfieldid)
                        .appendTo("#textfieldcontainer");

                    $("#"+textfieldid).removeClass("textfield");
                        console.log("done is", done)
                    
                    }else{

            
                        $.get("api/get-test-price/" + id, function(data, status){
                            
                           $("#" + testprice).text(data.price);
                           $("#" + price).text(data.price);

                           var sumse = 0;
                        console.log("this witch" + sumse)
                        $('.price-value').each(function(){
                                console.log("the price value is " + $(this).text())
                                sumse += parseFloat($(this).text()); // Or this.innerHTML, this.innerText
                            });
                             $("#total").text(sumse);
                          
                        });
                        

                        
                    }
                    
                            
                 
            });
                //  let testsum = 0;
                    // $("form").on("blur", "#otherprice", function(){
                    //     console.log("key pressed...")
                        
                    //     $('.text-price-value').each(function(){
                            
                            
                    //         let textvalue = $(this).val()  || 0;
                    //         if($.isNumeric(textvalue)){
                    //             console.log("parse int",  parseInt(textvalue))
                    //                 testsum += parseInt(textvalue); // Or this.innerHTML, this.innerText
                    //         }
                            
                    //     });
                    //     total = testsum + $("#newtotal").val();

                    //     $("#total").text($("#newtotal").val());

                    // });
                //     console.log("show sum " + testsum)
                 
                   
            // $('form#create').on('change', 'select', function(){
            
            //     var total = 0
            //     $('.addition > .price').each(function() {
            //         var currentValue = parseInt($(this).val(), 10);
                
            //         if (!isNaN(currentValue)) {
            //             total += currentValue;
            //         }
            //         $(".total").val(total);
            //     });
                
            // });
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
                    console.log(data);
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