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
    //
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

        //$Bearer = $user-> createToken('token_type')->plainTextToken;

        return response()
            ->json(['user'=>$user,'token'=>$token],201);

    }

    public function login(Request $request): JsonResponse
    {
        if(!Auth::attempt($request->only('email','password'))){
            return response()
                ->json(['message'=>'Detalles de inicio de sesión no válidos'],401);
        }

        $user = User::where('email',$request->get('email'))->firstOrFail();

        $token = $user-> createToken('auth_token')->plainTextToken;

        //$Bearer = $user-> createToken('token_type')->plainTextToken;

        return response()
            ->json([
                'message'=>'Hola '.$user->name,
                //'user'=>$user,
                'access_token'=>$token,
                //'token_type' => $Bearer
            ],201);

    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()
            ->json(['message'=>'Cierre de sesión exitoso'],201);
    }
}
