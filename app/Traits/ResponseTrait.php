<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{

    /**
     * Generate data type response.
     *
     * Returns the success data and message if there is any error
     *
     * @param object $data
     * @return JsonResponse
     */
    public function responseData($data = [], $status_code = JsonResponse::HTTP_OK) : JsonResponse
    {
        return response()->json($data, $status_code);
    }

    /**
     * Generate success type response.
     *
     * Returns the success data and message if there is any error
     *
     * @param object $data
     * @param string $message
     * @param integer $status_code
     * @return JsonResponse
     */
    public function responseSuccess($data = [], $message = "Sucesso", $status_code = JsonResponse::HTTP_OK)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'errors' => null,
            'data' => $data,
        ], $status_code);
    }

    /**
     * Generate Error response.
     *
     * Returns the errors data if there is any error
     *
     * @param object $errors
     * @return JsonResponse
     */
    public function responseError($errors, $message = 'Dados inválidos', $status_code = JsonResponse::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => null,
        ], $status_code);
    }
}
