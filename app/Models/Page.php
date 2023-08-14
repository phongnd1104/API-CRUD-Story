<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'image_id',
        'story_id',
        'created_at',
        'updated_at'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function texts()
    {
        return $this->belongsToMany(Text::class, 'text_page')->withPivot('positions')->withTimestamps();
    }

    public function textpages()
    {
        return $this->hasMany(TextPage::class,'page_id');
    }


}
