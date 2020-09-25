<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\DoanhNghiep;
use Symfony\Component\DomCrawler\Crawler;

class CrawlingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDoanhNghiep()
    {
        $number = 102;
//        $client = new \Goutte\Client();
//        $crawler = $client->request('GET', 'https://www.lazada.vn/dien-thoai-di-dong/?spm=a2o4n.home.cate_1.1.19056afeWLyiaY');
//        $crawler->filter('div.cpF1IH ul.ant-pagination li');
//        var_dump(($crawler->filter('div.cpF1IH ul.ant-pagination li')));
//        exit;
        
//        $crawler->filter('div.c3gNPq ul li')->each(
//                
//            function (Crawler $node) {
//        var_dump($node->filter('a')->text());
//        exit;
//            }
//        );
        $this->getDoanhNghiepByPage();
        for($i=2;$i<=$number;$i++){
            $this->getPhoneLazadaByPage($i);
        }
        
    }
    
    public function getDoanhNghiepByPage($page=null)
    {
        if($page){
            $url = 'https://www.lazada.vn/dien-thoai-di-dong/?spm=a2o4n.home.cate_1.1.19056afeWLyiaY?page='.$page;
        }
        else{
            $url = 'https://www.lazada.vn/dien-thoai-di-dong/?spm=a2o4n.home.cate_1.1.19056afeWLyiaY';
        }
        

        $client = new \Goutte\Client();

        $crawler = $client->request('GET', $url);
        $crawler->filter('div.c1_t2i div.c2prKC')->each(
            function (Crawler $node) {
       
                $photo = urlencode($node->filter('img')->attr('src'));
                $name = $node->filter('.c16H9d a')->text();
                

                $wholeStar = $node->filter('.c3dn4k')->count();
                $halfStar = $node->filter('.c3EEAg')->count();
                $rate = $wholeStar + 0.5 * $halfStar;
                
                $price = $node->filter('.c3gUW0 .c13VH6')->text();
                $price = str_replace(".", "", $price);
                preg_match_all('!\d+!', $price, $matches);
                $price = $matches[0][0];
                
                $product = new Product();
                $product->name = $name;
                $product->price = $price;
                $product->rate = $rate;
                $product->supplier = 'Lazada';
                $product->website = 'https://www.lazada.vn/dien-thoai-di-dong/?spm=a2o4n.home.cate_1.1.19056afeWLyiaY';
                $product->category = 'Điện tử';
                $product->sub_category = 'Phone';
                $product->photo = $photo;
                $product->save();
            }
        );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPhoneTgdd()
    {
        $url = 'https://www.thegioididong.com/dtdd';

        $client = new \Goutte\Client();

        $crawler = $client->request('GET', $url);
        
        $crawler->filter('ul.homeproduct li.item')->each(
            function (Crawler $node) {
       
                $name = $node->filter('h3')->text();
                $photo = urlencode($node->filter('img')->attr('data-original'));

                $wholeStar = $node->filter('.icontgdd-ystar')->count();
                $halfStar = $node->filter('.icontgdd-hstar')->count();
                $rate = $wholeStar + 0.5 * $halfStar;
                
                $price = $node->filter('.price strong')->text();
                $price = str_replace(".", "", $price);
                preg_match_all('!\d+!', $price, $matches);
                $price = $matches[0][0];
                
                $product = new Product();
                $product->name = $name;
                $product->price = $price;
                $product->rate = $rate;
                $product->supplier = 'Thế giới di động';
                $product->website = 'https://www.thegioididong.com/dtdd';
                $product->category = 'Điện tử';
                $product->sub_category = 'Phone';
                $product->photo = $photo;
                $product->save();
            }
        );
        
        return 'ok';
        
    }

}
