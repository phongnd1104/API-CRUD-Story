<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\PageResource;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Page\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    private $pageRepo;
    private $imageRepo;

    public function __construct(PageRepository $pageRepo, ImageRepository $imageRepo)
     {
         $this->pageRepo = $pageRepo;
         $this->imageRepo = $imageRepo;
     }

     public function index()
     {
        $page = $this->pageRepo->all();
        $data = PageResource::collection($page);
        if($data){
            $this->message = "Success";
        }
        return $this->responseData($data??[]);
     }


     public function store(Request $request)
     {
         $validator = Validator::make($request->all(),
         [
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'story_id' => 'required',
             'text_id' => 'required|numeric'

         ]);
         if (!$validator)
         {
             $this->message = $validator->messages();
             goto next;
         }
             $getUploadFile = $request->file('image');
             $imageName = Str::random(32)."background.".$getUploadFile->extension();
             $imagePath = storage_path('app/public');
             Storage::disk('public')->put($imageName,file_get_contents($request->image));

             $image = $this->imageRepo->store([
                 'image' => $request->image,
                 'path' => $imagePath,
                 'classify' => "background",
                 'created_at' => time(),
                 'updated_at' => time()
             ]);
             $page = $this->pageRepo->store([
                 'image_id' => $image->id,
                 'story_id' => $request->story_id,
                 'created_at' => time(),
                 'updated_at' => time()
             ]);

             $page->texts()->attach($request->text_id, ['positions'=>$request->positions]);
             if($image && $page){
                 $this->message = "created successfully";
                 $data = [
                     'page' => new PageResource($page),
                     'image' => new ImageResource($image)
                 ];
             }

         next:
         return $this->responseData($data??[]);

     }

}
