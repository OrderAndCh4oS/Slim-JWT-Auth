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
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(Request $request, Response $response, $args = [])
    {
        return $this->view->render($response, 'index.twig');
    }
}
