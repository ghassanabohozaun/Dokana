<?php

namespace App\Services\Api;

use App\Models\Category;
use App\Repositories\Api\GlobalRepository;

class GlobalService
{
    protected $globalRepository;
    public function __construct(GlobalRepository $globalRepository)
    {
        $this->globalRepository = $globalRepository;
    }

    // get categories
    public function getCategories($limit = null)
    {
        return $this->globalRepository->getCategories($limit);
    }


      // get brands
    public function getBrands($limit = null){
        return $this->globalRepository->getBrands();
    }

}
