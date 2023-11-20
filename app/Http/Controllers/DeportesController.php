<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeportesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        try {

            $deportes = Deporte::all('id','deporte')->sortBy('id');

            return response()->json(['deportes' =>  $deportes], 201);

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
        $request->validate([

            'deporte' => 'required|unique:deportes',

        ]);

        try {

            $deporte = $request->input('deporte');

            $deporteSql = [
                'deporte'=>$deporte,
            ];

            Deporte::factory()->create($deporteSql);

           return response()->json(['message' => 'Deporte '.$deporteSql['deporte'].' creado correctamente'], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $deportes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $deportes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:deportes,id',
            'nuevoDeporte' => 'required|string|max:50',
        ]);

        $id = $request->input('id');
        $nuevoDeporte = $request->input('nuevoDeporte');

        $deportes = Deporte::where('id', $id)->first();

        try {
            if (!empty($deportes)){
                $deportes->deporte = $nuevoDeporte;
                Deporte::where('id', $id)->update(['deporte' => $nuevoDeporte]);
                $deportes->save();
                return response()->json(['message' => $nuevoDeporte.'Deporte modificado correctamente'], 201);
            }else{
                return response()->json(['message' => 'Deporte no encontrado'], 404);
            }
        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     * @property string $id
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:deportes,id',
        ]);

        $id = $request->input('id');

        if(Pista::where('deporte_id', $id)->exists()){

            Pista::where('deporte_id', $id)->delete();

            Deporte::where('id', $id)->delete();

            return response()->json(['message' => 'Deporte eliminado correctamente && Pista eliminado correctamente'], 201);
        }

        return response()->json(['error' => 'Deporte no eliminado correctamente'], 404);

    }
}
