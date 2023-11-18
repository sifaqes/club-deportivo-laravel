<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PistasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $deportesConPistas = [];

        $deportes = Deporte::all();

        foreach ($deportes as $deporte) {

            $pistasDisponibles = Pista::where('deporte_id', $deporte->id)
                ->where('disponibilidad', true)
                ->pluck('pista');


            $deportesConPistas[] = [
                'deporte' => $deporte->deporte,
                'pistas_disponibles' => $pistasDisponibles,
            ];
        }

        return response()->json(['deportes_con_pistas' => $deportesConPistas], 200);
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
    public function show(Pistas $pistas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pistas $pistas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pistas $pistas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pistas $pistas)
    {
        //
    }
}
