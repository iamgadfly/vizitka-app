<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends Repository
{
    public function __construct(Image $model)
    {
        parent::__construct($model);
    }

    public function getByUrl(string $url)
    {
        return $this->model->where('url', $url)->first();
    }
}
