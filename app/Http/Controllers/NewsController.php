<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.news.new');
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
            "title"=>$request->title,
            "description"=>"",
            "url"=>"",
            "url_to_image"=>Utils::upload($request,'url_to_image','news/'),
            "content"=>str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($request->body_content)),
            "published_at"=> date('Y-m-d H:m:s'),
            "created_at" => date('Y-m-d H:m:s'),
            "created_by" => Auth::user()->name,
            "is_tester" => $request->availability
        );

        News::insert($news_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = News::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
