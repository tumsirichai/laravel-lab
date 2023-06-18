<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Validator;
use Image;

use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // - variable
        $sql    = array();
        $data   = array();
        $q      = '';
        
        // - validation
        $this->validate($request, ['q' => 'max:200']);

        // - preparation
        $q = strip_tags($request->input('q'));

        // - processing
        $sql = new Blog();
        $sql = $sql->where('status','<>','delete');
        if($q){$sql = $sql->where('name','LIKE','%'.$q.'%');}
        $data = $sql->orderBy('update_date','DESC')->paginate(50);
        $data->setPath('');

        // - resulte
        return view('admin.blog.index',['data' => $data,'q' => $q]);
    }

    public function addForm(Request $request)
    {
        // - variable
        $data = array();

        // - preparation
        if(File::exists('content/temp/blog.jpg')) {File::delete('content/temp/blog.jpg');}
        
        // - resulte
        return view('admin.blog.add',['data' => $data]);
    }

    public function add(Request $request)
    {
        // - variable
        // ...

        // - validation
        $this->validate($request, ['name' => 'required|min:3|max:255']);
        $this->validate($request, ['slug' => 'required|min:3|max:200']);
        $this->validate($request, ['permalink' => 'required|min:2|max:200|unique:blog,slug']);
        $this->validate($request, ['detail' => 'required']);
        $this->validate($request, ['status' => 'required|min:3|max:20']);

        // - preparation
        $name             = strip_tags($request->input('name'));
        $slug             = strip_tags($request->input('permalink'));
        $status           = strip_tags($request->input('status'));
        $detail           = $request->input('detail');

        // - processing
        $save = new Blog();  
        $save->name               = $name;
        $save->slug               = $slug;
        $save->detail             = $detail;
        $save->status             = $status;
        $save->add_date           = Carbon::now();
        $save->update_date        = Carbon::now();
        $save->save();
        $id = $save->id;

        if(File::exists('content/temp/blog.jpg')) {
            $image_name = $id.'-'.md5(date("YmdHis")).'.jpg';
            $img = Image::make('content/temp/blog.jpg');
            $img->save('content/blog/'.$image_name);
            File::delete('content/temp/blog.jpg');
            Blog::where('id',$id)->update(['image' => $image_name]);
        }

        // - resulte
        return redirect('staff-backend/blog/edit/'.$id)->with('status', 'success')->with('msg', 'บันทึกข้อมูลเรียบร้อย');

    }

    public function editForm($id)
    {
        // - var
        $data = array();

        // - validation
        $validator = Validator::make(
            array(
                'id' => $id
            ),
            array(
                'id' => 'required|integer'
            )
        );

        if ($validator->fails()){
            return view('error',['data'=>'']);
        }

        // - processing
        if(File::exists('content/temp/blog.jpg')) {File::delete('content/temp/blog.jpg');}
        $data =  Blog::where('id',$id)->take(1)->get();

        // - resulte
        return view('admin.blog.edit',['data'=>$data,'id'=>$id]);
    }

    public function edit(Request $request)
    {
        // - variable
        // ...

        // - validation 
        $this->validate($request, ['id' => 'required|integer']);
        $this->validate($request, ['name' => 'required|min:3|max:255']);
        $this->validate($request, ['slug' => 'required|min:2|max:200']);
        $this->validate($request, ['permalink' => 'required|min:2|max:200|unique:blog,slug,'.$request->input('id')]);
        $this->validate($request, ['detail' => 'required']);

        // - preparation
        $id               = strip_tags($request->input('id'));
        $name             = strip_tags($request->input('name'));
        $slug             = strip_tags($request->input('permalink'));
        $status           = strip_tags($request->input('status'));
        $detail           = $request->input('detail');

        // - processing
        Blog::where('id',$id)->update([
            'name'=>$name,
            'slug'=>$slug,
            'status'=>$status,
            'detail'=>$detail,
            'update_date' => Carbon::now()
        ]);
       
        if(File::exists('content/temp/blog.jpg')) {
            $image_name = $id.'-'.md5(date("YmdHis")).'.jpg';
            $img = Image::make('content/temp/blog.jpg');
            $img->save('content/blog/'.$image_name);
            File::delete('content/temp/blog.jpg');
            Blog::where('id',$id)->update(['image' => $image_name]);
        }

        // - resulte
        return redirect('staff-backend/blog/edit/'.$id)->with('status', 'success')->with('msg', 'บันทึกข้อมูลเรียบร้อย');

    }

    public function delete(Request $request)
    {
        // - variable
        // ...

        // - validation
        $this->validate($request, ['id' => 'required|integer']);

        // - preparation
        $id = strip_tags($request->input('id'));

        // - processing
        Blog::where('id',$id)->update(['status' => 'delete']);

        // - resulte
        // ...

    }

    public function deleteImage(Request $request)
    {
        // - variable
        // ...

        // - validation
        $this->validate($request, ['id' => 'required|integer']);
        $this->validate($request, ['image' => 'required|min:10|max:255']);

        // - preparation
        $id     = strip_tags($request->input('id'));
        $image  = strip_tags($request->input('image'));

        // - processing
        if(File::exists('content/blog/'.$image)) {File::delete('content/blog/'.$image);}
        Blog::where('id',$id)->update(['image' => '']);

        // - resulte
        // ...

    }


}
