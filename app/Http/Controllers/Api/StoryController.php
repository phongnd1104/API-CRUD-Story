<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Repositories\Story\StoryRepository as StoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    private $storyRepo;
    public function __construct(StoryRepository $storyRepo)
    {
        $this->storyRepo = $storyRepo;
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
        }else
        {
            $story = $this->storyRepo->store([
                'name' => $request->name,
                'project' => $request->project,
                'course' => $request->course,
                'type' => $request->type,
                'thumb' => $request->thumb,
                'author_id' => $request->author_id,
                'illustrator_id' => $request->illustrator_id
            ]);
        }

    }
}
