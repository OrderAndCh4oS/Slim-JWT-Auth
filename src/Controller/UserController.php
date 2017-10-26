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
use Oacc\Entity\User;
use Oacc\Service\JsonEncoder;
use Oacc\Transformer\UserTransformer;
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
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(Request $request, Response $response, $args = [])
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
}
