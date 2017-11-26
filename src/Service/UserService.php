<?php

namespace Oacc\Service;

use Doctrine\ORM\EntityRepository;
use Oacc\Entity\User;
use Oacc\Exceptions\ValidationException;
use Oacc\Listener\HashPasswordListener;
use Oacc\Listener\ValidationListener;
use Oacc\Utility\Error;
use Oacc\Utility\JsonEncoder;
use Oacc\Utility\Jwt;
use Oacc\Validation\UserValidation;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class UserService
{
    /**
     * @var Container
     */
    protected $container;
    private $errors;

    /**
     * Authentication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->errors = new Error();
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
        if (!$this->checkDataIsNotEmpty($data)) {
            return JsonEncoder::setErrorJson($response, $this->errors->getErrors());
        }
        $this->addValidationListener($data);
        $this->addPasswordEncoderListener();
        try {
            /** @var User $user */
            $user = $this->createUser($data);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        }

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
        if (!$this->checkDataIsNotEmpty($data)) {
            return JsonEncoder::setErrorJson($response, $this->errors->getErrors());
        }
        $this->addValidationListener($data);
        $this->addPasswordEncoderListener();
        try {
            /** @var User $user */
            $user = $this->updateUser($data, $user);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        }

        return JsonEncoder::setSuccessJson($response, [$user->getUsername().' updated successfully']);
    }

    /**
     * @param $data
     * @return bool
     */
    private function checkDataIsNotEmpty($data)
    {
        if (empty($data)) {
            $message = 'No valid data. Post username, email, password and password_confirm as json';
            $this->errors->addError('validation', $message);
        }

        return !$this->errors->hasErrors();
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
            new ValidationListener(new UserValidation($data['password_confirm'], $this->container->em))
        );
    }

    private function addPasswordEncoderListener()
    {
        $this->container->em->getEventManager()->addEventListener(
            ['prePersist', 'preUpdate'],
            new HashPasswordListener()
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
