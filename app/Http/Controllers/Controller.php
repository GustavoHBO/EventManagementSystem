<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Método para retornar uma resposta de sucesso.
     * @param $data - Dados a serem retornados.
     * @param  string  $message - Mensagem de sucesso.
     * @param  int  $statusCode - Código de status HTTP.
     * @return JsonResponse - Resposta em JSON.
     */
    protected function sendSuccessResponse($data, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Método para retornar uma resposta de erro.
     * @param $message - Mensagem de erro.
     * @param  int  $statusCode - Código de status HTTP.
     * @return JsonResponse - Resposta em JSON.
     */
    protected function sendErrorResponse($message, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }
}
