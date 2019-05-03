<?php

namespace App\Repositories\Eloquent\Product;


use App\Models\Product\Brand;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Product\brandRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BrandRepository extends BaseRepository implements brandRepositoryInterface
{

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}