<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SociosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $request = Socio::all();
        return response()->json(['socios' => $request], 200);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Socios $socios)
    {
        //
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
    public function update(Request $request, Socios $socios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socios $socios)
    {
        //
    }
}
