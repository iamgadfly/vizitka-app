<?php

namespace App\Services;

use App\Repositories\ShareRepository;


class ShareService
{
    public function __construct(
        protected ShareRepository $repository
    ) {}

    public function createShortlink(string $url)
    {
        $url = str($url)->replace(config('app.url'), '')->value();
        return $this->repository->create(['url' => $url]);
    }
}
