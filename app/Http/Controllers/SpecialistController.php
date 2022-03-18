<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpecialistRequest;
use App\Http\Requests\GetSpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Services\SpecialistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpecialistController extends Controller
{
    public function __construct(
       protected SpecialistService $service
    ) {}

    public function create(CreateSpecialistRequest $request)
    {
        $specialist = $this->service->findByUserId($request->user_id);

        if (!is_null($specialist)) {
            return $this->error('Specialist is already existing', 400);
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
        return $this->success($this->service->create($body), 'Specialist created', 201);
    }

    public function get(GetSpecialistRequest $request)
    {
        return SpecialistResource::make($this->service->getSpecialistData($request->id));
    }

    public function me()
    {
        return SpecialistResource::make($this->service->getMe());
    }
}
