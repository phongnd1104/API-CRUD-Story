<?php

namespace App\Repositories\Text;

use App\Models\Text;
use App\Repositories\BaseRepositories;

class TextRepository extends BaseRepositories
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Text::class;
    }
}
