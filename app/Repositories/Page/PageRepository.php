<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\BaseRepositories;

class PageRepository extends BaseRepositories
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Page::class;
    }
}
