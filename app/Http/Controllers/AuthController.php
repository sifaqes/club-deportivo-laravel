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
 *      title="API club deportivo",
 *      description="API Reservas club deportivo"
 *)
 *
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Reservas"
 * )
 * **/
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"CRUD Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para registrar un nuevo usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="NombreUsuario"),
     *             @OA\Property(property="email", type="string", format="email", example="usuario@dominio.com"),
     *             @OA\Property(property="password", type="string", example="contrasena"),
     *             @OA\Property(property="password_confirmation", type="string", example="contrasena")
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

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Cierre de sesión exitoso'], 201);
    }

    /**
     * Display a listing of the resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado'], 201);
    }
}

