<?php

function successResponse($data, $code = 200) {
    return response()->json([
        'success' => true,
        'data' => $data
    ], $code);
}

function errorReponse($message, $code = 500) {
    return response()->json([
        'success' => false,
        'data' => $message
    ], $code);
}
