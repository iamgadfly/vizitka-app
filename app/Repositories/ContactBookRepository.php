<?php

namespace App\Repositories;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Models\ContactBook;
use Illuminate\Support\Collection;

/**
 * ContactBookRepository class
 *
 * @package App\Repositories
 *
 * @extends Repository
 *
 * @method Collection<ContactBook> all()
 * @method ContactBook getById(int $id)
 * @method bool update($id, array $data)
 * @method bool deleteById($id)
 * @method ContactBook whereFirst(array $condition)
 * @method Collection<ContactBook> whereGet(array $condition)
 * @method bool massDelete(array $ids)
 */
class ContactBookRepository extends Repository
{
    public function __construct(ContactBook $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $clientId
     * @param string $type
     * @return ContactBook|null
     */
    public function thrashedRecord(int $clientId, string $type): ?ContactBook
    {
        $type_id = $type == 'client' ? 'client_id' : 'dummy_client_id';
        return $this->model::onlyTrashed()
            ->where([
                'specialist_id' => auth()->user()->specialist->id,
                $type_id => $clientId
            ])->first();
    }

    /**
     * @param array $data
     * @return ContactBook
     */
    public function create(array $data)
    {
        return $this->model::updateOrCreate($data, $data);
    }
}
