<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:57
 */

namespace Oacc\Controller;

use Oacc\Service\JsonEncoder;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package Oacc\Controller
 */
class AdminController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(Request $request, Response $response, $args = [])
    {
        return JsonEncoder::setSuccessJson(
            $response,
            [
                'title' => 'JWT Auth Dashboard',
                'text' => '<p>Welcome...</p>',
            ]
        );
    }
}
