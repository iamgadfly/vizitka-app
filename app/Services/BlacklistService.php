<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
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
     * @throws SpecialistNotFoundException
     */
    public function create(array $data): bool
    {
        $type_blacklist_id = $data['type'] == 'client' ? 'blacklisted' : 'dummy_client_id';
        $type_id = $data['type'] == 'client' ? 'client_id' : 'dummy_client_id';
        $blacklistRecord = $this->repository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            $type_blacklist_id => $data['blacklisted_id']
        ]);
        if (!is_null($blacklistRecord)) {
            throw new RecordIsAlreadyExistsException;
        }
        $record = $this->contactBookRepository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            $type_id => $data['blacklisted_id']
        ]);

        if (!is_null($record)) {
            $record->delete();
        }
        return !is_null($this->repository->create([
            $type_blacklist_id => $data['blacklisted_id'],
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
        ]));
    }

    /**
     * @throws RecordNotFoundException
     * @throws SpecialistNotFoundException
     */
    public function delete(int $id, string $type)
    {
        $type_id = $type == 'client' ? 'blacklisted_id' : 'dummy_client_id';
        $record = $this->contactBookRepository->thrashedRecord($id, $type);
        if (!is_null($record)) {
            $record->restore();
        }
        $recordId = $this->repository->whereFirst([
            $type_id => $id,
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
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
