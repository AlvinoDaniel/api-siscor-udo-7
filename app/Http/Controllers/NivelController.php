<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\NivelRepository;
use App\Http\Requests\NivelRequest;

class NivelController extends AppBaseController
{
    private $repository;

    public function __construct(NivelRepository $nivelRepository)
    {
        $this->repository = $nivelRepository;
    }

     /**
     * Listar todos los departamentos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $niveles = $this->repository->all();
            $message = 'Lista de Niveles';
            return $this->sendResponse(['niveles' => $niveles], $message);
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
    public function store(NivelRequest $request)
    {
        try {
            $nivel = $this->repository->registrar($request->all());
            return $this->sendResponse(
                $nivel,
                'Nivel de Instrucción Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NivelRequest $request, $id)
    {

        try {
            $nivel = $this->repository->actualizar($request->all(), $id);
            return $this->sendResponse(
                $nivel,
                'Nivel Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el nivel'
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
                'Nivel Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Eliminar el Nivel'
            );
        }
    }
}
