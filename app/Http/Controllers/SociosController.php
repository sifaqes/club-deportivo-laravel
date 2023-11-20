<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Socio;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use function PHPUnit\Framework\isFalse;

/**
 * Class SociosController
 * @package App\Http\Controllers
 * @OA\Server(url="http://localhost:8000")
 */

class SociosController extends Controller
{
    /**
     * Muestra un listado de los recursos de socios.
     *
     * @return JsonResponse Devuelve una respuesta JSON con el listado de socios.
     *
     * @OA\Get(
     *     path="/api/socios",
     *     tags={"Socios"},
     *     summary="Listar socios",
     *     description="Muestra un listado de los recursos de socios.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Response(
     *         response=201,
     *         description="Listado de socios obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="socios", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nombre", type="string")
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

            $socio = Socio::all('id','nombre')->sortBy('id');

            return response()->json(['socios' =>  $socio], 201);

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
     * Almacena un nuevo recurso de socio en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito del almacenamiento.
     *
     * @OA\Post(
     *     path="/api/socios",
     *     tags={"Socios"},
     *     summary="Crear socio",
     *     description="Almacena un nuevo recurso de socio en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="John"),
     *             @OA\Property(property="apellidos", type="string", example="Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Socio creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Socio creado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error de validación")
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
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
        ]);
        try {

            $nombre = $request->input('nombre');
            $apellidos = $request->input('apellidos');

            $socio = new Socio();

            $socio->nombre =  $nombre;
            $socio->apellidos = $apellidos;

            $socio->save();

            return response()->json(['message' => 'Socio creado correctamente'], 201);

        }catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $socios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $socios)
    {
        //
    }

    /**
     * Actualiza el recurso de socio especificado en el almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la actualización.
     *
     * @OA\Put(
     *     path="/api/socios",
     *     tags={"Socios"},
     *     summary="Actualizar socio",
     *     description="Actualiza el recurso de socio especificado en el almacenamiento.",
     *          security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="socioId", type="integer", example="1"),
     *             @OA\Property(property="nombre", type="string", example="John"),
     *             @OA\Property(property="apellidos", type="string", example="Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Socio actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Socio actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error de validación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Socio no encontrado")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'socioId' => 'required|integer|exists:socios,id',
            'nombre' => 'required',
            'apellidos' => 'required',
        ]);

        $socioId = $request->socioId;
        $nombre = $request->nombre;
        $apellidos = $request->apellidos;

        try {

                $socio = Socio::find($socioId);
                $socio->nombre = $nombre;
                $socio->apellidos = $apellidos;

                $socio->save();

                return response()->json(['message' => 'Socio actualizado correctamente'], 201);
        }   catch (Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }

    }


    /**
     * Elimina el recurso de socio especificado del almacenamiento.
     *
     * @param Request $request
     * @return JsonResponse Devuelve una respuesta JSON indicando el éxito de la eliminación.
     *
     * @OA\Delete(
     *     path="/api/socios",
     *     tags={"Socios"},
     *     summary="Eliminar socio",
     *     description="Elimina el recurso de socio especificado del almacenamiento.",
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
     *         description="Socio eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Socio eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error de validación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Socio no encontrado")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:socios,id',
        ]);

        $id = $request->input('id');

        if (Reserva::where('socio_id', $id)->exists()) {
            Reserva::where('socio_id', $id)->delete();
        }

        Socio::destroy($id);

        Socio::where('id', $id)->delete();

        return response()->json(['message' => 'Socio eliminado correctamente'], 201);
    }
}
