<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductProviderController;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index(Request $request){
        // - variable
        $sql            = array();
        $data           = array();
        $q              = '';
        $category       = new ProductCategoryController(); 
        $provider       = new ProductProviderController(); 
        $categoryList   = $category->listData();
        $providerList   = $provider->listData();
        
        // - validation
        $this->validate($request, ['q' => 'max:200']);

        // - preparation
        $q = strip_tags($request->input('q'));

        // - processing
        $sql = new Blog();
        $sql = $sql->where('status','active');
        if($q){$sql = $sql->where('name','LIKE','%'.$q.'%');}
        $data = $sql->orderBy('update_date','DESC')->paginate(20);
        $data->setPath('');
        
        // - resulte
        return view('blog.index',['data' => $data,'q' => $q,'category'=>$categoryList,'provider'=>$providerList]);
        
    }

    public function show($slug){
        // - variable
        $data           = array();
        $category       = new ProductCategoryController(); 
        $provider       = new ProductProviderController(); 
        $categoryList   = $category->listData();
        $providerList   = $provider->listData();

        // - validation
        $validator = Validator::make(
            array(
                'id' => $slug
            ),
            array(
                'id' => 'required|min:2|max:200'
            )
        );

        if ($validator->fails()){
            return view('error',['name' => 'เกิดข้อผิดพลาด','detail' => 'ข้อมูลไม่ถูกต้อง']);
        }

        // - preparation
        // ...

        // - processing
        $data = Blog::where('slug',$slug)->where('status','active')->take(1)->get();

        // - resulte
        if(!empty($data[0])){
            return view('blog.detail',['data' => $data,'category'=>$categoryList,'provider'=>$providerList]);
        }else{
            return view('error',['name' => 'เกิดข้อผิดพลาด','detail' => 'ไม่พบเนื้อหา']);
        }

    }

}
