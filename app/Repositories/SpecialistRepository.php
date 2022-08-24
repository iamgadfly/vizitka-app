<?php

namespace App\Repositories;

use App\Exceptions\SpecialistNotFoundException;
use App\Models\Specialist;
use Illuminate\Support\Collection;

/**
 * BlacklistRepository class
 *
 * @package App\Repositories
 *
 * @extends Repository
 *
 * @method Collection<Specialist> all()
 * @method Specialist getById(int $id)
 * @method bool update($id, array $data)
 * @method bool deleteById($id)
 * @method Specialist whereFirst(array $condition)
 * @method Collection<Specialist> whereGet(array $condition)
 * @method bool massDelete(array $ids)
 */
class SpecialistRepository extends Repository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return Specialist
     */
    public function create(array $data): Specialist
    {
        return $this->model::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );
    }

    /**
     * @param $id
     * @param bool $isRegistered
     * @return Specialist
     * @throws SpecialistNotFoundException
     */
    public function findByUserId($id, bool $isRegistered = true): Specialist
    {
        return $this->model::where([
            'user_id' => $id,
            'is_registered' => $isRegistered
        ])->first() ?? throw new SpecialistNotFoundException;
    }

    /**
     * @param int $id
     * @param bool $isRegistered
     * @return Specialist
     * @throws SpecialistNotFoundException
     */
    public function findById(int $id, bool $isRegistered = true): Specialist
    {
        return $this->model::where([
            'id' => $id,
            'is_registered' => $isRegistered
        ])->first() ?? throw new SpecialistNotFoundException;
    }

    /**
     * @param string $phoneNumber
     * @param bool $isRegistered
     * @return Specialist
     * @throws SpecialistNotFoundException
     */
    public function findByPhoneNumber(string $phoneNumber, bool $isRegistered = true): Specialist
    {
        return $this->model::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where('phone_number', $phoneNumber);
        })->where([
            'is_registered' => $isRegistered
        ])->get()->first() ?? throw new SpecialistNotFoundException;
    }
}
