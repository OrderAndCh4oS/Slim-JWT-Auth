<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:57
 */

namespace Oacc\Controller;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Oacc\Service\UserService;
use Oacc\Transformer\UserTransformer;
use Oacc\Utility\JsonEncoder;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 * @package Oacc\Controller
 */
class UserController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Oacc\Exceptions\ValidationException
     */
    public function getAction(Request $request, Response $response)
    {
        $user = (new UserService($this->container))->getUserFromTokenClaim($request);
        $userItem = new Item($user, new UserTransformer);
        /** @var Manager $fractal */
        $fractal = $this->container->fractal;

        return JsonEncoder::setSuccessJson(
            $response,
            null,
            ['user' => $fractal->createData($userItem)->toArray()]
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postAction(Request $request, Response $response)
    {
        return (new UserService($this->container))->create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Oacc\Exceptions\ValidationException
     */
    public function putAction(Request $request, Response $response)
    {
        return (new UserService($this->container))->update($request, $response);
    }
}
