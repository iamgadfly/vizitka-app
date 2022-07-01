<?php

namespace App\Services;

use App\Repositories\ShareRepository;
use Carbon\Carbon;


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

    public function getByHash(string $hash)
    {
        return [
            'url' => $this->repository->whereFirst([
                    'hash' => $hash,
                    ['deactivated_at', '<=', Carbon::now()
                ]
        ])->url];
    }
}
