<?php


namespace App\Traits;


use Illuminate\Http\Response;

trait ApiResponder
{
	//Antes a saida era :\Illuminate\Http\JsonResponse
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return \response($data, $code)->header('Content-Type', 'application/json');
    }

    public function validResponse($data, $code = Response::HTTP_OK) :\Illuminate\Http\JsonResponse
    {
        return \response()->json(['data' => $data], $code)->header('Content-Type', 'application/json');
    }

    public function errorResponse($message, $code) :\Illuminate\Http\JsonResponse
    {
        return \response()->json(['message' => $message], $code);
    }

    public function errorMessage($message, $code) :\Illuminate\Http\JsonResponse
    {
        return \response()->json($message, $code)->header('Content-Type', 'application/json');
    }
}
