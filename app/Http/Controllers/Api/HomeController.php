<?php

namespace App\Http\Controllers\Api;

use App\AD;
use App\ADPart;
use App\AffiliateAD;
use App\Banner;
use App\Category;
use App\DynamicSection;
use App\FlashEvent;
use App\Helpers\Utils;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DynamicSectionResource;
use App\Http\Resources\FlashEventResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\UnfinishedResource;
use App\LayoutSetting;
use App\News;
use App\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/home",
     * summary="Fetching data home",
     * description="Fetching data",
     * operationId="fetchingdata",
     * tags={"home"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="lang",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="string", example="000"),
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="object", example="")
     *        )
     *     )
     * )
     */
    public function index(Request $request){
        $user = $this->user;
        $today = Carbon::now();

        $banner = Cache::remember('__banner_section',3600, function (){
            return Banner::where('row_status','=','active')->get();
        });

        $category = Cache::remember('__categories_section',3600, function (){
            return Category::where('row_status','=','active')->get();
        });

        $unfinished=[];
        for($i=1;$i<=2;$i++){
            $adpart = ADPart::on(Utils::ad_part($i))
                ->where('status','=',101)
                ->where('uid','=',$user->uid)
                ->where('tm', '>=', date('Y-m-d', strtotime('-3 month')))
                ->take(10)
                ->pluck('adid')
                ->toArray();

            if(count($adpart) > 0){
                array_push( $unfinished,$adpart);
            }
        }
        $ad = [];
        if(count($unfinished)>0){
            $ad = AD::whereIn('adid',$unfinished[0])
                ->where('status','=','4010')
                ->where('tm_end', '>=', date('Y-m-d H:i'))
                ->get();;
        }
        

        $query_flash_event = FlashEvent::where('row_status','=','active')->with('detail');
        if($user->is_tester){
            $query_flash_event->where('is_tester','=',true);
        }else{
            $query_flash_event->where('is_tester','=',false);
        }
        $arr_flash_event = $query_flash_event->get();
        $arr_flash = [];
        foreach ($arr_flash_event as $item){

            if($item->ut_by_register_date){
                $register = Carbon::parse(date_format(date_create($user->register),"Y-m-d"));
                $from = Carbon::parse(date_format(date_create($item->registered_from),"Y-m-d"));
                $to = Carbon::parse(date_format(date_create($item->registered_to),"Y-m-d"));
                if(!($register->gte($from) && $register->lte($to))){
                   continue;
                }
            }elseif ($item->ut_by_point_count){
                $point = $user->total_earn - $user->total_use;
                if(!($point >= $item->target_point_from && $point <= $item->target_point_to)){
                    continue;
                }
            }

            if($item->event_period == 'weekly'){
                $date = date('Y-m-d');
                $d = new \DateTime($date);
                $day_name = $d->format('l');
                if($day_name == $item->day_name){
                    if(!($today->gte($item->event_start) && $today->lte($item->event_end))){
                        continue;
                    }
                }else{
                    continue;
                }
            }else{
                if(!($today->gte($item->event_start) && $today->lte($item->event_end))){
                    continue;
                }
            }

            array_push($arr_flash, $item);
        }

        $arr_dynamic_section = Cache::rememberForever('__dynamic_section',function (){
            $dynamic_section =[];
            $arr_dynamic_section = DynamicSection::where('row_status','=','active')->get();
            foreach ($arr_dynamic_section as $item){
                if($item->target == 'snapcash'){
                    $snapcash = AffiliateAD::where('af_id','=',$item->snapcash_id)
                        ->where('status','=','4010')
                        ->where('isactive','=','1')
                        ->where('tm_end', '>=', date('Y-m-d H:i'))
                        ->first();
                    if(!$snapcash){
                        continue;
                    }
                    $item->target = 'deeplink';
                    $item->deeplink = 'cashtree://snapcash?code='.$snapcash->af_id;
                }elseif ($item->target == 'campaign'){
                    $objAd = AD::where('adid','=',$item->adid)
                        ->where('status','=','4010')
                        ->where('tm_end', '>=', date('Y-m-d H:i'))
                        ->first();;

                    if(!$objAd){
                        continue;
                    }

                    $item->target = 'deeplink';
                    $item->deeplink = 'cashtree://openad/'.$objAd->adid;
                }
                array_push($dynamic_section, $item);
            }

            return $dynamic_section;
        });

        $news = Cache::remember('__news_list_home1',3600, function (){
            return News::select('id','news_code','title','url_to_image','reward')
                ->withCount('news_read')
                ->orderBy('id','DESC')
                ->where('row_status','=','active')->take(10)->get();
        });

        $layout_settings = Cache::rememberForever('__layout_setting',function (){
            return LayoutSetting::orderBy('sequence','ASC')->get();
        });

        $background_color = Cache::rememberForever('__background_color',function (){
            $objColor = DB::connection('common')->table('settings')->where('setting_code','=', 'background_color')->first();
            if($objColor){
                return $objColor->setting_value_full;
            }
            return '';
        });

        $background_image = Cache::rememberForever('__background_image',function (){
            $objImage = DB::connection('common')->table('settings')->where('setting_code','=', 'background_image')->first();
            if($objImage){
                return $objImage->setting_value_full;
            }
            return '';
        });

        $config = [
            'background_color' => $background_color,
            'background_image' => $background_image,
            'lucky_chance' => !$user->phone_auth_tm
        ];

        $uid = $user->uid;
        $notification = Notification::leftJoin('notification_details','notification_details.notification_id','notifications.id')
            ->where(function ($query) use ($uid) {
                $query->where('uid', '=', $uid)
                    ->Where('notification_details.is_read','=', false);
            })
            ->orWhereNull('uid')
            ->where('notifications.created_at','>=', date('Y-m-d', strtotime('-1 month')))
            ->count();

        $response = ['banner' => BannerResource::collection($banner)];
        $dynamic_position = [];
        foreach ($layout_settings as $setting){
            if($setting->page_name == 'categories'){
                $section = [
                    'content_type' => 'categories',
                    'sequence' => $setting->sequence,
                    'data' => CategoryResource::collection($category)
                ];
                array_push($dynamic_position, $section);
            }
            elseif ($setting->page_name == 'flash_event'){
                $section = [
                    'content_type' => 'flash_event',
                    'sequence' => $setting->sequence,
                    'data' => FlashEventResource::collection($arr_flash)
                ];
                array_push($dynamic_position, $section);
            }
            elseif ($setting->page_name == 'unfinished'){
                $section = [
                    'content_type' => 'unfinished',
                    'sequence' => $setting->sequence,
                    'data' => UnfinishedResource::collection($ad)
                ];
                array_push($dynamic_position, $section);
            }
            elseif ($setting->page_name == 'dynamic'){
                $section = [
                    'content_type' => 'dynamic',
                    'sequence' => $setting->sequence,
                    'data' => DynamicSectionResource::collection($arr_dynamic_section)
                ];
                array_push($dynamic_position, $section);
            }
            elseif ($setting->page_name == 'news'){
                $section = [
                    'content_type' => 'news',
                    'sequence' => $setting->sequence,
                    'data' => NewsResource::collection($news)
                ];
                array_push($dynamic_position, $section);
            }
        }
        $response['dynamic_position'] = $dynamic_position;
        $response['config']= $config;
        $user->sex = $user->sex == '0' ? 'Male' : 'Female';
        $response['user'] = $user;
        $response['notification'] = ['count'=>$notification];

        return $this->successResponse($response);
    }

}
