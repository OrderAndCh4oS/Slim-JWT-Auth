<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:57
 */

namespace Oacc\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PageController
 * @package Oacc\Controller
 */
class PageController extends Controller
{
    public function indexAction(Request $request, Response $response, $args = [])
    {
        return $this->setSuccessJson(
            $response,
            [
                'title' => 'JWT Auth',
                'text' => '<p>Register and login</p>',
            ]
        );
    }
}
