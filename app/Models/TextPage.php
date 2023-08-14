<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextPage extends Model
{
    use HasFactory;
    public $table = "text_page";

    public function text()
    {
        return $this->belongsTo(Text::class);
    }
}
