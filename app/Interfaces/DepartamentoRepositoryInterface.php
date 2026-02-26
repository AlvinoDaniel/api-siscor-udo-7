<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;

interface DepartamentoRepositoryInterface extends BaseRepositoryInterface
{
    public function alldepartamentos();
    public function departamentsByNucleo($nucleo);
    public function departamentsForWritre();
    public function externalForWritre();
}
