<?php

use Illuminate\Support\Facades\Route;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return Hash::make("123456");

    date_default_timezone_set("Africa/Lagos");
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function () {

    Route::get("/backups", "AdminController@backup");
    Route::get('/backup', function () {

        /*
        Needed in SQL File:
    
        SET GLOBAL sql_mode = '';
        SET SESSION sql_mode = '';
        */
        $get_all_table_query = "SHOW TABLES";
        $result = DB::select(DB::raw($get_all_table_query));

        $tables = [
            'users',
            'migrations',
            'branches',
            'discount',
            'discounts',
            'failed_jobs',
            'logged_in_staff',
            'medicals',
            'migrations',
            'patients',
            'patient_referral',
            'referrals',
            'selected_test',
            'staff',
            'staff_activities',
            'tests',
            'current_month',
        ];

        $structure = '';
        $data = '';
        foreach ($tables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";

            $show_table_result = DB::select(DB::raw($show_table_query));

            foreach ($show_table_result as $show_table_row) {
                $show_table_row = (array)$show_table_row;
                $structure .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table;
            $records = DB::select(DB::raw($select_query));

            foreach ($records as $record) {
                $record = (array)$record;
                $table_column_array = array_keys($record);
                foreach ($table_column_array as $key => $name) {
                    $table_column_array[$key] = '`' . $table_column_array[$key] . '`';
                }

                $table_value_array = array_values($record);
                $data .= "\nINSERT INTO $table (";

                $data .= "" . implode(", ", $table_column_array) . ") VALUES \n";

                foreach ($table_value_array as $key => $record_column)
                    $table_value_array[$key] = addslashes($record_column);

                $data .= "('" . wordwrap(implode("','", $table_value_array), 400, "\n", TRUE) . "');\n";
            }
        }
        $file_name = 'C:\\Users\\NICE DIAGNOSTIC LAB\\Desktop\\ndc_database_backup_on_'  . date('y_m_d') . '.sql';
        $file_handle = fopen($file_name, 'w + ');

        $output = $structure . $data;
        fwrite($file_handle, $output);
        fclose($file_handle);
        return redirect()->back()->with("msg", "Backup Completed")->withInput();
    });

    Route::post("login", "AdminController@login");
    Route::post("calculate-referral", "AdminController@calculateReferral");
    Route::post("calculate-department", "AdminController@calculateDepartment");
    Route::get("department/view/{i}", "AdminController@viewdepartment");
    Route::get("debts", "AdminController@debts");
    Route::get("referral/view/{i}", "AdminController@showReferral");
    Route::get("complete-pay", "AdminController@completePay");
    Route::get("receipt/{i}", "AdminController@receipt");
    Route::get("new-referral", "ReferralsController@create");
    Route::get("test/view/{i}", "AdminController@showTest");
    Route::get("referral/edit/{i}", "ReferralsController@edit");
    Route::post("referral/edit/{i}", "ReferralsController@update");
    Route::get("referral/delete/{i}", "ReferralsController@destroy");
    Route::get("department", "ReferralsController@department");
    Route::get("referrals", "ReferralsController@index");
    Route::post("new-referral", "ReferralsController@store");
    Route::get("dashboard", "AdminController@dashboard");
    Route::get("department/delete/{i}", "AdminController@deleteDepartment");
    Route::get("new-department", "AdminController@newdepartment");
    Route::post("departments", "AdminController@createdepartment");
    Route::get("create-new-test", "AdminController@createNewTest");
    Route::get("tests", "TestController@show");
    Route::get("patients", "AdminController@patients");
    Route::get("staff", "StaffController@show");
    Route::get("staff/create", "StaffController@create");
    Route::get("staff/edit/{i}", "StaffController@edit");
    Route::post("staff/edit/{i}", "StaffController@update");
    Route::get("test/delete/{i}", "TestController@destroy");
    Route::get("search", "SearchController@adminSearch");
    Route::get("staff/delete/{i}", "StaffController@destroy");
    Route::get("test/edit/{i}", "TestController@edit");
    Route::post("test/edit/{i}", "TestController@update");
    Route::post("create-new-test", "TestController@create");
    Route::post("create-new-staff", "StaffController@store");
    Route::get("password/change", "AdminController@changePassword");
    Route::post("password/change", "StaffController@updatePassword");
    Route::get("patients/test/edit/{i}", "PatientsController@edit");
    Route::get("patients/edit/{i}", "PatientsController@editPatient");
    Route::get("today-patient", "AdminController@todayPatient");
    Route::get("overal-patient", "AdminController@overallPatient");
    Route::get("overall-test", "AdminController@overallTest");
    Route::get("today-test", "AdminController@todayTest");
    Route::get("loggedin-staff", "AdminController@loggedInStaff");
    Route::get("staff-activity", "AdminController@staffActivity");
    Route::get("branches", "BranchesController@index");
    Route::get("new-branch", "BranchesController@show");
    Route::get("branch/edit/{i}", "BranchesController@edit");
    Route::get("branch/delete/{i}", "BranchesController@destroy");
    Route::post("branch/edit/{i}", "BranchesController@update");
    Route::post("new-branch", "BranchesController@store");
    Route::post("patients/edit/{i}", "PatientsController@updatePatient");
});
Route::group(['prefix' => 'staff'], function () {
    Route::get("test/view/{i}", "StaffController@showTest");

    Route::get("login", "StaffController@login");
    Route::post("calculate-staff-referral", "AdminController@calculateStaffReferral");
    Route::get("dr-referrals/{i}", "AdminController@drReferral");
    Route::get("debts", "StaffController@debts");
    Route::get("tests", "StaffController@tests");
    Route::get("complete-payment/{i}", "StaffController@completePayment");
    Route::post("complete-payment", "StaffController@completeTransaction");
    Route::get("test/all", "StaffController@allTest");
    Route::get("test/today", "StaffController@todayTest");
    Route::get("dashboard", "StaffController@dashboard");
    Route::get("patient/test", "PatientsController@show");
    Route::get("patient/search", "SearchController@search");
    Route::get("patient/create", "PatientsController@create");
    Route::patch("patient/edit/{i}", "PatientsController@update");
    Route::post("login", "StaffController@checkCredentials");
    Route::post("patient/store", "PatientsController@store");
    Route::get("password/change", "StaffController@changePassword");
    Route::post("password/change", "StaffController@updatePassword");
});
Route::get("staff/patient/api/get-test-price/{i}", "PatientsController@getPrice");
Route::get("admin/patients/edit/api/get-test-price/{i}", "PatientsController@getEdtPrice");
Route::get("staff/patient/api/test/search", "SearchController@searchItem");
Route::get("admin/api/test/search/", "SearchController@searchItem");
Route::get("staff/patient/api/get-info/{i}", "SearchController@getInfo");
