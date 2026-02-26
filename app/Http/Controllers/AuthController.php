<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use App\Http\Resources\UserAuthCollection;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Crypt;

class AuthController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'sendResetPasswordEmail']]);
    }


    /**
     * Login de usuario
     *
     * [Se envia email y contraseña para aceeder a la sesión.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  password string required Contraseña de usuario.
     *
     * @responseFile  responses/AuthController/login.post.json
     *
    */
   public function login(LoginRequest $request, $conexion){
      $tipo_accion =  'Login';
      $allowedConection = ['a','c'];
      if (!in_array($conexion, $allowedConection)) {
         return $this->sendError('Consulta no válida.');
      }
      try {
         $user = User::where('email', $request->username_email)
                  ->orWhere('usuario', $request->username_email)
                  ->first();
         if(!$user){
            return $this->sendError('El Email/Usuario no existe en nuestros registros.');
         }

         if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Las credenciales no concuerdan. Email o Contraseña inválida',);
         }

         if($conexion === 'a' && !$user->hasRole('administrador')){
            return $this->sendError('El usuario no tiene los permisos para acceder a la Administracíon del Sistema.',);
         }

         if($conexion === 'c' && !$user->hasAnyRole(['jefe', 'secretaria'])){
            return $this->sendError('El usuario no tiene los permisos para acceder al Sistema.',);
         }


         $token = $user->createToken('TokenCultorApi-'.$user->name)->plainTextToken;
         $message = 'Usuario Autenticado exitosamente.';

         // $this->generateLog(
         //    '200',
         //    $message,
         //    $tipo_accion,
         //    'success'
         // );
         return $this->sendResponse(['token' => $token ], $message);

      } catch (\Throwable $th) {
         // $this->generateLog(
         //    $th->getCode(),
         //    $th->getMessage(),
         //    $tipo_accion,
         //    'error'
         // );
         return $this->sendError('Ocurrio un error, contacte al administrador');
      }
   }

   /**
     * Obtener información de usuario logeado.
     *
     * [Petición para obtener informacion del usuario logeado..]
     *
     * @responseFile  responses/AuthController/me.get.json
     *
    */
   public function me()
   {
      $user = Auth::user()->load(['personal.departamento', 'roles']);
      $rolesCollection = collect($user->roles);
      $pluckedRoles = $rolesCollection->pluck('name');
      // $user = Auth::user()->personal->departamento_id;
      $user->personal->departamento->load(['dptoSuperior', 'subDepartamentos']);
      $data = [
        'id'                => $user->id,
        'fullName'          => $user->personal->nombres_apellidos,
        'email'             => $user->email,
        'usuario'           => $user->usuario,
        'status'            => $user->status,
        'departamento'      => $user->personal->departamento,
        'rol_id'            => $user->roles[0]->id,
        'rol'               => $pluckedRoles->all(),
    ];
      return $this->sendResponse([ 'user' => $data ], 'Datos de Usuario Logeado');
      // return $this->sendResponse([ 'user' => $user ], 'Datos de Usuario Logeado');
   }

     /**
     * Cerrar sesión.
     *
     * [Petición para cerrar sesión.]
     *
     * @response
        {
            "message": "Sesión cerrada con exito."
        }
     *
    */

    public function logout(){
      $user = Auth::user();
      try {
          //Auth::user()->currentAccessToken()->delete();
         $user->tokens()->delete();
         return $this->sendSuccess('Sesión cerrada con exito.');
      } catch (\Throwable $th) {
         return $this->sendError('Ocurrio un error al intentar cerrar la sesion.', 422);
      }
  }

   /**
     * Cambiar clave
     *
     * [Cambiar contraseña de usuario.]
     *
     * @bodyParam  password string required Contraseña actual de usuario.
     * @bodyParam  newpassword string required Nueva contraseña de usuario.
     * @bodyParam  repassword string required Nueva contraseña repetida de usuario.
     *
     * @response {
            "message": "Se actualizo la contraseña"
        }
     *
     *
    */

   public  function changePassword(ChangePasswordRequest $request)
   {
      $user = Auth::user();
      $password = Hash::make($request->password);
      if(Hash::check($request->password, $user->password)) {
         try {
            $user->update(['password'=>$request->newpassword]);
            return $this->sendSuccess('Se actualizo su contraseña Exitosamente');
         } catch (\Throwable $th) {
            return $this->sendError('Lo sentimos, hubo un error al intentar regustrar su nueva contraseña.', 422);
         }
      } else {
         return $this->sendError('La contraseña actual no coincide con nuestros registros.', 422);
      }
   }

   public function sendResetPasswordEmail(ResetPasswordRequest $request){
    $user = User::with('personal')->where('email', $request->email)->first();
    if($user->personal->cedula_identidad !== $request->identification){
      return $this->sendError('Los datos suministrados no coinciden con ningun personal registrado.');
    }
    return $this->sendResponse([
      'r' => true,
      'ur' => base64_encode($user->id)
   ], 'Se ha enviado el correo exitosamente.');

    $reset_link_send = $user->sendPasswordResetLink();
    if($reset_link_send){
        return $this->sendResponse([
         'r' => $reset_link_send,
         'ur' => Crypt::encryptString($user->id)
      ], 'Se ha enviado el correo exitosamente.');

    }
    return $this->sendError('Lo sentimos, hubo un error al intentar enviar el email.');

   }
}
