<?php

namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use Illuminate\Database\Eloquent\Model as Model;

class Repository
{
    public function __construct(
        protected Model $model
    ) {}

    public function all()
    {
        return $this->model::all();
    }

    public function getById($id)
    {
        return $this->model::find($id);
    }

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(array $data)
    {
        $item = $this->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'phone_number' => $data['phone_number']
        ]);
        if (!is_null($item)) {
            throw new RecordIsAlreadyExistsException;
        }
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model::find($id);

        return $model->update($data);
    }

    public function deleteById($id)
    {
        $model = $this->model::find($id);

        return $model->delete();
    }

    public function whereFirst(array $condition)
    {
        return $this->model::where($condition)->first();
    }

    public function whereGet(array $condition)
    {
        return $this->model::where($condition)->get();
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public static function getSpecialistIdFromAuth(): ?int
    {
        return AuthHelper::getSpecialistIdFromAuth();
    }

    /**
     * @throws ClientNotFoundException
     */
    public static function getClientIdFromAuth(): ?int
    {
        return AuthHelper::getClientIdFromAuth();
    }
}
