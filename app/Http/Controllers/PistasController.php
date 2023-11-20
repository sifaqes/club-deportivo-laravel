<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * Class PistasController
 * @package App\Http\Controllers
 * @OA\Server(url="http://localhost:8000")
 */

class PistasController extends Controller
{
    /**
     * Muestra una lista de recursos.
     *
     * @return JsonResponse Devuelve una respuesta JSON con la lista de pistas.
     *
     * @OA\Get(
     *     path="/api/pistas",
     *     tags={"Pistas"},
     *     summary="Listar pistas",
     *     description="Muestra una lista de pistas.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Response(
     *         response=201,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="pistas", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="pista", type="string")
     *             ))
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
     * Almacena un nuevo recurso en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito del almacenamiento.
     *
     * @OA\Post(
     *     path="/api/pistas",
     *     tags={"Pistas"},
     *     summary="Crear pista",
     *     description="Almacena un nuevo recurso de pista en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="pista", type="string", example="Nueva Pista")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pista creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pista Nueva Pista creada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo pista debe ser único.")
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
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la actualización.
     *
     * @OA\Put(
     *     path="/api/pistas",
     *     tags={"Pistas"},
     *     summary="Actualizar pista",
     *     description="Actualiza el recurso de pista especificado en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="pista", type="string", example="Nueva Pista")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pista actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nueva Pista actualizada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe la pista",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No existe la pista")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo pista debe tener un máximo de 50 caracteres.")
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
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la eliminación.
     *
     * @OA\Delete(
     *     path="/api/pistas",
     *     tags={"Pistas"},
     *     summary="Eliminar pista",
     *     description="Elimina el recurso de pista y su deporte asociado en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pista y deporte eliminados correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pista [Nombre de la pista] de deporte [Nombre del deporte] eliminada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe la pista o el deporte",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No existe la pista o el deporte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo id debe ser un número entero válido.")
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
