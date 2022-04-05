<?php

namespace App\Services;

use App\Helpers\CardBackgroundHelper;
use App\Repositories\BusinessCardRepository;


class BusinessCardService
{
    public function __construct(
        protected BusinessCardRepository $repository,
    ) {}

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }

    public function update(array $data)
    {
        $data['background_image'] = CardBackgroundHelper::filenameFromActivityKind($data['background_image']);
        return $this->repository->update($data['id'], $data);
    }
}
