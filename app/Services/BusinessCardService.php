<?php

namespace App\Services;

use App\Helpers\CardBackgroundHelper;
use App\Http\Resources\BusinessCardResource;
use App\Repositories\BusinessCardRepository;
use App\Repositories\SpecialistRepository;


class BusinessCardService
{
    public function __construct(
        protected BusinessCardRepository $repository,
        protected SpecialistRepository $specialistRepository
    ) {}

    public function create(array $data)
    {
        $specialist = $this->specialistRepository->findByUserId(auth()->id());
        $data['background_image'] = CardBackgroundHelper::filenameFromActivityKind($data['background_image']);
        $data['specialist_id'] = $specialist->id;
        $data['about'] = $specialist?->about;
        $data['card_title'] = $specialist->card_title;
        $data['phone_number'] = auth()->user()->phone_number;

        return $this->repository->create($data);
    }

    public function get(int $id): array
    {
        $card = $this->repository->getById($id);
        $specialist = $this->specialistRepository->getById($card->specialist_id);
        $specialist_name = "$specialist->name $specialist->surname";

        return [
            'id' => $id,
            'card_title' => $card->card_title,
            'about' => $card->about,
            'specialist_avatar' => asset('storage' . $specialist->avatar),
            'specialist' => $specialist_name,
            'phone_number' => $specialist->user->phone_number
        ];
    }
}
