<?php

namespace App\Http\Controllers;

use App\Deposits;
use App\Discounts;
use App\Http\Requests\PatientRequest;
use App\LoggedInStaff;
use App\Medicals;
use App\PatientReferral;
use App\Patients;
use App\Referrals;
use App\SelectedTest;
use App\StaffActivities;
use App\Tests;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Carbon\Carbon;
use App\CurrentMonth;
use App\Departments;
use DB;
use App\Debts;
class PatientsController extends Controller
{

    public function updatePatient($id)
    {
        Patients::where("id", $id)->update(request()->except("_token"));
        return redirect()->back()->with("msg", "Updated Successfully.")->withInput();
    }
    public function editPatient($id)
    {


        return view("admin.editPat")->with("pat", Patients::find($id));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("client.newpatients")->with('tests', Tests::all())->with("departments", Departments::all())->with("referrals", Referrals::all());
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set("Africa/Lagos");
        
        if(request()->get("otherDepartment") != ""){
            
            $deptid = Departments::latest("id")->first();
           
            Departments::create(["name" => request()->get("otherDepartment")]);
            request()->request->add(["department" => $deptid["id"] + 1]);
        }
 
        $referred_by = request()->get("referred");
        if (!empty($referred_by)) {
            $refers =  Referrals::latest("id")->first();
            Referrals::create(["name" =>  request()->get("referred") ]);
            request()->request->add(["referred_by" => $refers["id"] + 1]);
            
        }
        // return request()->get("otherDepartment");
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'referred_by' => 'required',
            'test_id' => 'required|array'
        ], [
            'first_name.required' => "First Name is required",
            'last_name.required' => "Last Name is required",
            'phone.required' => "Phone is required",
            'age.required' => "Date of Birth is required",
            'gender.required' => "Gender is required",
            'referred_by.required' => "Referred by is required",
            'test_id.required' => "Test is required",
        ]);
        $tests = $request->input("test_id");
        $request->request->add(['branch_id' => $request->session()->get('branch')]);

        $price = $request->input("price");

        $trx_id = uniqid();
        $request->request->add(["trx_id" => $trx_id]);

      
        $now = Carbon::now();
              
        $currentMonth = new CurrentMonth();
        
        if (empty($request->get("patient_id"))) {
            

            
             $diffInYear =  $now->year - CurrentMonth::where("id", 1)->value("year");
             $diffInMonth =  $now->month - CurrentMonth::where("id", 1)->value("month");



            if($diffInMonth > 0){
                
                CurrentMonth::where('id', 1)->update(["month" => $now->month]);
               
                $last_serial_no =  Patients::latest()->value("id");
                
                $pat_id = date("d")."".  sprintf("%'. 02d",  $now->month). "". date("y")."".  sprintf("%'. 03d", 1);
                $request->request->add(["pat_id" => $pat_id]);
        
                 $id = Patients::create($request->except("tests_id", "othertest", "otherDepartment", "paymentType", "department", "amountpaid", 'branch_id', "discount", "patient_id", "referred", "trx_id", "_token", "price", "test_id", "referred_by", "deposit", "test_id"))->id;
                 $request->request->add(['patients_id' => $id]);
            }else{
            

                 $last_id = substr(DB::table("patients")->orderBy("id", "desc")->value("pat_id"), 6, 10) + 1;

                 
             
            if($diffInYear > 0 && CurrentMonth::where("id", 1)->value("month") == 12){
                CurrentMonth::where('id', 1)->update(["month" => 1, "year" => $now->year]);
                $pat_id = date("d")."".  sprintf("%'. 02d",  $now->month). "". date("y")."".  sprintf("%'. 03d", 1);

            }else{
                $pat_id = date("d")."".  sprintf("%'. 02d",  $now->month). "". date("y")."".  sprintf("%'. 03d", $last_id);

            }
                $request->request->add(["pat_id" => $pat_id]);

                 $id = Patients::create($request->except("tests_id", "othertest", "otherDepartment",  "paymentType", "department",  "amountpaid", 'branch_id', "discount", "patient_id", "referred", "trx_id", "_token", "price", "test_id", "referred_by", "deposit", "test_id"))->id;
                 $request->request->add(['patients_id' => $id]);
               

                 
            }

         
        } else {
            $id = Patients::where("pat_id", $request->get("patient_id"))->orWhere("phone", $request->get("patient_id"))->value("id");
            request()->request->add(["patients_id" => $id]);
            
        }
        Discounts::create(["discount" => $request->get("discount"), "trx_id" => $trx_id]);
        PatientReferral::create($request->except("tests_id", "othertest", "otherDepartment",  "paymentType", "department", "amountpaid", 'branch_id', "discount", "first_name", "last_name", "patient_id", "referred", "gender", "pat_id", "phone", "_token", "price", "patient_name", "age", "test_id", "_token", "price", "test_id", "deposit", "test_id"))->id;

        
        $mydepts = request()->get("department");
        array_shift($mydepts);
        
        
        $department = request()->get("department");
        $total = 0;
        foreach ($tests as $key => $value) {

            if (!is_null($value)) {
                $request->request->add(["tests_id" => $value]);
                $request->request->add(["price" => Tests::where('id', $value)->value("price")]);
                $request->request->add(["department" => $mydepts[$key]]);
                $total += Tests::where('id', $value)->value("price");
                Medicals::create($request->except("otherDepartment","amountpaid", "discount", "first_name", "last_name", "patient_id", "referred", "gender", "pat_id", "deposit", "_token", "phone", "patient_name", "age", "test_id"));
            }
        }
        
       


        $pat_data = Patients::find($id);
        $med_data = Medicals::where("trx_id", $trx_id)->get();

        $staff_id = Auth::user()->id;

        StaffActivities::create(["staffs_id" => $staff_id, "activity" => "Registered a new Patient"]);
        $balance = $total -  $request->get("amountpaid");
        if($balance > 0){
            Debts::create(["balance" => $balance, 'pat_id' => $request->get("pat_id"), 'amount_paid' => $request->get("amountpaid"), 'trx_id' => $trx_id, "completed" => "N"]);
        }
        return view("client.receiptd")->with("referred_by", request()->get("referred_by"))->with( "amountpaid", $request->get("amountpaid"))->with("pat_data", $pat_data)->with("med_data", $med_data)->with("trx_id", $trx_id);
    }

    /**
     * Display the specified resource.`
     *
     * @param  \App\Patients  $patients
     * @return \Illuminate\Http\Response
     */
    public function show(Patients $patients)
    {
        return view("client.test")->with("tests", Patients::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patients  $patients
     * @return \Illuminate\Http\Response
     */
    public function edit(Patients $patients, $id)
    {

        $pat_data = Patients::find($id);

        return view("client.editpatients")
            ->with('id', $id)
            ->with("med_test", Medicals::where("patients_id", $id)->whereDate("created_at", request()->get("thedate"))->get())
            ->with("pat_data", $pat_data)
            ->with('tests', Tests::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patients  $patients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patients $patients)
    {

        $request->validate([], []);
        $tests = $request->input("tests_id");

        $price = $request->input("price");
        $medical_id = $request->input("medical_id");
        $referral_id = $request->input("referral_id");
        $deposit_id = $request->input("deposit_id");
        $patients_id = $request->input("patient_id");

        $id =   Patients::where('id', $patients_id)->update($request->except(
            "med_id",
            "_method",
            "patients_id",
            "medical_id",
            "referral_id",
            "deposit_id",
            "patient_id",
            "trx_id",
            "_token",
            "price",
            "tests_id",
            "referred_by",
            "deposit",
            "test_id"
        ));
        $request->request->add(["patients_id" => $id]);

        $id = PatientReferral::where('id', $referral_id)->update($request->except(
            "med_id",

            "patients_id",
            "_method",
            "medical_id",
            "referral_id",
            "deposit_id",
            "patient_id",
            "pat_id",
            "phone",
            "_token",
            "price",
            "patient_name",
            "age",
            "tests_id",
            "_token",
            "price",
            "test_id",
            "deposit",
            "test_id"
        ));
        // Deposits::where('id', $deposit_id)->update($request->except(
        //     "med_id",

        //     "patients_id",
        //     "_method",
        //     "medical_id",
        //     "referral_id",
        //     "deposit_id",
        //     "patient_id",
        //     "pat_id",
        //     "phone",
        //     "_token",
        //     "price",
        //     "patient_name",
        //     "referred_by",
        //     "age",
        //     "tests_id",
        //     "_token",
        //     "price",
        //     "test_id",
        //     "test_id"
        // ));

        $med_ids = $request->get("med_id");
        // dd($med_id);

        // echo $med_id[1];
        if (count($tests) > 0) {
            foreach ($tests as $key => $value) {
                if (!is_null($value)) {
                    $request->request->add(["tests_id" => $value]);;
                    $med_id = Medicals::where("tests_id", $value)->value("id");


                    Medicals::where('id', $med_ids[$key])->update($request->except(
                        "med_id",

                        "_method",
                        "patients_id",
                        "medical_id",
                        "referral_id",
                        "deposit_id",
                        "patient_id",
                        "pat_id",
                        "deposit",
                        "_token",
                        "phone",
                        "price",
                        "patient_name",
                        "age",
                        "test_id",
                        "referred_by"
                    ));
                }
            }
        }

        SelectedTest::truncate();

        return redirect()->back()->with("msg", " Patient Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patients  $patients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patients $patients)
    {
        //
    }

    public function getPrice($id)
    {
        $price = Tests::find($id)->price;
      
        return response()->json(["price" => $price]);
    }

    public function getEdtPrice($id)
    {


        $price = Tests::find($id)->price;
        $selectid = request()->get("selectid");
        $pat_id = request()->get("pat_id");

        $testuniqueid = request()->get("uniqueid");


        if (SelectedTest::where("test_id", $selectid)->where("uniqueid", $testuniqueid)->exists()) {
            SelectedTest::where("test_id", $selectid)->where("uniqueid", $testuniqueid)->update(['price' => $price]);
        } else {
            SelectedTest::create(["uniqueid" => $testuniqueid, "test_id" => $selectid, "price" => $price]);
        }


        $pat_total = Medicals::where("patients_id", $pat_id)->get();


        $total = 0;
        foreach ($pat_total as $item) {
            $total += Tests::where("id", $item->tests_id)->value("price");
        }

        $selected_total = SelectedTest::where("uniqueid", $testuniqueid)->sum("price");

        $sumtotal = $selected_total + $total;


        return response()->json(["price" => $price, "total" => $sumtotal]);
    }
}
