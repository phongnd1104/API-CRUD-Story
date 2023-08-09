<?php

namespace App\Repositories\Story;

use App\Models\Story;
use App\Repositories\BaseRepositories;

class StoryRepository extends BaseRepositories
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Story::class;
    }
}
