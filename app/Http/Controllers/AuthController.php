<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Prueba deporte",
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"CRUD Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para registrar un nuevo usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="sifaqes"),
     *             @OA\Property(property="email", type="string", format="email", example="sifaqes@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="informacion de usuario"),
     *             @OA\Property(property="token", type="string", example="token_de_autenticacion")
     *         )
     *     ),

     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:100',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|confirmed|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password'))
        ]);

        $token = $user-> createToken('auth_token')->plainTextToken;

        return response()
            ->json(['user'=>$user,'token'=>$token],201);

    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión y obtener token de autenticación",
     *     tags={"CRUD Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales de inicio de sesión",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="sifaqes@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hola NombreUsuario"),
     *             @OA\Property(property="access_token", type="string", example="token_de_autenticacion")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Detalles de inicio de sesión no válidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detalles de inicio de sesión no válidos")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Detalles de inicio de sesión no válidos'], 401);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hola ' . $user->name,
            'access_token' => $token,
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Cerrar sesión y revocar token de autenticación",
     *     tags={"CRUD Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cierre de sesión exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     *
     * Cerrar sesión y revocar token de autenticación.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso'], 200);
    }



    /**
     * @OA\Put(
     *     path="/api/update",
     *     summary="Actualizar información del usuario",
     *     tags={"CRUD Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="NuevoNombre"),
     *             @OA\Property(property="email", type="string", example="nuevo@dominio.com"),
     *             @OA\Property(property="password", type="string", example="nuevacontrasena")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Errores de validación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     *
     * Actualizar la información del usuario.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if($request->get('password')){
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();
        return response()
            ->json(['message'=>'Usuario actualizado'],201);
    }


    /**
     * @OA\Delete(
     *     path="/api/delete",
     *     summary="Delete user",
     *     description="Delete a user and revoke access tokens",
     *     operationId="deleteUser",
     *     tags={"CRUD Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="sifaqes@gmail.com", description="Email of the user to be deleted"),
     *                 @OA\Property(property="password", type="string", example="12345678", description="New password for the user (optional)"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         ),
     *     ),
     * )
     */

    public function delete(): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado'], 201);
    }


}
