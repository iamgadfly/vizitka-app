<?php

namespace App\Services;

use App\Exceptions\LinkHasExpiredException;
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
        $link = $this->repository->whereFirst([
            'url' => $url,
            ['deactivated_at', '>=', Carbon::now()]
        ]);
        if (!is_null($link)) {
            return $link;
        }
        return $this->repository->create(['url' => $url]);
    }

    /**
     * @throws LinkHasExpiredException
     */
    public function getByHash(string $hash)
    {
        $url = $this->repository->whereFirst([
            'hash' => $hash,
            ['deactivated_at', '>=', Carbon::now()]
        ])?->url ?? throw new LinkHasExpiredException;

        return [
            'url' => config('app.url') . $url
        ];
    }
}
