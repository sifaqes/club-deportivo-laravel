<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
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
        $deportes = Deporte::all();

        $listaDeportes = $deportes->map(function ($deporte) {
            return $deporte->deporte;
        });

        return response()->json(['deportes' => $listaDeportes ]);
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

        $deporte = new Deporte();

        $deporte->deporte = $request->input('deporte');

        $deporte->save();

        return response()->json(['message' => 'Deporte creado correctamente'], 201);
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
            'nuevoDeporte' => 'required|string|unique:deportes,deporte',
        ]);

        $deporte = $request->input('deporte');
        $nuevoDeporte = $request->input('nuevoDeporte');

        $deporteSql = Deporte::where('deporte', $deporte)->first();

        try {
            $deporteSql->deporte = $nuevoDeporte;
            $deporteSql->save();
            return response()->json(['message' => 'Deporte '.$deporte.' modificado correctamente '], 201);
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
            'deporte' => 'required|string|exists:deportes,deporte',
        ]);

        $deporte= $request->input('deporte');

        $deporteSql = Deporte::where('deporte', $deporte)->first();

        try {
            $deporteSql->delete();
            return response()->json(['message' => 'Deporte '.$deporte.' borrado correctamente '], 201);

        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
       }
}
