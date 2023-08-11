<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'audio' => 'required',
            'sentence' => 'required|string'
        ]);
        if ($validator->fails())
        {
            $this->message = $validator->messages();
            goto next;
        }
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
        }catch (Exception $e)
        {
            db::rollBack();
            throw new Exception($e->getMessage());
        }
        next:
        return $this->responseData($data??[]);
    }


}
