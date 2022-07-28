<?php

namespace App\Repositories;

use App\Models\Share;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ShareRepository
 *
 * @package App\Repositories
 *
 * @method  Collection<Share> whereGet(array $condition)
 * @method Share whereFirst(array $condition)
 */
class ShareRepository extends Repository
{
    public function __construct(Share $model)
    {
        parent::__construct($model);
    }
}
