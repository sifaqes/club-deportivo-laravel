<?php

namespace App\Http\Controllers;

use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReservasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reservas = Reserva::all('id','socio_id', 'pista_id','socio','pista','deporte','fecha', 'hora_inicio', 'horaFin');
        return response()->json(['reservas' => $reservas], 200);
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
        // Validaciones
        $request->validate([
            'pista_id' => ['required',
                Rule::unique('reservas')->where(function ($query) use ($request) {
                    return $query->where('hora_inicio', $request->hora_inicio);
                }),
            ],
            'hora_inicio' => 'required|date_format:H:00',
        ]);

        // Comprobamos la hora de reserva valida existe
        $horaInicio = intval(substr($request->hora_inicio, 0, 2));
        if ($horaInicio < 8 || $horaInicio > 22) {
            return response()->json(['error' => 'La hora de inicio debe estar entre las 08:00 y las 22:00'], 400);
        }


        $socioId = Auth::id();


        $reservasDiarias = Reserva::where('socio_id', $socioId)
            ->whereDate('hora_inicio', now()->toDateString())->count();

        if ($reservasDiarias >= 3) {
            return response()->json(['error' => 'No puedes realizar más de 3 reservas en un mismo día.'], 422);
        }

        try {

            $email = User::findOrFail($socioId)->email;

            $socio = Socio::all()->random()->nombre;
            $pista = Pista::where('id', $request->pista_id)->first()->pista;
            $deporte = Pista::where('id', $request->pista_id)->first()->deporte->deporte;

            $fecha = now()->toDateString();
            $hora_inicio = $request->hora_inicio;
            $horaFin = Carbon::createFromFormat('H:i', $request->hora_inicio)->addHour()->format('H:i');

            $reserva = [

                'user_id'=>$socioId,
                'socio_id' => $socioId,
                'pista_id' => $request->pista_id,
                'socio' => $socio,
                'pista'  => $pista,
                'deporte' => $deporte,
                'fecha' => $fecha,
                'hora_inicio' => $hora_inicio,
                'horaFin' => $horaFin,
            ];

            Reserva::factory()->create($reserva);

            return response()->json(['reserva' => $reserva ], 201);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservas $reservas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservas $reservas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservas $reservas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservas $reservas)
    {
        //
    }
}
