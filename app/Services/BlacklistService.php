<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Repositories\BlacklistRepository;
use App\Repositories\ContactBookRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BlacklistService
{
    public function __construct(
        protected BlacklistRepository $repository,
        protected ContactBookRepository $contactBookRepository
    ) {}

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(array $data): bool
    {
        $blacklistRecord = $this->repository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'client_id' => $data['blacklisted_id']
        ]);
        if (!is_null($blacklistRecord)) {
            throw new RecordIsAlreadyExistsException;
        }
        $record = $this->contactBookRepository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'client_id' => $data['blacklisted_id']
        ]);

        if (!is_null($record)) {
            $record->delete();
        }
        return !is_null($this->repository->create($data));
    }

    /**
     * @throws RecordNotFoundException
     */
    public function delete(int $id)
    {
        $record = $this->contactBookRepository->thrashedRecord($id);
        if (!is_null($record)) {
            $record->restore();
        }
        $recordId = $this->repository->whereFirst([
            'blacklisted_id' => $id,
            'specialist_id' => auth()->user()->specialist->id
        ])?->id;
        if (is_null($recordId)) {
            throw new RecordNotFoundException;
        }
        return $this->repository->deleteById($recordId);
    }


    public function get(int $specialist_id)
    {
        return $this->repository->whereGet([
            'specialist_id' => $specialist_id
        ]);
    }
}
