<?php

namespace App\Repositories;

use App\Models\Blacklist;
use Illuminate\Support\Collection;

/**
 * BlacklistRepository class
 *
 * @package App\Repositories
 *
 * @extends Repository
 *
 * @method Blacklist create(array $data)
 * @method Collection<Blacklist> all()
 * @method Blacklist getById(int $id)
 * @method bool update($id, array $data)
 * @method bool deleteById($id)
 * @method Blacklist whereFirst(array $condition)
 * @method Collection<Blacklist> whereGet(array $condition)
 * @method bool massDelete(array $ids)
 */
class BlacklistRepository extends Repository
{
    public function __construct(Blacklist $model)
    {
        parent::__construct($model);
    }
}
