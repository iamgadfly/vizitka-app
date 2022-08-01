<?php

namespace App\Repositories;

use App\Models\Device;
use Illuminate\Support\Collection;

/**
 * Class DeviceRepository
 *
 * @package App\Repositories;
 *
 * @method Device whereFirst(array $condition)
 * @method Collection<Device> whereGet(array $condition)
 */
class DeviceRepository extends Repository
{
    public function __construct(Device $model)
    {
        parent::__construct($model);
    }
}
