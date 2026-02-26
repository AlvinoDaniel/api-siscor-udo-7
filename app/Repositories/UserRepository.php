<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;
use App\Models\Personal;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Exception;
use Illuminate\Support\Facades\Storage;



class UserRepository extends BaseRepository implements UserRepositoryInterface {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(User $user)
  {
      $this->model = $user;
  }

  /**
   * Registrar grupo de un Departamento
   * @param Array $departamentos
   * @param Array $data
   */
  public function verificarJefatura($departamento_id){

    try {
    //   $personal = Personal::find($departamento_id);

      $usuariosJefe =  User::whereHas('personal' , function (Builder $query) use($departamento_id) {
          $query->where('departamento_id', $departamento_id);
        })
        ->whereHas('roles', function (Builder $query) {
          $query->where('name', 'jefe');
        })
        ->get();
       return $usuariosJefe->count() === 1 ? false : true;
    } catch (\Throwable $th) {
       throw new Exception($th->getMessage());
    }
 }

 public function registrarUsuario($data){

    $roles = $data['rol'];
    $hasRolJefe = in_array('jefe', $roles);
    $personalData = [
        'nombres_apellidos'     => $data['nombres_apellidos'],
        'cedula_identidad'      => $data['cedula_identidad'],
        'cargo'                 => $data['cargo'],
        'correo'                => $data['email'],
        'cod_nucleo'            => $data['nucleo'],
        'departamento_id'       => $data['departamento_id'],
        'nivel_id'              => $data['grado_instruccion'],
        'jefe'                  => $hasRolJefe ? 1 : 0,
    ];

    $userData = [
        'email'                 => $data['email'],
        'usuario'               => $data['usuario'],
        'password'              => $data['password'],
        'status'                => 1,
    ];


    if($hasRolJefe){
        $firma = $data['firma'];
        $personalData['descripcion_cargo'] = $data['cargo_jefe'];
        $fileName = $firma->getClientOriginalName();
        $isExist = Storage::disk('firmas')->exists('/'.$data['cedula_identidad'].'/'.$fileName);
        if(!$isExist) {
            $path = Storage::disk('firmas')->putFileAs('/'.$data['cedula_identidad'], $firma, $fileName);
            $personalData['firma'] = $path;
        }
    }
    try {
        // DB::beginTransaction();

        $personal = Personal::create($personalData);
        $userData['personal_id'] = $personal->id;

        $user = User::create($userData);
        $user->assignRole($roles);

        return $user;

        // DB::commit();
    } catch (\Throwable $th) {
        // DB::rollBack();
        throw new Exception($th->getMessage());
        //throw $th;
    }

 }
 public function actualizarUsuario($data, $id){
    $user = User::find($id);
    $personal = Personal::find($user->personal_id);
    $roles = $data['rol'];
    $hasRolJefe = in_array('jefe', $roles);

    $personalData = [
        'nombres_apellidos'     => $data['nombres_apellidos'],
        'cedula_identidad'      => $data['cedula_identidad'],
        'cargo'                 => $data['cargo'],
        'correo'                => $data['email'],
        'cod_nucleo'            => $data['nucleo'],
        'departamento_id'       => $data['departamento_id'],
        'nivel_id'              => $data['grado_instruccion'],
        'jefe'                  => $hasRolJefe ? 1 : 0,
        'descripcion_cargo'     => $data['cargo_jefe']
    ];

    $userData = [
        'email'                 => $data['email'],
        'usuario'               => $data['usuario'],
        'password'              => $data['password'],
    ];

    if($hasRolJefe && !$user->hasRole('jefe')){
        $hasJefe = $this->verificarJefatura($data['departamento_id']);
        if(!$hasJefe){
            throw new Exception("No se puede actualizar el rol. Ya existe un usuario Jefe en el Departamento.");
        }
    }

    if($data['hasFile']){
        $firma = $data['firma'];
        $fileName = $firma->getClientOriginalName();
        $isExist = Storage::disk('firmas')->exists($user->path);
        if($isExist) {
            Storage::disk('anexos')->delete($user->path);
        }
        $path = Storage::disk('firmas')->putFileAs('/'.$data['cedula_identidad'], $firma, $fileName);
        $personalData['firma'] = $path;
    }

    try {
        // DB::beginTransaction();

        foreach ($personalData as $campo => $value) {
            if(!empty($value)){
                $personal->update([$campo => $value]);
            }
        }

        foreach ($userData as $campo => $value) {
            if(!empty($value)){
                $user->update([$campo => $value]);
            }
        }

        $user->syncRoles($roles);

        return $personalData;

        // DB::commit();
    } catch (\Throwable $th) {
        // DB::rollBack();
        throw new Exception($th->getMessage());
        //throw $th;
    }

 }

}
