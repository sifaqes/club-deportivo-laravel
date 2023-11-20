<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 *  Class AuthController
 * @OA\Info(
 *      version="1.0.0",
 *      title="Club deportivo API",
 *      description="API Reservas club deportivo, es  una pureba tecnica para la empresa NETSNITS ",
 *)
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Reservas"
 * )
 * @OA\SecurityScheme(
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="bearerAuth",
 *  )
 * **/

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para registrar un nuevo usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="siphax@zerrouki.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
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
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|confirmed|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
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
     *     tags={"Usuarios"},
     *     summary="Iniciar sesión",
     *     description="Inicia sesión y devuelve un token de acceso.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="siphax@zerrouki.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hola usuario@example.com"),
     *             @OA\Property(property="access_token", type="string", example="token_de_acceso_generado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales no válidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detalles de inicio de sesión no válidos")
     *         )
     *     ),
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
            'message' => 'Hola ' . $user->email,
            'access_token' => $token,
        ], 201);
    }

    /**
     * Cierra la sesión del usuario revocando todos los tokens de acceso.
     *
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito del cierre de sesión.
     *
     * @return JsonResponse
     * @throws AuthorizationException Si el usuario no está autenticado.
     *
     * @OA\Get(
     *     path="/api/logout",
     *     tags={"Usuarios"},
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario revocando todos los tokens de acceso.",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=201,
     *         description="Cierre de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cierre de sesión exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No se pudo cerrar sesión. Usuario no autenticado.")
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Cierre de sesión exitoso'], 201);
    }

    /**
     * Muestra una lista de recursos.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON con la información del usuario autenticado.
     *
     * @OA\Get(
     *     path="/api/perfil",
     *     tags={"Usuarios"},
     *     summary="Listar recursos",
     *     description="Muestra una lista de recursos.",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", description="Información del usuario autenticado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autorizado para acceder a este recurso.")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->User();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $socios)
    {
        //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la actualización.
     *
     * @OA\Put(
     *     path="/api/update",
     *     tags={"Usuarios"},
     *     summary="Actualizar recurso",
     *     description="Actualiza el recurso especificado en el almacenamiento.",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="siphax@zerrouki.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="newPassword", type="string", example="nueva_contraseña")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contraseña actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Contraseña actualizada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Contraseña actual incorrecta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El formato de correo electrónico no es válido.")
     *         )
     *     )
     * )
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'newPassword' => 'required|string|min:8',
            ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $newPassword = $request->input('newPassword');


        $user = auth()->user();
        $pass = $user->getAuthPassword();
        if (!Hash::check($password, $pass)) {
            return response()->json(['error' => 'Contraseña actual incorrecta'], 401);
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada exitosamente']);
    }


    /**
     * Elimina el recurso especificado del almacenamiento.
     *
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la eliminación.
     *
     * @OA\Delete(
     *     path="/api/delete",
     *     tags={"Usuarios"},
     *     summary="Eliminar recurso",
     *     description="Elimina el recurso especificado del almacenamiento.",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=201,
     *         description="Usuario eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se pudo eliminar el usuario. Usuario no autenticado.")
     *         )
     *     )
     * )
     */
    public function destroy(): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado'], 201);
    }
}

