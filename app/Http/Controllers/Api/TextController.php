<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TextRequest;
use App\Http\Resources\AudioResource;
use App\Http\Resources\TextResource;
use App\Repositories\Audio\AudioRepository;
use App\Repositories\Text\TextRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class TextController extends Controller
{
    private $textRepo;
    private $audioRepo;

    public function __construct(TextRepository $textRepo, AudioRepository $audioRepo)
    {
        $this->textRepo = $textRepo;
        $this->audioRepo = $audioRepo;
    }

    public function index()
    {
        $text = $this->textRepo->all();
        $data = TextResource::collection($text);
        $this->message = "Success";

        return $this->responseData($data??[]);
    }

    public function show($id)
    {
        $text = $this->textRepo->find($id);
        if($text)
        {
            $this->message = "success";
            $data = new TextResource($text);
        }else{
            $this->message = "find not found";
        }

        return $this->responseData($data??[]);
    }

    public function store(TextRequest $request)
    {
        $request->validated();
        DB::beginTransaction();
        try {
            $sentence = $request->sentence;

            $AudioUpload = $request->file('audio');
            $result = (!str_contains($sentence, "'") ? $sentence : str_replace("'","_", $sentence) );
            $audioName = $result.".".$AudioUpload->extension();
            Storage::disk("public")->put($audioName, file_get_contents($request->audio));
            $audioPath = storage_path("app/public/".$audioName);

            $audio = $this->audioRepo->store([
                'audio' => $audioName,
                'path' =>   $audioPath,
                'created_at' => time(),
                'updated_at' => time()
            ]);

            $text = $this->textRepo->store([
                'sentence' => $request->sentence,
                'audio_id' => $audio->id,
                'created_at' => time(),
                'updated_at' => time()
            ]);

            if($text){
                $this->message = "created successfully";
                $data = [
                    "text" => new TextResource($text),
                    'audio' => new AudioResource($audio),
                ];
            }
            db::commit();
        }catch (\Exception $e)
        {
            db::rollBack();
            throw new Exception($e->getMessage());
        }
        return $this->responseData($data??[]);
    }

    public function update(TextRequest $request, $id)
    {
        $request->validated();
        DB::beginTransaction();
        try {
            $sentence = $request->sentence;
            $getAudio = $this->audioRepo->find($id);

            if($request->audio)
            {
                $storage = Storage::disk('public');
                if($storage->exists($getAudio->audio)){
                    $storage->delete($getAudio->audio);
                }
            }

            $AudioUpload = $request->file('audio');
            $result = (!str_contains($sentence, "'") ? $sentence : str_replace("'","_", $sentence) );
            $audioName = $result.".".$AudioUpload->extension();
            Storage::disk("public")->put($audioName, file_get_contents($request->audio));
            $audioPath = storage_path("app/public/".$audioName);



            $audio = $this->audioRepo->update([
                'audio' => $audioName,
                'path' =>   $audioPath,
                'updated_at' => time()
            ], $id);

            $text = $this->textRepo->update([
                'sentence' => $request->sentence,
                'audio_id' => $getAudio->id,
                'updated_at' => time()
            ],$id);

            if($text){
                $this->message = "updated successfully";

            }
            db::commit();
        }catch (\Exception $e)
        {
            db::rollBack();
//            throw new Exception($e->getMessage());
        }
        return $this->responseData($data??[]);
    }

    public function destroy($id)
    {
        $getAudio = $this->audioRepo->find($id);


        db::beginTransaction();
        try{
            $text = $this->textRepo->destroy($id);
            $audio = $this->audioRepo->destroy($id);

            $storage = Storage::disk('public');
            if($storage->exists($getAudio->audio)){
                $storage->delete($getAudio->audio);
            }
            if($text && $audio)
            {
                $this->message = "deleted successfully";

            }
            db::commit();
        }catch (\Exception $e){
            db::rollBack();

//            throw new Exception($e->getMessage());
        }

        return $this->responseData($data??[]);
    }

}
