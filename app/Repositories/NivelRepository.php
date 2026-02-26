<?php

namespace App\Repositories;
use App\Models\Nivel;

class NivelRepository extends BaseRepository
{
     /**
     * @var Model
     */
    protected $model;

    /**
     * Base Repository Construct
     *
     * @param Model $model
     */
    public function __construct(Nivel $nivel)
    {
        $this->model = $nivel;
    }

}
