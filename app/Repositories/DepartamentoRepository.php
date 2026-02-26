<?php

namespace App\Repositories;

use App\Interfaces\DepartamentoRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Departamento;
use App\Models\RemitenteExterno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Exception;


class DepartamentoRepository extends BaseRepository implements DepartamentoRepositoryInterface {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Departamento $departamento)
  {
      $this->model = $departamento;
  }

  /**
   * Listar todas las departamentos de un departamento
   */
  public function alldepartamentos(){
      $departamento = Auth::user()->personal->departamento;
      return Departamento::where('id','<>' ,$departamento->id)
        ->where('cod_nucleo',$departamento->cod_nucleo)
        ->get();

  }
  /**
   * Listar todas las departamentos de un Nucleo
   */
  public function departamentsByNucleo($nucleo){
      return Departamento::where('cod_nucleo', $nucleo)
        ->get();
  }

  public function departamentsForWritre(){
    $user_nucleo = Auth::user()->personal->cod_nucleo;
    $departamento = Auth::user()->personal->departamento;
      return Departamento::has('jefe')
        ->where('cod_nucleo', $user_nucleo)
        ->where('id','<>' ,$departamento->id)
        ->get();
  }

  public function externalForWritre(){
    $departamento = Auth::user()->personal->departamento;
      return RemitenteExterno::whereHas('documentos', function (Builder $query) use($departamento) {
            $query->where('departamento_receptor', $departamento->id);
            $query->where('responder', 1);
        })->get();
  }

  public function subDepartaments(){
    $user_nucleo = Auth::user()->personal->cod_nucleo;
    $departamento = Departamento::with(['subDepartamentos'])
        ->find(Auth::user()->personal->departamento->id);

    return $departamento;
  }

}
