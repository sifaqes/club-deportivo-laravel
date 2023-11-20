<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Socio;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SociosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {

            $socio = Socio::all('id','nombre')->sortBy('id');

            return response()->json(['socios' =>  $socio], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $request->validate([
                'nombre' => 'required|unique:socios',
                'apellidos' => 'required|unique:socios',
            ]);

            $socio = new Socio();

            $this->$socio->nombre = $request->input('nombre');
            $this->$socio->apellidos = $request->input('apellidos');

            $socio->save();

            return response()->json(['message' => 'Socio creado correctamente'], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $socios)
    {
        //
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
            'socioId' => 'required|integer|exists:socios,id',
            'nombre' => 'required',
            'apellidos' => 'required',
        ]);

        $socioId = $this->$request->socioId;
        $nombre = $this->$request->nombre;
        $apellidos = $this->$request->apellidos;

        try {

                $socio = Socio::find($socioId);
                $socio->nombre = $nombre;
                $socio->apellidos = $apellidos;

                $socio->save();

                return response()->json(['message' => 'Socio actualizado correctamente'], 201);
        }   catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:socios,id',
        ]);

        $id = $request->input('id');

        Reserva::where('socio_id', $id)->delete();

        Socio::destroy(1);

        Socio::where('id', $id)->delete();

        return response()->json(['message' => 'Socio eliminado correctamente'], 201);
    }
}
