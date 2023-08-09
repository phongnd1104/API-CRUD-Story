<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function author(){
        return $this->belongsTo(Author::class);
    }
    public function illustrator()
    {
        return $this->belongsTo(Illustrator::class);
    }
}
