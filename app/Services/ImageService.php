<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\ImageRepository;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ImageService
{
    public function __construct(
        protected ImageRepository $repository
    ) {}

    /**
     * @param UploadedFile $image
     * @return void
     */
    public function create(UploadedFile $image)
    {
        $path = $this->storeImage($image);

        return $this->repository->create([
            'url' => $path
        ]);
    }

    public function getByUrl(string $url)
    {
        return $this->repository->getByUrl($url);
    }

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }

    public function delete(int $id): bool
    {
        $item = $this->repository->getById($id);

        if (is_null($item)) return false;

        Storage::disk('public')->delete($item->url);
        $item->delete();

        return true;
    }

    public function removeTemporary(Image $image): Image
    {
        $image->deleted_at = null;
        $image->save();

        return $image;
    }

    public function makeTemporary(Image $image): Image
    {
        $image->deleted_at = Carbon::now();
        $image->save();

        return $image;
    }

    /**
     * @param UploadedFile $image
     * @return string
     */
    private function storeImage(UploadedFile $image): string
    {
        $filename = date('H:i:s') . '-' . md5(auth()->id()) . '.' . $image->extension();
        $file_path = config('custom.photo_path') . '/' . $filename;

        Storage::disk('public')->put($file_path, file_get_contents($image));

        return $file_path;
    }
}
