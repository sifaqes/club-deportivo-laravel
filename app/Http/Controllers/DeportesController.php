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
            'deporte' => 'required|string|exists:deportes,deporte',
            'nuevoDeporte' => 'required|string',
        ]);

        $deporte = $request->input('deporte');
        $nuevoDeporte = $request->input('nuevoDeporte');

        try {
            $deporteSql = Deporte::where('deporte', $deporte)->first();

            $deporteSql->deporte = $nuevoDeporte;

            $deporteSql->where('deporte', $deporte)->update(['deporte' => $nuevoDeporte]);

            $deporteSql->save();

            return response()->json(['message' => 'Deporte '.$deporteSql->deporte.' modificado correctamente '], 201);
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
            'deporte' => 'required|string|exists:deportes,deporte',
        ]);

        $deporte = $request->input('deporte');

        $deportes = Deporte::where('deporte', $deporte)->first();

        if (!$deportes) {
            return response()->json(['message' => 'Deporte no encontrado'], 404);
        }

        $deporteId = $deportes->getAttributes()['id'];

        $pistas = Pista::where('deporte_id', $deporteId)->get();

        $id =[];

        foreach ($pistas as $pista) {
            $id = $pista->getAttributes()['id'];
        }

        //$deportes->delete();

        return response()->json(['message' => $id.' method not allow.'], 201);
    }
}
