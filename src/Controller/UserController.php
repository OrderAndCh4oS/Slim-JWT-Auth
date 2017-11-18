<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:57
 */

namespace Oacc\Controller;

use Doctrine\ORM\EntityRepository;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Oacc\Authentication\Jwt;
use Oacc\Authentication\Register;
use Oacc\Entity\User;
use Oacc\Service\JsonEncoder;
use Oacc\Transformer\UserTransformer;
use Oacc\Validation\Exceptions\ValidationException;
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
     * @return Response
     */
    public function indexAction(Request $request, Response $response)
    {
        return JsonEncoder::setSuccessJson(
            $response,
            null,
            ['hello']
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAction(Request $request, Response $response)
    {
        $token = Jwt::get($request);
        /** @var EntityRepository $userRepo */
        $userRepo = $this->container->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepo->findOneBy(['username' => $token->getClaim('username')]);
        $user = new Item($user, new UserTransformer);
        /** @var Manager $fractal */
        $fractal = $this->container->fractal;

        return JsonEncoder::setSuccessJson(
            $response,
            null,
            ['user' => $fractal->createData($user)->toArray()]
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postAction(Request $request, Response $response)
    {
        try {
            /** @var User $user */
            $response = (new Register($this->container))->register($request, $response);
        } catch (ValidationException $e) {
            $response = JsonEncoder::setErrorJson($response, $e->getErrors());
        }

        return $response;
    }
}
