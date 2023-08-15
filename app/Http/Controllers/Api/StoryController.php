<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest;
use App\Http\Resources\ImageResource;
use App\Http\Resources\StoryResource;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Story\StoryRepository as StoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;


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

    public function store(StoryRequest $request)
    {
            $request->validated();

            DB::beginTransaction();
            try {
                $getUpLoadFile = $request->file('image');
                $imageName = Str::random(32).".".$getUpLoadFile->extension();
                Storage::disk('public')->put($imageName, file_get_contents($request->image));
                $imagePath = storage_path("app/public".$imageName);

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
                    'author_id' => $request->author_id,
                    'illustrator_id' => $request->illustrator_id,
                    'image_id' => $image->id,
                    'created_at'=>time(),
                    'updated_at'=>time()
                ]);

                if($image)
                {
                    $this->status = "success";
                    $this->message = " Created Successfully";
                    $data = [
                        "story" => new StoryResource($story),
                        "image" => new ImageResource($image)
                    ];
                }
                DB::commit();
            }catch (\Exception $e)
            {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
            next:
            return $this->responseData($data ?? []);
        }

    public function update(StoryRequest $request, $id)
    {
        $request->validated();
        $getImage = $this->imageRepo->find($id);
        if($request->image){
            $storage = Storage::disk('public');
            if($storage->exists($getImage->image)){
                $storage->delete($getImage->image);
            }
        }
        $getUpLoadFile = $request->file('image');
        $imageName = Str::random(32).".".$getUpLoadFile->extension();
        Storage::disk('public')->put($imageName, file_get_contents($request->image));
        $imagePath = storage_path("app/public".$imageName);

        db::beginTransaction();
        try {
            $image = $this->imageRepo->update([
                'image' => $imageName,
                'path' => $imagePath,
                'classify' => "thumb",
                'updated_at' => time()
            ], $id);

            $story = $this->storyRepo->update([
                'name' => $request->name,
                'project' => $request->project,
                'course' => $request->course,
                'type' => $request->type,
                'thumb' => $request->thumb,
                'author_id' => $request->author_id,
                'illustrator_id' => $request->illustrator_id,
                'image_id' => $getImage->id,
                'updated_at'=>time()
            ], $id);

            if($image)
            {
                $this->status = "success";
                $this->message = " Updated Successfully";
            }
            db::commit();
        }catch (\Exception $e)
        {
            db::rollBack();

            throw new Exception($e->getMessage());
        }

            next:

            return $this->responseData($data ?? []);

    }

    public function destroy($id)
    {
        $getImage = $this->imageRepo->find($id);
        db::beginTransaction();
        try {
            $story = $this->storyRepo->destroy($id);
            $image = $this->imageRepo->destroy($id);

            $storage = Storage::disk('public');
            $storage->delete($getImage->image);
            if($story && $image){

                $this->status = 200;
                $this->message = "deleted successfully";
            }
            db::commit();
        }catch (\Exception){
            db::rollBack();
        }
        return $this->responseData($data ?? []);
    }

}
