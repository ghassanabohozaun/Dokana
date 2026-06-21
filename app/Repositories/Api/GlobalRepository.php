<?php

namespace App\Repositories\Api;

use App\Models\Brand;
use App\Models\Category;

class GlobalRepository
{
    // get categories
    public function getCategories($limit = null)
    {
        if ($limit == null) {
            return Category::active()->latest()->get();
        }
        return Category::active()->limit($limit)->latest()->get();
    }


    // get brands
    public function getBrands($limit = null){
        return Brand::active()->latest()->get();
    }
}
