<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function audio()
    {
        return $this->belongsTo(Audio::class);
    }

    protected $fillable = [
        'sentence',
        'audio_id',
        'created_at',
        'updated_at'
    ];
}
