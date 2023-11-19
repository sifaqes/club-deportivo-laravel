<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{

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
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso'], 200);
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
    public function edit(Socios $socios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();
        $user->email = $request->get('email');

        if($request->get('password')){
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();
        return response()
            ->json(['message'=>'Usuario actualizado'],201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socios $socios)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado'], 201);
    }
}

