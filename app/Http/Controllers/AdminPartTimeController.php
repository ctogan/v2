<?php

namespace App\Http\Controllers;

use App\Helpers\Upload;
use App\Helpers\Utils;
use App\JobApplicant;
use App\JobCompany;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\Cache;
use View;
use ImageOptimizer;

class AdminPartTimeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.parttime.index');
    }

    public function employee()
    {
        return view('admin.parttime.employee');
    }

    public function vacancy(Request $request)
    {
        $company = Cache::get_company();
        $category = Cache::get_category();
        $province = Cache::get_province();
        $city = Cache::get_all_city();

        $pageVars = [
            "company_filter" => $request->company_filter,
            "category_filter" => $request->category_filter,
            "province_filter" => $request->province_filter,
            "city_filter" => $request->city_filter,
            "company" => $company,
            "category" => $category,
            "province" => $province,
            "city" => $city,
        ];

        return View::make('admin.parttime.vacancy')->with($pageVars);
    }

    public function vacancy_add()
    {
        $company = Cache::get_company();
        $province = Cache::get_province();

        $pageVars = [
            "province" => $province,
            "company" =>$company
        ];

        return View::make('admin.parttime.vacancy_add')->with($pageVars);
    }

    public function vacancy_edit($id)
    {
        $vacancy = Vacancy::where('job_vacancy.id','=',$id)
            ->join('job_company','job_company.id','=','job_vacancy.company_id')
            ->select('job_vacancy.*','job_company.company_name')
            ->first();

        $company = Cache::get_company();
        $province = Cache::get_province();
        $city = Cache::get_city($vacancy->province_id);

        $pageVars = [
            "province" => $province,
            "city"=>$city,
            "vacancy"=>$vacancy,
            "company" =>$company
        ];

        return View::make('admin.parttime.vacancy_edit')->with($pageVars);
    }

    public function vacancy_submit(Request $request){
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'province_id'=> 'required',
            'city_id'=> 'required',
            'position_name'=> 'required',
            'description'=> 'required',
            'qualifications' => 'required',
            'education' => 'required',
            'experienced' => 'required',
            'salary' => 'required',
            'send_to_email' => 'required',
            'active_until' => 'required',
            'vacancy_status' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data_insert = array(
            'row_status' => "active",
            'company_id' => $request->company_id,
            'province_id'=> $request->province_id,
            'city_id'=> $request->city_id,
            'position_name' => $request->position_name,
            'description' => $request->description,
            'qualifications' => $request->qualifications,
            'education' => $request->education,
            'experienced' => $request->experienced,
            'salary' => $request->salary,
            'salary_type' => $request->salary_type,
            'allowance' => $request->allowance,
            'send_to_email' => $request->send_to_email,
            'send_to_wa' => $request->send_to_wa,
            'active_until' => $request->active_until,
            'vacancy_status' => $request->vacancy_status,
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        Vacancy::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function vacancy_update(Request $request){
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'province_id'=> 'required',
            'city_id'=> 'required',
            'position_name'=> 'required',
            'description'=> 'required',
            'qualifications' => 'required',
            'education' => 'required',
            'experienced' => 'required',
            'salary' => 'required',
            'send_to_email' => 'required',
            'active_until' => 'required',
            'vacancy_status' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $vacancy = Vacancy::where('id','=',$request->id)->first();

        if(!$vacancy){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $vacancy->company_id = $request->company_id;
        $vacancy->province_id = $request->province_id;
        $vacancy->province_id = $request->province_id;
        $vacancy->city_id = $request->city_id;
        $vacancy->description = $request->description;
        $vacancy->qualifications = $request->qualifications;
        $vacancy->education = $request->education;
        $vacancy->experienced = $request->experienced;
        $vacancy->salary = $request->salary;
        $vacancy->salary_type = $request->salary_type;
        $vacancy->allowance = $request->allowance;
        $vacancy->send_to_email = $request->send_to_email;
        $vacancy->send_to_wa = $request->send_to_wa;
        $vacancy->active_until = $request->active_until;
        $vacancy->vacancy_status = $request->vacancy_status;
        $vacancy->updated_by = Auth::user()->name;
        $vacancy->updated_at = date('yy-m-d h:m:s');

        if(!$vacancy->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function vacancy_delete(Request $request){

        $vacancy = Vacancy::where('id','=',$request->id)->first();

        if(!$vacancy){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $vacancy->row_status = "deleted";
        $vacancy->updated_by = Auth::user()->name;
        $vacancy->updated_at = date('yy-m-d h:m:s');

        if(!$vacancy->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function vacancy_approve(Request $request){
        $vacancy = Vacancy::where('id','=',$request->id)->first();

        if(!$vacancy){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $vacancy->vacancy_status = "published";
        $vacancy->approved_by = Auth::user()->name;
        $vacancy->approved_at = date('yy-m-d h:m:s');
        $vacancy->updated_by = Auth::user()->name;
        $vacancy->updated_at = date('yy-m-d h:m:s');

        if(!$vacancy->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function vacancy_reject(Request $request){
        $validation = Validator::make($request->all(), [
            'rejection_reason' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $vacancy = Vacancy::where('id','=',$request->id)->first();

        if(!$vacancy){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $vacancy->vacancy_status = "rejected";
        $vacancy->rejection_reason =$request->rejection_reason;
        $vacancy->rejected_by = Auth::user()->name;
        $vacancy->rejected_at = date('yy-m-d h:m:s');
        $vacancy->updated_by = Auth::user()->name;
        $vacancy->updated_at = date('yy-m-d h:m:s');

        if(!$vacancy->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function company()
    {
        return view('admin.parttime.company');
    }

    public function company_add()
    {
        $province = Cache::get_province();
        $category = Cache::get_category();

        $pageVars = [
            "province" => $province,
            "category" =>$category
        ];
        return View::make('admin.parttime.company_add')->with($pageVars);
    }

    public function company_edit($id)
    {
        $company = JobCompany::where('id','=',$id)->first();
        $province = Cache::get_province();
        $category = Cache::get_category();
        $city = Cache::get_city($company->province_id);

        $pageVars = [
            "province" => $province,
            "city"=>$city,
            "category" =>$category,
            "company"=>$company
        ];

        return View::make('admin.parttime.company_edit')->with($pageVars);
    }

    public function company_submit(Request $request){
        $validation = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_logo' => 'required|image|mimes:jpg,jpeg,png',
            'category'=> 'required',
            'address'=> 'required',
            'province_id'=> 'required',
            'city_id'=> 'required',
            'description' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data_insert = array(
            'row_status' => "active",
            'company_name' => $request->company_name,
            'company_logo' => Utils::upload($request,'company_logo','minijob/company/logo/'),
            'category'=> $request->category,
            'address'=> $request->address,
            'province_id'=> $request->province_id,
            'city_id'=> $request->city_id,
            'description' => $request->description,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'website' => $request->website,
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        JobCompany::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function company_update(Request $request){
        $validation = Validator::make($request->all(), [
            'company_name' => 'required',
            'category'=> 'required',
            'address'=> 'required',
            'province_id'=> 'required',
            'city_id'=> 'required',
            'description' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $company = JobCompany::where('id','=',$request->id)->first();

        if(!$company){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $company->company_name = $request->company_name;
        $company->category = $request->category;
        $company->address = $request->address;
        $company->province_id = $request->province_id;
        $company->city_id = $request->city_id;
        $company->employee_size_id = $request->employee_size_id;
        $company->description = $request->description;
        $company->email = $request->email;
        $company->phone_number = $request->phone_number;
        $company->website = $request->website;
        $company->updated_by = Auth::user()->name;
        $company->updated_at = date('yy-m-d h:m:s');

        if ($request->hasFile('company_logo')) {
            $validation = Validator::make($request->all(), [
                'company_logo' => 'required|image|mimes:jpg,jpeg,png'
            ]);

            if ($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $company->company_logo = Utils::upload($request,'company_logo','minijob/company/logo/');
        }

        if(!$company->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function company_delete(Request $request){

        $company = JobCompany::where('id','=',$request->id)->first();

        if(!$company){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $company->row_status = "deleted";
        $company->updated_by = Auth::user()->name;
        $company->updated_at = date('yy-m-d h:m:s');

        if(!$company->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function company_paging(Request $request){
        return DataTables::of(JobCompany::where('job_company.row_status','=',"active")
            ->join('province','province.id','job_company.province_id')
            ->join('city','city.id','job_company.city_id')
            ->join('job_company_category','job_company_category.id','job_company.category')
            ->select(DB::raw("(select count(*) from job_vacancy where company_id=job_company.id and row_status='active') as count"),"job_company.*","job_company_category.category_name","province.province_name","city.city_name")
            ->get())->addIndexColumn()->make(true);
    }

    public function vacancy_paging(Request $request){
        $query = Vacancy::where('job_vacancy.row_status','=',"active")
            ->join('job_company','job_company.id','job_vacancy.company_id')
            ->join('province','province.id','job_vacancy.province_id')
            ->join('city','city.id','job_vacancy.city_id')
            ->join('job_company_category','job_company_category.id','job_company.category')
            ->select("job_vacancy.*","job_company.company_name","job_company_category.category_name","province.province_name","city.city_name");

        if($request->company_filter){
            $query->where('job_vacancy.company_id','=',$request->company_filter);
        }

        if($request->category_filter){
            $query->where('job_company.category','=',$request->category_filter);
        }

        if($request->province_filter){
            $query->where('job_vacancy.province_id','=',$request->province_filter);
        }

        if($request->city_filter){
            $query->where('job_vacancy.city_id','=',$request->city_filter);
        }

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }

    public function applicant(Request $request){
        return view('admin.parttime.applicant');
    }

    public function applicant_paging(Request $request){
        return DataTables::of(JobApplicant::join('job_vacancy','job_vacancy.id','job_applicant.vacancy_id')
            ->join('job_company','job_company.id','job_vacancy.company_id')
            ->join('province','province.id','job_vacancy.province_id')
            ->join('city','city.id','job_vacancy.city_id')
            ->join('job_company_category','job_company_category.id','job_company.category')
            ->select('job_applicant.applicant_name','job_applicant.uid','job_applicant.apply_date','job_vacancy.id as vacancy_id','job_vacancy.position_name','job_company.company_name','job_company.id as company_id','province.province_name', 'city.city_name','job_company_category.category_name')
            ->get())->addIndexColumn()->make(true);
    }

}
