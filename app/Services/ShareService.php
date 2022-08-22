<?php

namespace App\Services;

use App\Exceptions\LinkHasExpiredException;
use App\Models\Specialist;
use App\Repositories\ShareRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ShareService
{
    public function __construct(
        protected ShareRepository $repository
    ) {}

    public function createShortlink(string $url, $sharableType, $sharableId)
    {
        $url = str($url)->ltrim('/')->value();
        $link = $this->repository->whereFirst([
            'url' => $url,
            ['deactivated_at', '>=', Carbon::now()]
        ]);
        if (!is_null($link)) {
            return $link;
        }

        $share = $this->repository->create([
            'url' => $url,
            'sharable_type' => $sharableType,
            'sharable_id' => $sharableId
        ]);

        $url = config('app.url') . '/shares/' . $share->hash;
        $qr = QRService::generate($url);

        Storage::disk('public')->put("images/shares/{$share->hash}.png", $qr);
    }

    /**
     * @throws LinkHasExpiredException
     */
    public function getByHash(string $hash): string
    {
        $url = $this->repository->whereFirst([
            'hash' => $hash,
            ['deactivated_at', '>=', Carbon::now()]
        ]) ?? throw new LinkHasExpiredException;

        if ($url->sharable_type == Specialist::class) {
            return config('custom.vizitnica_deep_link') . $url->url;
        }
        return config('custom.vizitka_deep_link'). $url->url;
    }

    public function getQrCode(string $url)
    {
        $link = $this->createShortlink($url);
        $url = config('app.url') . "/shares/" . $link->hash;

        $qr = QRService::generate($url);
        return response($qr, 200, [
            'Content-Type' => 'image/png'
        ]);
    }
}
