<?php

namespace Oacc\Service;

use Doctrine\ORM\EntityRepository;
use Oacc\Authentication\Jwt;
use Oacc\Authentication\Password\HashPasswordListener;
use Oacc\Authentication\Password\PasswordEncoder;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class UserService
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Authentication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return User
     */
    public function getUserFromTokenClaim(Request $request)
    {
        $token = Jwt::get($request);
        /** @var EntityRepository $userRepo */
        $userRepo = $this->container->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepo->findOneBy(['username' => $token->getClaim('username')]);

        return $user;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $this->checkDataIsNotEmpty($data);
        $this->addValidationListener($data);
        $this->addPasswordEncoderListener();
        $user = $this->createUser($data);

        return JsonEncoder::setSuccessJson($response, [$user->getUsername().' registered successfully']);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function update(Request $request, Response $response)
    {
        $user = $this->getUserFromTokenClaim($request);
        $data = $request->getParsedBody();
        $this->checkDataIsNotEmpty($data);
        $this->addValidationListener($data);
        $this->addPasswordEncoderListener();
        $user = $this->updateUser($data, $user);

        return JsonEncoder::setSuccessJson($response, [$user->getUsername().' updated successfully']);
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    private function checkDataIsNotEmpty($data)
    {
        if (empty($data)) {
            throw new ValidationException(
                new Error(['No valid data. Post username, email, password and password_confirm as json'])
            );
        }
    }

    /**
     * @param $data
     * @return User
     */
    private function createUser($data): User
    {
        $user = new User();
        $this->persistUserData($data, $user);

        return $user;
    }

    /**
     * @param $data
     * @param User $user
     * @return User
     */
    private function updateUser($data, User $user): User
    {
        $this->persistUserData($data, $user);

        return $user;
    }

    /**
     * @param $data
     */
    private function addValidationListener($data)
    {
        $this->container->em->getEventManager()->addEventListener(
            ['prePersist', 'preUpdate'],
            new UserValidationListener($data['password_confirm'], $this->container->em)
        );
    }

    private function addPasswordEncoderListener()
    {
        $this->container->em->getEventManager()->addEventListener(
            ['prePersist', 'preUpdate'],
            new HashPasswordListener(new PasswordEncoder())
        );
    }

    /**
     * @param $data
     * @param $user
     */
    private function persistUserData($data, User $user)
    {
        $user->setUsername($data['username']);
        $user->setEmailAddress($data['email']);
        $user->setPlainPassword($data['password']);
        $this->container->em->persist($user);
        $this->container->em->flush();
    }
}
