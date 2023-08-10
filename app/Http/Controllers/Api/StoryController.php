<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Story\StoryRepository as StoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\Translation\t;


class StoryController extends Controller
{
    private $storyRepo;
    private $imageRepo;
    public function __construct(StoryRepository $storyRepo, ImageRepository $imageRepo)
    {
        $this->storyRepo = $storyRepo;
        $this->imageRepo = $imageRepo;
    }

    public function index()
    {
        $this->status = 200;
        $this->message = "success";
        $data = StoryResource::collection($this->storyRepo->all());

        return $this->responseData($data);
    }
    public function show($id)
    {
        $story = $this->storyRepo->find($id);
        if($story)
        {
            $this->status = 200;
            $this->message = "success";
            $data = new StoryResource($story);

        }
            return $this->responseData($data??[]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'project' => 'required',
            'course' => 'required',
            'type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|numeric',
            'illustrator_id' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            $this->status = "422";
            $this->message = $validator->messages();
            goto next;
        }else
        {
            DB::beginTransaction();
            try {
                $getUpLoadFile = $request->file('image');
                $imageName = time().".".$getUpLoadFile->extension();
                $imagePath = public_path("/images/stories");
                $getUpLoadFile->move($imagePath, $imageName);

                $image = $this->imageRepo->store([
                    'image' => $imageName,
                    'path' => $imagePath,
                    'classify' => "thumb",
                    'created_at' => time(),
                    'updated_at' => time()
                ]);
                $story = $this->storyRepo->store([
                    'name' => $request->name,
                    'project' => $request->project,
                    'course' => $request->course,
                    'type' => $request->type,
                    'thumb' => $request->thumb,
                    'author_id' => $request->author_id,
                    'illustrator_id' => $request->illustrator_id,
                    'image_id' => $image->id,
                    'created_at'=>time(),
                    'updated_at'=>time()
                ]);
                DB::commit();
            }catch (\Exception $e)
            {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }


            if($image)
            {
                $this->status = "success";
                $this->message = " Created Successfully";
                $data = [
                    "story" => $story,
                    "image" => $image
                ];
            }
            next:

            return $this->responseData($data ?? []);
        }

    }

    
}
