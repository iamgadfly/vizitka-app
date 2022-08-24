<?php

namespace App\Repositories;

use App\Models\DummyBusinessCard;
use Illuminate\Support\Collection;

/**
 * BlacklistRepository class
 *
 * @package App\Repositories
 *
 * @extends Repository
 *
  * @method Collection<DummyBusinessCard> all()
 * @method DummyBusinessCard getById(int $id)
 * @method bool update($id, array $data)
 * @method bool deleteById($id)
 * @method DummyBusinessCard whereFirst(array $condition)
 * @method Collection<DummyBusinessCard> whereGet(array $condition)
 * @method bool massDelete(array $ids)
 */
class DummyBusinessCardRepository extends Repository
{
    public function __construct(DummyBusinessCard $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return DummyBusinessCard
     */
    public function create(array $data): DummyBusinessCard
    {
        return $this->model::updateOrCreate([
            'phone_number' => $data['phone_number']
        ], $data);
    }
}
