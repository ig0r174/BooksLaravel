<?php

namespace App\Traits;

use Response;

trait ApiResponse
{
    /**
     * @param $result
     * @param $message
     * @param $code
     * @return mixed
     */
    public function sendResponse($result, $message, $code) {

        return Response::json(self::makeResponse($message, $result), $code);
    }

    /**
     * @param $message
     * @param $code
     * @return mixed
     */
    public function sendEmptyResponse($message, $code) {

        return Response::json(self::makeEmptyResponse($message), $code);
    }

    /**
     * @param $error
     * @param int $code
     * @param array $data
     * @return mixed
     */
    public function sendError($error, $code = 400, $data = []) {

        return Response::json(self::makeError($error, $data), $code);
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];
    }

    public static function makeEmptyResponse($message)
    {
        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
