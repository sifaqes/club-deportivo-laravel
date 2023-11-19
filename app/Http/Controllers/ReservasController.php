<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use DateTime;
use Exception;
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
        try {

            $reservas = Reserva::all();

            $result = $reservas->map(function ($reserva) {
                return [
                    'id' => $reserva->id, 'reserva' => ['socio' => $reserva->socio,
                        'pista' => $reserva->pista,
                        'deporte' => $reserva->deporte,
                        'fecha' => $reserva->fecha,
                        'horaInicio' => $reserva->horaInicio,
                        'horaFin' => $reserva->horaFin,],
                ];
            });

            return response()->json(['reservas' =>  $result], 201);
        }   catch (Exception $e){

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
            'pista_id' => ['required',
                Rule::unique('reservas')->where(function ($query) use ($request) {
                    $horaInicio =  $request->Get('horaInicio');
                    return $query->where('horaInicio', $horaInicio);
                }),
            ],
            'horaInicio' => 'required|date_format:H:00',
        ]);

        // inputs
        $pistaId = $request->input('pista_id');
        $hora = $request->horaInicio;

        //User
        $userId = Auth::id();

        // interval de tiempo
        $horaInicio = intval(substr($hora, 0, 2));
        if ($horaInicio < 8 || $horaInicio > 22) {
            return response()->json(['error' => 'La hora de inicio debe estar entre las 08:00 y las 22:00'], 400);
        }

        // comprobar que no se superan las 3 reservas diarias
        $reservasDiarias = Reserva::where('socio_id', $userId)->whereDate('horaInicio', now()->toDateString())->count();
        if ($reservasDiarias >= 3) {
            return response()->json(['error' => 'No puedes realizar más de 3 reservas en un mismo día.'], 422);
        }

        try {
            //socio
            $socios = Socio::all()->find($pistaId);
            $socioId =  $socios->id;
            $socioNombre = $socios->nombre;
            $socioApellidos = $socios->apellidos;

            $pista = Pista::where('id', $pistaId)->first()->pista;
            $pistaId = Pista::where('id', $pistaId)->first()->deporte_id;

            $deporte = Deporte::where('id', $pistaId)->first();
            $deporteId = $deporte->id;

            $fecha = now()->toDateString();

            $horaInicio = $request->horaInicio;
            $horaFin = Carbon::createFromFormat('H:i', $horaInicio)->addHour()->format('H:i');

            $reserva = [
                'user_id'=>$userId,
                'socio_id' => $socioId,
                'pista_id' => $pistaId,
                'socio' => $socioNombre . ' ' . $socioApellidos,
                'pista'  => $pista,
                'deporte' => $deporteId,
                'fecha' => $fecha,
                'horaInicio' => $horaInicio,
                'horaFin' => $horaFin,
            ];

            Reserva::factory()->create($reserva);

            return response()->json(['reserva' => $reserva ], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $reservas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $reservas)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $reservaId
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {

        $request->validate([
            'reservaId' => 'required|integer|exists:reservas,id',
            'nuevaHora' => 'required|date_format:H:00',
        ]);

        $reservaId = $request->reservaId;
        $nuevaHora = $request->nuevaHora;

        $user = Auth::user();

        $reserva = Reserva::where('id', $reservaId)->where('socio_id', $user->getAuthIdentifier())->first();
        if (!$reserva) {
            return response()->json(['error' => 'No se encontró ninguna reserva para actualizar.'], 404);
        }

        $dateTime = DateTime::createFromFormat("H:i:s", $reserva->horaInicio);

        // en caso que la reserva sea anterior a la hora actual no se puede modificar antes 2 horas
        $horaReserva  = $dateTime->format("H:i");
        $horaActual = date('H:00');

        try {

            $reserva->horaInicio = $nuevaHora;

            $reserva->save();

            return response()->json(['message' =>   'Reserva actualizada a '.$nuevaHora.' con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {

        $user = Auth::user();

        $reserva = Reserva::where('socio_id', $user->getAuthIdentifier())->latest()->first();

        if (!$reserva) {
            return response()->json(['error' => 'No se encontró ninguna reserva para eliminar.'], 404);
        }

        try {

            $reserva->delete();

            return response()->json(['message' => 'Reserva eliminada con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
