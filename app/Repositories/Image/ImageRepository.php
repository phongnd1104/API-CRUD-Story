<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepositories;

class ImageRepository extends  BaseRepositories
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Image::class;
    }
}
