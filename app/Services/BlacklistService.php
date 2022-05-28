<?php

namespace App\Services;

use App\Repositories\BlacklistRepository;
use App\Repositories\ContactBookRepository;


class BlacklistService
{
    public function __construct(
        protected BlacklistRepository $repository,
        protected ContactBookRepository $contactBookRepository
    ) {}

    public function create(array $data)
    {
        $record = $this->contactBookRepository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'client_id' => $data['blacklisted_id']
        ]);
        if (!is_null($record)) {
            $record->delete();
        }
        return !is_null($this->repository->create($data));
    }

    public function delete(int $id)
    {
        $record = $this->contactBookRepository->whereFirst([
            'specialist_id' => $id,
            'client_id' => auth()->user()->specialist->id
        ]);
        if (!is_null($record) && $record->trashed()) {
            $record->restore();
        }
        $recordId = $this->repository->whereFirst([
            'blacklisted_id' => $id,
            'specialist_id' => auth()->user()->specialist->id
        ])->id;
        return $this->repository->deleteById($recordId);
    }
}
