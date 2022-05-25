<?php

namespace App\Services;

use App\Repositories\BlacklistRepository;


class BlacklistService
{
    public function __construct(
        protected BlacklistRepository $repository
    ) {}

    public function create(array $data)
    {
        return !is_null($this->repository->create($data));
    }

    public function delete(int $id)
    {
        $recordId = $this->repository->whereFirst([
            'blacklisted_id' => $id,
            'specialist_id' => auth()->user()->specialist->id
        ])->id;
        return $this->repository->deleteById($recordId);
    }
}
