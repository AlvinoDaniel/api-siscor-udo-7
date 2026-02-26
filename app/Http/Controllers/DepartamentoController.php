<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Interfaces\DepartamentoRepositoryInterface;
use App\Http\Requests\DepartamentoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Nucleo;
use Exception;

class DepartamentoController extends AppBaseController
{
    private $repository;

    public function __construct(DepartamentoRepositoryInterface $departamentoRepository)
    {
        $this->repository = $departamentoRepository;
    }

    /**
     * Listar todos los departamentos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $departamentos = $this->repository->alldepartamentos();
            $message = 'Lista de Departamentos';
            return $this->sendResponse(['departamentos' => $departamentos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function list()
    {
        try {
            $departamentos = Departamento::with(['dptoSuperior'])->orderBy('cod_nucleo', 'asc')->get();
            $message = 'Lista de Departamentos';
            return $this->sendResponse(['departamentos' => $departamentos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartamentoRequest $request)
    {
        $data = $request->except('permiso_secretaria');
        // $config_dpto = array();
        // $config_dpto['permiso_enviar_secretaria'] = empty($request->permiso_secretaria) ? 0 : $request->permiso_secretaria;
        // $data['configuracion'] = json_encode($config_dpto);
        // dd($data);
        try {
            $departamento = $this->repository->registrar($data);
            return $this->sendResponse(
                $departamento,
                'Departamento Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartamentoRequest $request, $id)
    {

        $data = $request->except('permiso_secretaria');
        if($request->has('correlativo')){
            $data['correlativo'] = $request->correlativo;
        }
        // $config_dpto = array();
        // $config_dpto['permiso_enviar_secretaria'] = empty($request->permiso_secretaria) ? 0 : $request->permiso_secretaria;
        // $data['configuracion'] = json_encode($config_dpto);
        try {
            $departamento = $this->repository->actualizar($data, $id);
            return $this->sendResponse(
                $departamento,
                'Departamento Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Departamento'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Departamento Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Eliminar el Departamento'
            );
        }
    }

     /**
     * Listar todos los departamentos.
     *
     * @return \Illuminate\Http\Response
     */
    public function allNucleos()
    {
        try {
            $nucleos = Nucleo::all();
            $message = 'Lista de Nucleos';
            return $this->sendResponse(['nucleos' => $nucleos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }


    /**
     * Listar todos los departamentos.
     *
     * @return \Illuminate\Http\Response
     */
    public function departamentsByNucleo(Request $request)
    {
        $nucleo = $request->nucleo;
        try {
            $departamentos = $this->repository->departamentsByNucleo($nucleo);
            $message = 'Lista de Departamentos';
            return $this->sendResponse(['departamentos' => $departamentos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function departamentsWrite()
    {
        try {
            $departamentos = $this->repository->departamentsForWritre();
            $externos = $this->repository->externalForWritre();
            $message = 'Lista de Departamentos';
            return $this->sendResponse([
                    'departamentos' => $departamentos,
                    'externos' => $externos,
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function subDepartaments()
    {
        try {
            $departamentos = $this->repository->subDepartaments();
            $message = 'Lista de Sub Departamentos';
            return $this->sendResponse(['departamentos' => $departamentos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }


}
