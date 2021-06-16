<?php

namespace App\Http\Controllers;

use App\Branches;
use App\LoggedInStaff;
use Illuminate\Support\Facades\Hash;
use App\Staff;
use App\User;
use App\Medicals;
use App\Patients;
use App\StaffActivities;
use App\Tests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Debts;
class StaffController extends Controller
{

    public function debts(){

        $debts = Debts::latest()->where("completed", "N")->get();
        return view("client.debts")->with("debts", $debts);
    }
    public function updatePassword()
    {

        request()->validate([
            'oldpassword' => "required",
            'newpassword' => "required",
            'confirmpassword' => "required"
        ], [
            "oldpassword.required" => "Old password is required",
            "newpassword.required" => "New password is required",
            "confirmpassword.required" => "Confirm password is required"
        ]);


        if (User::where("email", Auth::user()->email)->exists()) {
            if (Hash::check(request()->get("oldpassword"), Auth::user()->password)) {
                User::where("email", Auth::user()->email)->update(["password" => Hash::make(request()->get("newpassword"))]);
                $staff_id = Auth::user()->id;
                StaffActivities::create(["staffs_id" => $staff_id, "activity" => "Changed Password"]);
                return redirect()->back()->withInput()->with("msg", "Password Updated Successfully.");
            } else {
                return redirect()->back()->withInput()->with("msg", "Old Password does not match our records.");
            }
        } else {
            return redirect()->back()->withInput()->with("msg", "Email does not match our records.");
        }
    }
    public function changePassword()
    {
        return view("client.changepassword");
    }
    public function dashboard()
    {
        $price =  Medicals::where("created_at", "=", \Carbon\Carbon::today()->format("Y-m-d"))->get();

        return view("client.dashboard")
            ->with("tests", Patients::orderBy("created_at", "DESC")->get())
            ->with("price", $price)
            ->with("medicals", Medicals::all());
    }
    public function login()
    {

        if (Auth::check()) {
            User::where("id", Auth::user()->id)->update(["loggedin" => 0]);
            StaffActivities::create(["staffs_id" => Auth::user()->id, "activity" => "logged out"]);
            Auth::logout();
        }
        return view("client.welcome")->with('branches', Branches::all());
    }
    public function showTest($trx_id)
    {
        return view("client.showTest")->with("tests", Medicals::where("trx_id", $trx_id)->get())->with("trx_id", $trx_id);;
    }
    public function allTest()
    {
        return view("client.testse")->with("medical", Medicals::orderBy('created_at', 'DESC')->groupBy("trx_id")->get());
    }

    public function todayTest()
    {
        
        return view("client.todaytests")
            ->with("medical", Medicals::orderBy("created_at", "DESC")->orderBy('created_at', 'DESC')->groupBy("trx_id")->where("created_at", Carbon::now()->toDateString())->get());
    }
    public function completeTransaction()
    {
        
        if(Debts::where("trx_id", request()->get("trx_id"))->exists()){
             $balance = request()->get("amount") - Debts::where("trx_id", request()->get("trx_id"))->value("balance");
            if($balance == 0){
                Debts::where("trx_id", request()->get("trx_id"))->update(["completed" => "Y"]);
                return view("client.completereceipt")
                ->with("med_data", Medicals::where("trx_id", request()->get("trx_id"))->get())
                ->with("trx_id",request()->get("trx_id") )
                ->with("pat", Patients::where("pat_id",request()->get("pat_id"))->get());

            }else{
                return redirect()->back()->with("msg", "Balance is not complete");
            }
        }else{
            return redirect()->back()->with("msg", "Transaction ID does not exists");

        }
    }
    public function completePayment($id)
    {
        return view("client.completepayment")->with("id", $id)->with("trx_id", request()->get("trx_id"));
    }
    public function tests()
    {
        return view('client.test')->with('medical', Medicals::orderBy('created_at', 'DESC')->groupBy("trx_id")->get());
    }
    public function checkCredentials()
    {
        $email =  request()->get("email");
        $password = request()->get("password");
        request()->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email is required',
                'password.required' => 'Password is required'
            ]
        );
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            StaffActivities::create(["staffs_id" => Auth::user()->id, "activity" => "logged in"]);
            User::where("id", Auth::user()->id)->update(["loggedin" => 1]);
            request()->session()->put('branch', request()->get("id"));
            return redirect()->to("staff/dashboard");
        } else
            return redirect()->back()->withInput()->with('errmsg', "Incorrect Credentials");
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

        return view("admin.newstaff");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(["password" => Hash::make("123456")]);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required'
        ], [
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'phone.required' => 'Phone is required',
            'email.required' => 'Email is required',
            'address.required' => 'Address is required',
        ]);
        if (User::where("email", $request->get("email"))->exists())
            return redirect()->back()->with("msg", "User Already Exist");

        $id = User::create($request->except("first_name", "last_name", "address"))->id;
        $request->request->add(['user_id' => $id]);
        Staff::create($request->except('password', 'email'));
        return redirect()->back()->with("msg", "Staff Created Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        $staff = Staff::all();
        return view("admin.staff")->with("staff", $staff);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff, $id)
    {
        return view("admin.editstaff")->with('staff', Staff::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff, $id)
    {
        Staff::where("user_id", $id)->update($request->except("_token", "email", "email"));
        return redirect()->back()->with("msg", "Staff Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff, $id)
    {
        User::where("id", $id)->delete();
        return redirect()->back()->with("msg", "Staff Deleted Successfully.");
    }
}
