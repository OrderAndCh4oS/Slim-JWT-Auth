<?php

namespace Oacc\Service;

use Slim\Http\Response;

class JsonEncoder
{
    /**
     * @param Response $response
     * @param $error_messages
     *
     * @param int $status_code
     * @return Response
     */
    public static function setErrorJson(Response $response, $error_messages, $status_code = 400)
    {
        $data = [
            'status' => 'error',
            'errors' => $error_messages,
        ];

        return $response->withJson($data, $status_code);
    }

    /**
     * @param Response $response
     * @param array $messages
     * @param array $data
     * @param int $status_code
     * @return Response
     */
    public static function setSuccessJson(Response $response, $messages = null, $data = null, $status_code = 200)
    {
        $jsonData = ['status' => 'success'];
        if ($data) {
            $jsonData['data'] = $data;
        }
        if ($messages) {
            $jsonData['messages'] = $messages;
        }

        return $response->withJson($jsonData, $status_code);
    }
}
