<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Repositories\Story\StoryRepository as StoryRepository;
use Illuminate\Http\Request;

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
}
