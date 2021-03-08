<?php

namespace App\Http\Controllers;

use App\Helpers\Cache;
use App\Helpers\CtreeCache;
use App\Helpers\User;
use App\Helpers\Utils;
use App\JobApplicant;
use App\JobApplicantReported;
use App\JobBookmark;
use App\JobCompany;
use App\JobEmployeeSize;
use App\JobFAQ;
use App\JobFilter;
use App\JobNotification;
use App\JobUserPreferenceCategories;
use App\JobUserPreferenceEducation;
use App\JobUserPreferenceProvince;
use App\JobVacancyReported;
use App\Province;
use App\UserJobExperiences;
use App\UserName;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\UserAddress;
use Illuminate\Support\Facades\View;
use App\CompanyCategory;
use function GuzzleHttp\json_decode;
use App\JobCandidateBookmark;
use App\JobCandidateReported;
use App\City;
use App\JobEducation;
use PDF;

class ApiPartTimeController extends ApiController
{

    public function index(Request $request){
        $config = [
            "text"=>trans('part_time'),
            "is_registered_employee"=>CtreeCache::is_regis_part_time($this->user->uid),
            "is_registered_company"=>CtreeCache::is_regis_part_time($this->user->uid),
        ];

        $response = [
            'config' => $config,
            'user' => $this->user
        ];

        return $this->successResponse($response);
    }

    public function candidate(Request $request){
        $job_filter = JobFilter::where('uid','=',$this->user->uid)->first();

        $config = [
            "text"=>trans('part_time'),
            "filter"=>$job_filter ? json_decode($job_filter->filter) : null,
            "filter_text"=>$job_filter ? json_decode($job_filter->filter_text) : null,
            "is_profile_complete" => $this->user->job_part_time_complete == 1 ? true : false
        ];

        $response = [
            'config' => $config,
            'user'=> $this->user,
            'bookmark' => $this->get_job_bookmark(3),
            'recommendation' => $this->get_job_search_and_recomendations($request),
        ];
        return $this->successResponse($response);
    }

    public function candidate_profile(Request $request){
        $user = $this->user;

        $field_address= UserName::select('address')->where('uid' , $user->uid)->first();

        $response = [
            'user'=> $user,
            'address'=> $field_address->address,
            'religion' => Utils::RELIGION_MASTER,
            'company_category' => CtreeCache::get_category(false),
            'education' => Utils::EDUCATION_MASTER,
            'experience' => UserJobExperiences::where('uid' , $user->uid)->where('status' , '0')->get()
        ];

        return $this->successResponse($response);
    }

    public function get_bookmark(Request $request){
        $response = $this->get_job_bookmark(20);
        return $this->successResponse($response);
    }

    public function get_job_bookmark($limit = 3){
        $result = JobBookmark::where('job_bookmark.uid','=',$this->user->uid)
            ->where('job_vacancy.vacancy_status','=','published')
            ->where('job_vacancy.active_until','>=',date('Y-m-d 00:00:01'))
            ->join('job_vacancy','job_vacancy.id','job_bookmark.vacancy_id')
            ->select('job_bookmark.vacancy_id')
            ->where('job_bookmark.row_status','!=','deleted')
            ->orderBy('job_bookmark.created_at','desc')
            ->limit($limit)
            ->get();
        $data = [];
        if($result){
            foreach ($result as $item){
                $is_submited= JobApplicant::where('uid','=',$this->user->uid)
                    ->where('vacancy_id','=',$item->id)->first() ? true : false;

                $data[] = CtreeCache::get_job_vacancy_by_id($item->vacancy_id, $is_submited);
            }
        }
        return count($data) > 0 ? $data : [];
    }

    public function candidate_history(Request $request){
        $vacancy = JobApplicant::where('job_applicant.uid','=',$this->user->uid)
            ->select('job_applicant.vacancy_id')
            ->get();
        $data = array();
        if($vacancy){
            foreach ($vacancy as $item){
                if($item->vacancy_id != '')
                {
                    $is_submited= JobApplicant::where('uid','=',$this->user->uid)
                        ->where('vacancy_id','=',$item->vacancy_id)->first() ? true : false;
                    $data[] = CtreeCache::get_job_vacancy_by_id($item->vacancy_id,$is_submited);
                }

            }
        }
        $result = count($data) > 0 ? $data : [];

        $response =[
            'history' => $result
        ];

        return $this->successResponse($response,static::TRANSACTION_SUCCESS, 200);
    }

    public function company(Request $request){
        $company = JobCompany::where('uid','=',$this->user->uid)->first();
        $config = [
            "text"=>trans('part_time')
        ];

        $response = [
            'waiting_confirm_vacancy' => $company,
            'active_vacancy' => $company,
            'rejected_vacancy' => $company,
            'config' => $config
        ];

        return $this->successResponse($response);
    }

    public function company_history(Request $request){
        $company = JobCompany::where('uid','=',$this->user->uid)->first();
        $vacancy = [];
        if($company){
            $vacancy = Vacancy::where('company_id' , $company->id)->get();
        }

        $response = [
            'history' => $vacancy,
        ];

        return $this->successResponse($response);
    }

    public function company_detail(Request $request){

        $company = JobCompany::where('job_company.id','=',$request->id)
            ->join('job_company_category','job_company_category.id','job_company.category')
            ->join('province','province.id','job_company.province_id')
            ->join('city','city.id','job_company.city_id')
            ->join('job_employee_size','job_employee_size.id','job_company.employee_size_id')
            ->first();

        //$waiting_confirm = Vacancy::where('company_id' , $request->id)->where('status','waiting_confirm')->get();

        $config = [
            "text"=>trans('part_time')
        ];

      $response = [
            'config' => $config,
            'waiting_confirm_vacancy' =>Vacancy::where('company_id' , $request->id)->where('vacancy_status','waiting_confirm')->get(),
            'reported_vacancy' => Vacancy::where('company_id' , $request->id)->where('vacancy_status','failed')->get(),
            'active_vacancy' => Vacancy::where('company_id' , $request->id)->where('vacancy_status','published')->get(),
            "company"=>$company,
        ];

        return $this->successResponse($response);
    }

    public function my_company(Request $request){
        $config = [
            "text"=>trans('part_time')
        ];
        $response = [
            'waiting_confirm_vacancy' => Vacancy::where('company_id' , $this->user->uid)->where('vacancy_status','waiting_confirm')->get(),
            'active_vacancy' => Vacancy::where('company_id' , $this->user->uid)->where('vacancy_status','published')->get(),
            'rejected_vacancy' => Vacancy::where('company_id' , $this->user->uid)->where('vacancy_status','failed')->get(),
            'config' => $config
        ];

        return $this->successResponse($response);
    }

    public function my_company_detail(Request $request){
        $company = JobCompany::select('job_company.*','job_employee_size.employee_size','province.province_name','city.city_name','job_company_category.category_name')
            ->where('uid','=',$this->user->uid)
            ->join('job_employee_size','job_employee_size.id','job_company.employee_size_id')
            ->leftJoin('job_company_category' ,'job_company.category' ,'job_company_category.id')
            ->leftJoin('province' ,'job_company.province_id' ,'province.id')
            ->leftJoin('city' ,'job_company.city_id' ,'city.id')
            ->first();

        $active_vacancy = Vacancy::select('job_vacancy.*','job_company.company_name','province.province_name','city.city_name','job_company_category.category_name')
            ->where('company_id' , $company->id)
            ->leftJoin('job_company', 'job_company.id' ,'job_vacancy.company_id')
            ->leftJoin('province', 'province.id' ,'job_vacancy.province_id')
            ->leftJoin('city', 'city.id' ,'job_vacancy.city_id')
            ->leftJoin('job_company_category','job_company_category.id','job_company.category')
            ->where('vacancy_status','published')
            ->get();

        $pending_vacancy = Vacancy::select('job_vacancy.*','job_company.company_name','province.province_name','city.city_name','job_company_category.category_name')
            ->where('company_id' , $company->id)
            ->leftJoin('job_company', 'job_company.id' ,'job_vacancy.company_id')
            ->leftJoin('province', 'province.id' ,'job_vacancy.province_id')
            ->leftJoin('city', 'city.id' ,'job_vacancy.city_id')
            ->leftJoin('job_company_category','job_company_category.id','job_company.category')
            ->where('vacancy_status','waiting_confirm')
            ->get();

        $reported_vacancy = Vacancy::select('job_vacancy.*','job_company.company_name','province.province_name','city.city_name','job_company_category.category_name')
            ->where('company_id' , $company->id)
            ->leftJoin('job_company', 'job_company.id' ,'job_vacancy.company_id')
            ->leftJoin('province', 'province.id' ,'job_vacancy.province_id')
            ->leftJoin('city', 'city.id' ,'job_vacancy.city_id')
            ->leftJoin('job_company_category','job_company_category.id','job_company.category')
            ->where('vacancy_status','rejected')->get();

        $config = [
            "text"=>trans('part_time')
        ];

        $response = [
            'config' => $config,
            'waiting_confirm_vacancy' => $pending_vacancy,
            'reported_vacancy' => $reported_vacancy,
            'active_vacancy' => $active_vacancy,
            "company"=>$company,
        ];

        return $this->successResponse($response);
    }

    public function search(Request $request){
        $response = $this->get_job_search_and_recomendations($request);
        return $this->successResponse($response);
    }

    public function get_job_search_and_recomendations($request){
        $query = Vacancy::where("job_vacancy.row_status","=","active")
            ->where('job_vacancy.vacancy_status','=','published')
            ->where('job_vacancy.active_until','>=',date('Y-m-d 00:00:01'))
            ->join('job_company','job_company.id','=','job_vacancy.company_id')
            ->select('job_vacancy.id');

        if($request->search){
            $query->where('position_name','ilike','%'.$request->search.'%')
                ->orWhere('company_name','ilike','%'.$request->search.'%');
        }
        $job_filter = JobFilter::where('uid','=',$this->user->uid)->first();
        if($job_filter){
            if($job_filter->filter){
                $filter = json_decode($job_filter->filter);
                if($filter->province_id != "all"){
                    $query->whereIn('job_vacancy.province_id',explode(",",$filter->province_id));
                }
                if($filter->city_id != "all"){
                    $query->whereIn('job_vacancy.city_id',explode(",",$filter->city_id));
                }
                if($filter->company_type != "all"){
                    $query->whereIn('job_company.category',explode(",",$filter->company_type));
                }
                if($filter->education_level != "all"){
                    $query->whereIn('job_vacancy.education',explode(",",$filter->education_level));
                }
                if($filter->salary_to > 0){
                    $query->whereBetween('job_vacancy.salary', [$filter->salary_from,$filter->salary_to]);
                }
            }
        }
        $data = [];
        $query = $query->orderBy('job_vacancy.created_at','desc')->paginate();
        if($query){
            foreach ($query as $item){
                $is_submited= JobApplicant::where('uid','=',$this->user->uid)
                    ->where('vacancy_id','=',$item->id)->first() ? true : false;
                $data[] = CtreeCache::get_job_vacancy_by_id($item->id,$is_submited);
            }
        }
        return count($data) > 0 ? $data : [];
    }

    public function get_job_search_and_recomendation($request){
        $query = Vacancy::where("job_vacancy.row_status","=","active")
            ->join('job_company','job_company.id','=','job_vacancy.company_id')
            ->select('job_vacancy.*','job_company.company_name','job_company.company_logos');

        if($request->search){
            $query->where('position_name','ilike','%'.$request->search.'%')
                ->orWhere('company_name','ilike','%'.$request->search.'%');
        }

        $job_filter = JobFilter::where('uid','=',$this->user->uid)->first();

        if($job_filter){
            if($job_filter->filter){
                $filter = json_decode($job_filter->filter);
                if($filter->province_id != "all"){
                    $query->whereIn('job_vacancy.province_id',explode(",",$filter->province_id));
                }
                if($filter->city_id != "all"){
                    $query->whereIn('job_vacancy.city_id',explode(",",$filter->city_id));
                }
                if($filter->company_type != "all"){
                    $query->whereIn('job_company.category',explode(",",$filter->company_type));
                }
                if($filter->education_level != "all"){
                    $query->whereIn('job_vacancy.education',explode(",",$filter->education_level));
                }
                if($filter->salary_to > 0){
                    $query->whereBetween('job_vacancy.salary', [$filter->salary_from,$filter->salary_to]);
                }
            }
        }

        $response = [
            "result" => $query->paginate($request->per_page)
        ];
        return $response;
    }

    public function vacancy_detail(Request $request){
        $vacancy = Vacancy::where("job_vacancy.id",'=',$request->id)
            ->first();

        if(!$vacancy){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $config = [
            "text"=>trans('part_time')
        ];

        $data = [];
        if($vacancy){
            $is_submited= JobApplicant::where('uid','=',$this->user->uid)
                ->where('vacancy_id','=',$vacancy->id)->first() ? true : false;
            $data[] = CtreeCache::get_job_vacancy_by_id($vacancy->id,$is_submited);
        }

        $response = [
            "option" =>$config,
            "vacancy" => $data,
        ];

        return $this->successResponse($response);
    }

    public function apply_vacancy(Request $request){
        $validation = Validator::make($request->all(), [
            'vacancy_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        if(!$this->check_profile($this->user)){
            return $this->errorResponse(static::PROFILE_UNCOMPLETE, static::PROFILE_UNCOMPLETE_CODE);
        }

        if(!$this->user->phone){
            return $this->errorResponse(static::PROFILE_UNCOMPLETE, static::PROFILE_UNCOMPLETE_CODE);
        }

        JobApplicant::insert(array(
            "uid"=>$this->user->uid,
            "applicant_name" => $this->user->name,
            "img"=> $this->user->img,
            "dob" => $this->user->dob,
            "sex" => $this->user->sex,
            "pob" => $this->user->pob,
            "phone" => $this->user->phone,
            "email" => $this->user->email,
            "weight" => $this->user->weight,
            "height" => $this->user->height,
            "religion" => Utils::RELIGION_MASTER[$this->user->religion]['name'],
            "last_education" => $this->user->last_education,
            "skills" => $this->user->skills,
            "hobby" => $this->user->hobby,
            "address" => $this->user->address,
            "vacancy_id" => $request->vacancy_id,
            "apply_date"=>  date('yy-m-d h:m:s')
        ));

        $vacancy = Vacancy::where('job_vacancy.id','=',$request->vacancy_id)
            ->join('job_company','job_company.id','job_vacancy.company_id')
            ->first();

        JobNotification::insert(
            array(
                [
                    'uid' => $vacancy->uid,
                    'title'=> "Tambahan Pelamar",
                    'message'=> $vacancy->position_name,
                    "type"=>"employer",
                    "is_read" => false,
                    'deeplink' => 'jumlah_pelamar?vacancy='.$request->vacancy_id,
                ],
                [
                    'uid' => $this->user->uid,
                    'title'=> "Lamaran Terkirim",
                    'message'=> $vacancy->position_name,
                    "type"=>"candidate",
                    "is_read" => false,
                    'deeplink' => 'history_lamaran?uid='.$this->user->uid,
                ]
            )
        );

        CtreeCache::clear_candidate_vacancy($request->vacancy_id);

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function view_location(Request $request){

        $city = CtreeCache::get_all_province_and_city(true);
        $data = [];
        foreach ($city as $value){
           $data[$value['province_name']][] = $value;
        }
        print_r($data);exit();
        $pageVars = [
            "company" => $city,
        ];
        return View::make('admin.parttime.vacancy')->with($pageVars);
    }

    public function submit_filter(Request $request){
        $filter = array(
            "province_id" => $request->province_id ? $request->province_id : 'all',
            "city_id" => $request->city_id ? $request->city_id : 'all',
            "company_type" => $request->company_type ? $request->company_type : 'all',
            "education_level" => $request->education_level ? $request->education_level : 'all',
            "salary_from" => $request->salary_from ? $request->salary_from : '0',
            "salary_to" => $request->salary_to ? $request->salary_to : '0',
        );

        $filter_text = array(
            "province_id" => $request->province_text ? $request->province_text : 'all',
            "city_id" => $request->city_text ? $request->city_text : 'all',
            "company_type" => $request->company_type_text ? $request->company_type_text : 'all',
            "education_level" => $request->education_level_text ? $request->education_level_text : 'all',
            "salary_from" => $request->salary_from ? $request->salary_from : '0',
            "salary_to" => $request->salary_to ? $request->salary_to : '0',
        );

        $job_filter = JobFilter::where('uid','=',$this->user->uid)->first();

        if($job_filter){
            $job_filter->filter = json_encode($filter);
            $job_filter->filter_text = json_encode($filter_text);
            $job_filter->save();
        }else{
            JobFilter::insert(
                array(
                    "uid"=>$request->uid,
                    "filter"=>json_encode($filter),
                    "filter_text"=>json_encode($filter_text)
                )
            );
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_candidate_profile(Request $request){

        $validation = Validator::make($request->all(), [
            'pob' => 'required',
            'dob' => 'required',
            'sex' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'religion' => 'required',
            'education' => 'required',
            'skill' => 'required',
            'hobby' => 'required',
            'profile' => 'required',
            'school_name'=> 'required',
            'major' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserName::where('uid','=',$this->user->uid)->first();
        if($user){
            $user->uid =$this->user->uid;
            $user->name = $request->name;
            $user->dob = date('Y-m-d' , strtotime($request->dob));
            $user->sex =$request->sex;
            $user->pob = (int) $request->pob;
            $user->email =$request->email;
            $user->weight =$request->weight;
            $user->height =$request->height;
            $user->religion = $request->religion;
            $user->last_education = $request->education;
            $user->skills = $request->skill;
            $user->hobby = $request->hobby;
            $user->address = $request->address;
            $user->job_part_time_complete = true;
            $user->profile = $request->profile;
            $user->school_name = $request->school_name;
            $user->major = $request->major;
            $user->year_of_entry = $request->year_of_entry;
            $user->graduated_year = $request->graduated_year;
            $user->save();

        }else{
            UserName::insert(
                array(
                    "uid"=>$this->user->uid,
                    "name"=>$request->name,
                    "dob"=>date('Y-m-d' , strtotime($request->dob)),
                    "sex"=>$request->sex,
                    "pob"=> (int) $request->pob,
                    "address"=>$request->address,
                    "email"=>$request->email,
                    "weight" => $request->height,
                    "height" => $request->height,
                    "religion"=>$request->religion,
                    "last_education"=>$request->education,
                    "skills"=>$request->skill,
                    "hobby"=>$request->hobby,
                    "job_part_time_complete" => true,
                    "profile" => $request->profile,
                    "school_name" => $request->school_name,
                    "major" => $request->major,
                    "year_of_entry" => $request->year_of_entry,
                    "graduated_year" => $request->graduated_year
                )
            );
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_candidate_experiences(Request $request){
        $validation = Validator::make($request->all(), [
            'company_name' => 'required',
            'department' => 'required',
            'position' => 'required',
            'work_periode' => 'required',
            'work_description' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        UserJobExperiences::insert(
            array(
                'uid' => $this->user->uid,
                'company_name' => $request->company_name,
                'department' => $request->department,
                'position' => $request->position,
                'work_periode' => $request->work_periode,
                'work_description' => $request->work_description
            )
        );

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);

    }

    public function delete_candidate_experiences(Request $request){
        $validation = Validator::make($request->all(), [
            'experience_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }
        $experience = UserJobExperiences::where('id' , $request->experience_id)->where('uid' , $this->user->uid)->first();
        if(!$experience){
            return $this->errorResponse(static::TRANSACTION_ERROR_NOT_FOUND,static::ERROR_DATA_SAVE_CODE);
        }
        $experience->status = 1;
        
        if(!$experience->save()){
            return $this->errorResponse(static::ERROR_DATA_SAVE,static::ERROR_DATA_SAVE_CODE);
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);

    }

    public function submit_vacancy_bookmark(Request $request){
        $validation = Validator::make($request->all(), [
            'vacancy_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        JobBookmark::insert(
            array(
                'row_status'=>'active',
                'uid' => $this->user->uid,
                'vacancy_id' => $request->vacancy_id,
                'created_at' => date("Y-m-d h:i:s")
            )
        );
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_candidate_bookmark(Request $request){
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'uid' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        JobCandidateBookmark::updateOrCreate(
            array(
                'uid' => $request->uid,
                'company_id' => $request->company_id,
            ),
            array(
                'row_status'=>'active',
                'uid' => $request->uid,
                'company_id' => $request->company_id,
                'created_at' => date("Y-m-d h:i:s")
            )
        );
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function delete_vacancy_bookmark(Request $request){
        $validation = Validator::make($request->all(), [
            'vacancy_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $bookmark = JobBookmark::where('uid','=',$this->user->uid)
            ->where('vacancy_id','=',$request->vacancy_id)
            ->first();
        $bookmark->row_status = 'deleted';
        if($bookmark){
            $bookmark->delete();
        }else{
            return $this->errorResponse(static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function delete_candidate_bookmark(Request $request){
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'uid' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $bookmark = JobCandidateBookmark::where('uid','=', $request->uid)
            ->where('vacancy_id','=',$request->company_id)
            ->first();
        $bookmark->row_status = 'deleted';
        if($bookmark){
            $bookmark->delete();
        }else{
            return $this->errorResponse(static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_report_vacancy(Request $request){
        $validation = Validator::make($request->all(), [
            'vacancy_id' => 'required',
            'reason_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
        }

        $reported=JobVacancyReported::where("uid","=",$request->uid)
            ->where("vacancy_id","=",$request->vacancy_id)
            ->where("reason_id","=",$request->reason_id)
            ->first();

        if(!$reported){
            JobVacancyReported::insert(
                array(
                    'uid' => $this->user->uid,
                    'vacancy_id' => $request->vacancy_id,
                    'reason_id' => $request->reason_id
                )
            );
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_report_candidate(Request $request){
        $validation = Validator::make($request->all(), [
            'uid' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
        }

        $reported = JobCandidateReported::updateOrCreate(
            array(
                'uid' => $request->uid,
                'reported_by' => $this->user->uid,
                'reason_id' => $request->reason_id
            ),
            array(
                'row_status'=>'active',
                'uid' => $request->uid,
                'reason_id' => $request->reason_id,
                'reported_by' => $this->user->uid,
                'created_at' => date("Y-m-d h:i:s")
            )
        );

        if(!$reported){
            return $this->errorResponse(static::ERROR_DATA_SAVE,static::CODE_ERROR_VALIDATION);
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_report_user(Request $request){
        $validation = Validator::make($request->all(), [
            'applicant_id' => 'required',
            'vacancy_id' => 'required',
            'reason_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $reported = JobApplicantReported::where("uid","=",$request->uid)
            ->where("vacancy_id","=",$request->vacancy_id)
            ->where("reason_id","=",$request->reason_id)
            ->first();

        if(!$reported){
            JobApplicantReported::insert(
                array(
                    'applicant_id' => $request->applicant_id,
                    'vacancy_id' => $request->vacancy_id,
                    'reason_id' => $request->reason_id
                )
            );
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_company_profile(Request $request){
        $validation = Validator::make($request->all(), [
            'company_name' => 'required',
           // 'company_logo' => 'required|image|mimes:jpg,jpeg,png',
            'category'=> 'required',
            'address'=> 'required',
            'province_id'=> 'required',
            'city_id'=> 'required',
            'description' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);

        if($validation->fails()) {
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
        }

        $company = JobCompany::where('uid','=',$this->user->uid)->first();

        if($company){
            $company->uid=$this->user->uid;
            $company->company_name = $request->company_name;
           // $company->company_logo = Utils::upload($request,'company_logo','minijob/company/logo/');
            $company->category = $request->category;
            $company->employee_size_id = $request->employee_size_id;
            $company->address = $request->address;
            $company->province_id = $request->province_id;
            $company->city_id = $request->city_id;
            $company->description = $request->description;
            $company->email = $request->email;
            $company->phone_number = $request->phone_number;
            $company->website = $request->website;
            $company->updated_by = $this->user->uid;
            $company->updated_at = date('yy-m-d h:m:s');

            $company->save();
        }else{
            $data_insert = array(
                'row_status' => "active",
                'uid'=>$this->user->uid,
                'company_name' => $request->company_name,
               // 'company_logo' => Utils::upload($request,'company_logo','minijob/company/logo/'),
                'category'=> $request->category,
                'employee_size_id' => $request->employee_size_id,
                'address'=> $request->address,
                'province_id'=> $request->province_id,
                'city_id'=> $request->city_id,
                'description' => $request->description,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'website' => $request->website,
                'created_by' => $this->user->uid,
                'created_at' => date('yy-m-d h:m:s'),
            );

            JobCompany::insert($data_insert);
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_company_profile_logo(Request $request){
        $validation = Validator::make($request->all(), [
           'company_logo' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        if($validation->fails()) {
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
        }

        $company = JobCompany::where('uid','=',$this->user->uid)->first();

        if($company){
            $company->uid=$this->user->uid;
            $company->company_logo = Utils::upload($request,'company_logo','minijob/company/logo/');
            $company->updated_by = $this->user->uid;
            $company->updated_at = date('yy-m-d h:m:s');
            $company->save();
        }else{
            $data_insert = array(
                'row_status' => "notactive",
                'uid'=>$this->user->uid,
                'company_logo' => Utils::upload($request,'company_logo','minijob/company/logo/'),
                'created_by' => $this->user->uid,
                'created_at' => date('yy-m-d h:m:s')
            );
            JobCompany::insert($data_insert);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function submit_vacancy(Request $request){
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
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
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
            'created_by' => $this->user->uid,
            'created_at' => date('yy-m-d h:m:s'),
        );

        Vacancy::insert($data_insert);

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function update_vacancy(Request $request){
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
            return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
        }

        $vacancy = Vacancy::where('id','=', $request->id)->first();

        if(!$vacancy){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
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
        $vacancy->updated_by =  $this->user->uid;
        $vacancy->updated_at = date('yy-m-d h:m:s');

        if(!$vacancy->save()){
            return $this->errorResponse(static::TRANSACTION_ERROR,static::CODE_ERROR_VALIDATION);
        }

        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function applicant_candidate(Request $request){
        $validation = Validator::make($request->all(), [
            'vacancy_id' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }
        $response =[
            'vacancy' => CtreeCache::get_job_vacancy_by_id($request->vacancy_id),
            'candidates' => CtreeCache::get_candidate_vacancy($request->vacancy_id),
            'company' => JobCompany::where('uid' , $this->user->uid)->first()
        ];
        return $this->successResponse($response, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }
    
    public static function check_profile($user){
        $profile_validator = [
            'pob' => 'required',
            'dob' => 'required',
            'sex' => 'required',
            'email' => 'required',
            'religion' => 'required',
            'last_education' => 'required',
            'skills' => 'required',
            'hobby' => 'required',
        ];
        foreach($profile_validator as $key=>$value){
            if($user->$key == '' ||  $user->$key == null) {
                return false; //Check again
            }
        }

        return true;
    }

    public function filter_location_province(Request $request){
        $province = CtreeCache::get_province();
        $pageVars = [
            "province" => $province,
        ];
        return View::make('webapp.filter_province')->with($pageVars);
    }

    public function form_employer(Request $request){
        $response =[
            'province' => Cache::get_province(),
            'company_category' => Cache::get_category(),
            'working_time' =>array(['name' => 'hourly'] , ['name' => 'daily'], ['name' => 'weekly'], ['name' => 'monthly'], ['name' => 'project']),
            'education' => Cache::get_education(),
            'company' => JobCompany::where('uid' , $this->user->uid)->first(),
            'employee_size' => Cache::get_employee_size()
        ];
        return $this->successResponse($response, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function insert_preference_province(Request $request){

        $province = $request->id_province;
        $provincies = explode('.',$province);
        foreach ($provincies as $val){

            $data_insert = array(
                'id_province'=> $val,
                'created_at' => date('Y-m-d H:i:s'),
                'uid'=>$request->uid
            );
            JobUserPreferenceProvince::insert($data_insert);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function insert_preference_education(Request $request){

        $education = $request->id_education;
        $educations = explode('.',$education);
        foreach ($educations as $val){

            $data_insert = array(
                'uid'=>$request->uid,
                'id_education'=> $val,
                'created_at' => date('Y-m-d H:i:s'),

            );
            JobUserPreferenceEducation::insert($data_insert);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function insert_preference_category(Request $request){

        $category = $request->id_category;
        $categories = explode('.',$category);
        foreach ($categories as $val){

            $data_insert = array(
                'uid'=>$request->uid,
                'id_category'=> $val,
                'created_at' => date('Y-m-d H:i:s'),

            );
            JobUserPreferenceCategories::insert($data_insert);
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function upload_image_profile(Request $request){
        $validation = Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpg,jpeg,png'
         ]);
         if($validation->fails()) {
             return $this->errorResponse($validation->messages(),static::CODE_ERROR_VALIDATION);
         }

         $user = UserName::where('uid','=',$this->user->uid)->first();

        if($user){
            $user->img = Utils::upload($request,'img','minijob/profile/image/');
            $user->save();
        }
        return $this->successResponse(null, static::TRANSACTION_SUCCESS, static::CODE_SUCCESS);
    }

    public function my_company_candidate_bookmark(Request $request){
        
        $company = JobCompany::where('uid','=',$this->user->uid)->first();
        if(!$company){
            return $this->errorResponse("Please Register As Company", static::PROFILE_UNCOMPLETE_CODE);
        }
        $candidate_bookmark = JobCandidateBookmark::select('uid')->where('company_id' , $company->id)->get();
        $candidate_list = [];
        if($candidate_bookmark){
            foreach($candidate_bookmark as $k){
                $obj_user = CtreeCache::user_cache($k->uid , true);
                $obj_user->religion_text = Utils::RELIGION_MASTER[$obj_user->religion]['name'];
                $obj_user->last_education_text = Cache::get_education_by_id($obj_user->last_education)->education;
                $candidate_list[] =$obj_user;
            }
        }
        $response = [
            'candidate_bookmark' => $candidate_list
        ];

        return $this->successResponse($response);
    }

    public function generate_cv(Request $request)
    {
        $experienced = UserJobExperiences::where('uid','=',$this->user->uid)->get();
        $data = [
            'user'=>$this->user,
            'experience'=>$experienced
        ];
        $pdf = PDF::loadView('cv.index', $data);

        return $pdf->download('cv.pdf');
    }

    public function faq(Request $request){
        $faq = JobFAQ::where('type','=',$request->type)
            ->where('row_status','=','active')
            ->get();

        $response = [
            'faq' => $faq
        ];

        return $this->successResponse($response);
    }
}
