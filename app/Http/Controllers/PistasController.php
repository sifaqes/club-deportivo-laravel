<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PistasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {

            $pista = Pista::all('id','pista')->sortBy('id');

            return response()->json(['pista' =>  $pista], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
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
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'pista' => 'required|unique:pistas',
        ]);

        try {

            $pista = $request->input('pista');

            $pistaSql = [
                'pista'=>$pista,
            ];

            Pista::factory()->create($pistaSql);

            return response()->json(['message' => 'Pista '. $pistaSql['pista'].' creada correctamente'], 201);

        }   catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $pistas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $pistas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request -> validate([
            'id' => 'required|integer|exists:pistas,id',
            'pista' => 'required|max:50',
        ]);
        $id = $request->input('id');
        $pistaNueva = $request->input('pista');

        $pista = Pista::all()->where('id', $id)->first();

        try {
            if (!empty($pista)){
                $pista->pista = $pistaNueva;
                $pista->save();
                return response()->json(['message' => 'Pista '.$pista['pista'].' actualizada correctamente'], 201);
            }else{
                return response()->json(['message' => 'No existe la pista'], 201);
            }
        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:pistas,id',
        ]);

        $id = $request->input('id');

        $pistas = Pista::all();
        $deportes = Deporte::all();

        $deporte = $deportes->where('id', $id)->first();
        $pista = $pistas->where('deporte_id', $id)->first();

        try {
            if (!empty($pista) && !empty($deporte)){
                $pista->delete();
                $deporte->delete();
                return response()->json(['message' =>  $pista['pista'].' de deporte '.$deporte['deporte'].' borrada correctamente'], 201);
            }else{
                return response()->json(['message' => 'No existe la pista o el deporte'], 201);
            }
        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
