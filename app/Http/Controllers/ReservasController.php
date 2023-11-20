<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * Class ReservasController
 * @package App\Http\Controllers
 * @OA\Server(url="http://localhost:8000")
 */

class ReservasController extends Controller
{

    /**
     * Buscar una lista de recursos basada en la fecha especificada.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON con la lista de reservas.
     *
     * @OA\Post(
     *     path="/api/buscador",
     *     tags={"Reservas"},
     *     summary="buscar reservas",
     *     description="Muestra una busqueda de reservas basada en la fecha especificada ex 2023-11-20",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         required=true,
     *         description="Fecha en formato 'Y-m-d' (ejemplo: '2023-01-01')",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="reservas", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="reserva", type="array", @OA\Items(
     *                     @OA\Property(property="socio", type="string"),
     *                     @OA\Property(property="pista", type="string"),
     *                     @OA\Property(property="deporte", type="string"),
     *                     @OA\Property(property="fecha", type="string", format="date"),
     *                     @OA\Property(property="horaInicio", type="string", format="time"),
     *                     @OA\Property(property="horaFin", type="string", format="time")
     *                 ))
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo fecha debe tener el formato 'Y-m-d'.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */

    public function index(Request $request): JsonResponse
    {

        $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
        ]);

        $fecha = $request->input('fecha');

        try {

            $reservas = Reserva::all()->where('fecha', $fecha);
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
     * Almacena un nuevo recurso de reserva en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito del almacenamiento.
     *
     * @OA\Post(
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     summary="Crear reserva",
     *     description="Almacena un nuevo recurso de reserva en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="pista_id", type="integer", example="1"),
     *             @OA\Property(property="horaInicio", type="string", example="08:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="reserva", type="array", @OA\Items(
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="socio_id", type="integer"),
     *                 @OA\Property(property="pista_id", type="integer"),
     *                 @OA\Property(property="socio", type="string"),
     *                 @OA\Property(property="pista", type="string"),
     *                 @OA\Property(property="deporte", type="integer"),
     *                 @OA\Property(property="fecha", type="string", format="date"),
     *                 @OA\Property(property="horaInicio", type="string", format="time"),
     *                 @OA\Property(property="horaFin", type="string", format="time")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La hora de inicio debe estar entre las 08:00 y las 22:00",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La hora de inicio debe estar entre las 08:00 y las 22:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="No puedes realizar más de 3 reservas en un mismo día.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No puedes realizar más de 3 reservas en un mismo día.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'pista_id' => 'required|integer|exists:pistas,id',
            'horaInicio' => 'required|date_format:H:00'
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
        if ($reservasDiarias >= env('MAX_RESERVAS_DIA', 3)) {
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
     * Actualiza el recurso de reserva especificado en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la actualización.
     *
     * @OA\Patch(
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     summary="Actualizar reserva",
     *     description="Actualiza el recurso de reserva especificado en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="reservaId", type="integer", example="1"),
     *             @OA\Property(property="nuevaHora", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva actualizada a 10:00 con éxito.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró ninguna reserva para actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se encontró ninguna reserva para actualizar.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo nuevaHora debe tener el formato 'H:00'.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     )
     * )
     *
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

        //$user = Auth::user();

        $reserva = Reserva::where('id', $reservaId)->first();
        if (!$reserva) {
            return response()->json(['error' => 'No se encontró ninguna reserva para actualizar.'], 404);
        }

        //$dateTime = DateTime::createFromFormat("H:i:s", $reserva->horaInicio);
        // en caso que la reserva sea anterior a la hora actual no se puede modificar antes 2 horas
        //$horaReserva  = $dateTime->format("H:i");
        //$horaActual = date('H:00');

        try {

            $reserva->horaInicio = $nuevaHora;

            $reserva->save();

            return response()->json(['message' =>   'Reserva actualizada a '.$nuevaHora.' con éxito.'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Elimina el recurso de reserva especificado del almacenamiento.
     *
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la eliminación.
     *
     * @OA\Delete(
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     summary="Eliminar reserva",
     *     description="Elimina el recurso de reserva especificado del almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Response(
     *         response=201,
     *         description="Reserva eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva eliminada con éxito.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró ninguna reserva para eliminar",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se encontró ninguna reserva para eliminar.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     )
     * )
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

            return response()->json(['message' => 'Reserva eliminada con éxito.'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
