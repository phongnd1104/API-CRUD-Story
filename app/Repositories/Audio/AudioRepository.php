<?php

namespace App\Repositories\Audio;

use App\Models\Audio;
use App\Repositories\BaseRepositories;

class AudioRepository extends BaseRepositories
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Audio::class;
    }
}
