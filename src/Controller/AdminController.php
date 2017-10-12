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
use Respect\Validation\Validator as v;

class AdminController extends Controller
{
    public function indexAction(Request $request, Response $response, $args = []) {
        echo $this->session->user;die;
        return $this->view->render($response, 'index.twig');
    }
}