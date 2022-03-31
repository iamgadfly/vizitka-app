<?php

namespace App\Http\Controllers;

use App\Helpers\CardBackgroundHelper;
use App\Http\Requests\CreateSpecialistRequest;
use App\Http\Requests\GetSpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Services\SpecialistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SpecialistController extends Controller
{
    public function __construct(
       protected SpecialistService $service
    ) {}

    public function create(CreateSpecialistRequest $request)
    {
        $specialist = $this->service->findByUserId($request->user_id);

        if (!is_null($specialist)) {
            return $this->error('Specialist is already existing', Response::HTTP_BAD_REQUEST);
        }

        $body = $request->validated();
        if (!is_null($body['avatar'])) {
            $image = $body['avatar'];

            $ext = $image->extension();
            $filename = date('H:i:s') . '-' . md5(auth()->id()) . '.' . $ext;
            $file_path = config('custom.specialist_photo_path') . '/' . $filename;

            $image = file_get_contents($image);
            Storage::disk('public')->put($file_path, $image);

            $body['avatar'] = $file_path;
        }
        $body['user_id'] = auth()->id();
        $body['background_image'] = CardBackgroundHelper::filenameFromActivityKind($request->background_image);
        return $this->success($this->service->create($body), 'Specialist created', Response::HTTP_CREATED);
    }

    public function get(GetSpecialistRequest $request)
    {
        return $this->success(
            SpecialistResource::make($this->service->getSpecialistData($request->id)),
            null,
            Response::HTTP_OK
        );
    }

    public function me()
    {
        return $this->success(
            SpecialistResource::make($this->service->getMe()),
            null,
            Response::HTTP_OK
        );
    }
}
