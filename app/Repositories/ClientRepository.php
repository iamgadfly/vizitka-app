<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Collection;

/**
 * BlacklistRepository class
 *
 * @package App\Repositories
 *
 * @extends Repository
 *
 * @method Client create(array $data)
 * @method Collection<Client> all()
 * @method Client getById(int $id)
 * @method bool update($id, array $data)
 * @method bool deleteById($id)
 * @method Client whereFirst(array $condition)
 * @method Collection<Client> whereGet(array $condition)
 * @method bool massDelete(array $ids)
 */
class ClientRepository extends Repository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $id
     * @return Client|null
     */
    public function findByUserId($id): ?Client
    {
        return $this->model::where('user_id', $id)->first();
    }

    /**
     * @param string $phoneNumber
     * @return Client|null
     */
    public function findByPhoneNumber(string $phoneNumber): ?Client
    {
        return $this->model::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where(['phone_number' => $phoneNumber]);
        })->first();
    }
}
