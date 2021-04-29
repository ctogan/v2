<?php

namespace App\Console\Commands;

use App\Helpers\Utils;
use App\News;
use App\NewsCategory;
use App\NewsCategoryDetail;
use Illuminate\Console\Command;

class CtreeMagzJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ctreemagz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching news from ctreemagz.id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit','-1');

        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:m:s'));
        date_add($date, date_interval_create_from_date_string('-2 week'));
        $filter_date = date_format($date, 'Y-m-d\TH:i:s');

        $this->getCategory();
//        $url = 'https://ctreemagz.id/wp-json/wp/v2/posts?orderby=date&order=desc&after='.$filter_date.'&per_page=100&page=1';

        $url = 'https://ctreemagz.id/wp-json/wp/v2/posts?orderby=date&order=desc&after=2020-09-01T00:00:01&per_page=100&page=1';
        $cURL = curl_init();

        // Optional Authentication:
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        $result  = curl_exec($cURL);

        if($result){
            $data = json_decode($result);
            foreach($data as $item){
                $news_insert = array(
                    "row_status" => 'active',
                    "id_news"=>$item->id,
                    "featured_media_id"=>$item->featured_media,
                    "source_name"=> "CtreeMagz.id",
                    "author"=>"Cashtree",
                    "title"=>$item->title->rendered,
                    "description"=>$item->id,
                    "url"=>$item->link,
                    "url_to_image"=>$this->getImage($item->featured_media),
                    "content"=>str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars(Utils::mark_down($item->content->rendered))),
                    "published_at"=>$item->date,
                    "created_at" => date('Y-m-d H:m:s'),
                    "created_by" => "Job",
                );

                $news = News::where('id_news', $item->id)->first();
                if($news){
                    if($news->row_status != 'deleted'){
                        $news->row_status = $item->status == 'publish' ? 'active' : 'inactive';
                        $news->id_news=$item->id;
                        $news->featured_media_id =$item->featured_media;
                        $news->source_name= "CtreeMagz.id";
                        $news->author= "Cashtree";
                        $news->title = $item->title->rendered;
                        $news->description = $item->id;
                        $news->url =$item->link;
                        $news->url_to_image =$this->getImage($item->featured_media);
                        $news->content =str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($item->content->rendered));
                        $news->published_at =$item->date;
                        $news->updated_at = date('Y-m-d H:m:s');
                        $news->updated_by = "Job";
                        if($news->save()){
                            if($item->categories) {
                                NewsCategoryDetail::where('id_news', '=', $item->id)->delete();
                                foreach ($item->categories as $cat){
                                    $news_cat = array(
                                        "id_news"=>$news->id,
                                        "id_category"=>$cat
                                    );
                                    NewsCategoryDetail::insert($news_cat);
                                }
                            }
                        }
                    }
                }else{
                    $nnews = News::create($news_insert);
                    if($item->categories) {
                        foreach ($item->categories as $cat){
                            $news_cat = array(
                                "id_news"=>$nnews->id,
                                "id_category"=>$cat
                            );
                            NewsCategoryDetail::insert($news_cat);
                        }
                    }
                }
            }
        }
    }

    public function getImage($id){
        $url = 'https://ctreemagz.id/wp-json/wp/v2/media/'.$id;
        $cURLImage = curl_init();
        $image_url ='';
        // Optional Authentication:
        curl_setopt($cURLImage, CURLOPT_URL, $url);
        curl_setopt($cURLImage, CURLOPT_URL, $url);
        curl_setopt($cURLImage, CURLOPT_HTTPGET, true);
        curl_setopt($cURLImage, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLImage, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        $result_image  = curl_exec($cURLImage);

        if($result_image){
            $data_image = json_decode($result_image);
            $image_url = $data_image->guid->rendered;
        }
        curl_close($cURLImage);

        return $image_url;
    }

    public function getCategory(){
        $url = 'https://ctreemagz.id/wp-json/wp/v2/categories';
        $cURL = curl_init();

        // Optional Authentication:
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        $result  = curl_exec($cURL);

        if($result){
            $data = json_decode($result);
            foreach ($data as $item){
                $category = array(
                    "id" => $item->id,
                    "category_name" => $item->name,
                    "slug" => $item->slug
                );

                $exist = NewsCategory::find($item->id);
                if($exist){
                    $exist->category_name = $item->name;
                    $exist->slug = $item->slug;
                    $exist->save();
                }else{
                    NewsCategory::insert($category);
                }
            }
        }
        curl_close($cURL);

        return true;
    }
}
