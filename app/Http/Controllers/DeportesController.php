<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use App\Models\Pista;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * Class DeportesController
 * @package App\Http\Controllers
 * @OA\Server(url="http://localhost:8000")
 */

class DeportesController extends Controller
{
    /**
     * Muestra una lista de recursos.
     *
     * @return JsonResponse Devuelve una respuesta JSON con la lista de deportes.
     *
     * @OA\Get(
     *     path="/api/deportes",
     *     tags={"Deportes"},
     *     summary="Listar deportes",
     *     description="Muestra una lista de deportes.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Response(
     *         response=201,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="deportes", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="deporte", type="string")
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
     * Almacena un nuevo recurso en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito del almacenamiento.
     *
     * @OA\Post(
     *     path="/api/deportes",
     *     tags={"Deportes"},
     *     summary="Crear deporte",
     *     description="Almacena un nuevo recurso de deporte en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="deporte", type="string", example="Nuevo Deporte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Deporte creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deporte Nuevo Deporte creado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo deporte debe ser único.")
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
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la actualización.
     *
     * @OA\Put(
     *     path="/api/deportes",
     *     tags={"Deportes"},
     *     summary="Actualizar deporte",
     *     description="Actualiza el recurso de deporte especificado en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="nuevoDeporte", type="string", example="Nuevo Deporte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Deporte modificado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nuevo Deporte modificado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deporte no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El campo nuevoDeporte debe ser una cadena con un máximo de 50 caracteres.")
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
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la eliminación.
     *
     * @OA\Delete(
     *     path="/api/deportes",
     *     tags={"Deportes"},
     *     summary="Eliminar deporte",
     *     description="Elimina el recurso de deporte especificado en el almacenamiento y las pistas asociadas.",
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
     *         description="Deporte eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deporte eliminado correctamente && Pistas eliminadas correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Deporte no eliminado correctamente")
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
