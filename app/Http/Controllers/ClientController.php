<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\GetClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $service
    ) {}

    public function create(CreateClientRequest $request)
    {
        $client = $this->service->findByUserId($request->user_id);

        if (!is_null($client)) {
            return $this->error('Client is already existing', 400);
        }

        $body = $request->validated();
        if (!is_null($body['avatar'])) {
            $image = $body['avatar'];

            $ext = $image->extension();
            $filename = date('H:i:s') . '-' . md5(auth()->id()) . '.' . $ext;
            $file_path = config('custom.client_photo_path') . '/' . $filename;

            $image = file_get_contents($image);
            Storage::disk('public')->put($file_path, $image);

            $body['avatar'] = $file_path;
        }
        $body['user_id'] = auth()->id();
        return $this->success($this->service->create($body), 'Client created', 201);
    }

    public function get(GetClientRequest $request)
    {
        return ClientResource::make($this->service->getClientData($request->id));
    }

    public function me()
    {
        return ClientResource::make($this->service->getMe());
    }
}
