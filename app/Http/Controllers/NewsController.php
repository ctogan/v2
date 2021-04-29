<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\News;
use App\NewsCategory;
use App\NewsCategoryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.news.index');
    }

    public function add(Request $request){
        $categories = DB::connection('news')->table('news_category')->get();
        $data = [
            'categories' => $categories
        ];
        return view('admin.news.new', $data);
    }

    public function edit(Request $request){
        $news = News::find($request->id);
        $news_id = $news->id;
        $categories = NewsCategory::
            withCount(['news_category_detail'=>function($q) use($news_id){
                return $q->where('id_news','=', $news_id);
            }])->get();

        $data = [
            'categories' => $categories,
            'news' => $news
        ];
        return view('admin.news.edit', $data);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'url_to_image' => 'required|image|mimes:jpg,jpeg,png,webapp',
            'category' => 'required',
            'body_content' => 'required',
            'publish_for' => 'required',
            'availability' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $news_insert = array(
            "row_status" => 'active',
            "id_news"=>0,
            "featured_media_id"=>0,
            "source_name"=> "Cashtree App",
            "author"=> $request->author,
            "reward"=> $request->reward,
            "title"=>$request->title,
            "description"=>"",
            "url"=>"",
            "url_to_image"=>Utils::upload($request,'url_to_image','news/'),
            "content"=>str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars(Utils::mark_down($request->body_content))),
            "published_at"=> date('Y-m-d H:m:s'),
            "created_at" => date('Y-m-d H:m:s'),
            "created_by" => Auth::user()->name,
            "is_tester" => $request->availability
        );

        $news = News::create($news_insert);

        if($request->category){
            $arr_category = $request->category;
            $arr_insert = [];
            foreach ($arr_category as $item){
                $data = [
                    'id_news' =>$news->id,
                    'id_category' => $item
                ];
                array_push($arr_insert, $data);
            }

            if(count($arr_insert) > 0){
                NewsCategoryDetail::insert($arr_insert);
            }
        }

        $this->forget_cache('__news_list_home');

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'body_content' => 'required',
            'publish_for' => 'required',
            'availability' => 'required',
            'row_status' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $news = News::find($request->id);

        if(!$news){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data Not Found!')]);
        }

        if($request->url_to_image){
            $validation = Validator::make($request->all(), [
                'url_to_image' => 'required|image|mimes:jpg,jpeg,png,webapp',
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $news->url_to_image = Utils::upload($request,'url_to_image','news/');
        }

        $news->row_status = $request->row_status;
        $news->author = $request->author;
        $news->reward = $request->reward;
        $news->title = $request->title;
        $news->content = str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars(Utils::mark_down($request->body_content)));
        $news->updated_at = date('Y-m-d H:m:s');
        $news->updated_by = Auth::user()->name;
        $news->is_tester = $request->availability;

        if($news->save()){
            if($request->category){
                $arr_category = $request->category;
                NewsCategoryDetail::where('id_news','=',$news->id)->delete();
                foreach ($arr_category as $item){
                    NewsCategoryDetail::updateOrCreate(['id_news' => $news->id, 'id_category' => $item],
                        [
                            'id_news' =>$news->id,
                            'id_category' => $item
                        ]
                    );
                }
            }
        }

        $this->forget_cache('__news_list_home');

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $news = News::find($request->id);

        if(!$news){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data Not Found!')]);
        }

        $news->row_status = 'deleted';
        $news->updated_by = Auth::user()->name;

        if(!$news->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Error!')]);
        }

        $this->forget_cache('__news_list_home');

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = News::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
