<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;

class UserValidationListener extends ValidationListener
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UserValidationListener constructor.
     * @param Error $error
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Error $error)
    {
        parent::__construct($error);
        $this->em = $em;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
        $this->checkErrors();
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function validation(User $user)
    {
        if (empty($user->getUsername())) {
            $this->error->setError('username', 'Please enter a username');
        }
        if (strlen($user->getUsername()) > 80) {
            $this->error->setError('username', 'Username is too long');
        }
        if (!$this->fieldIsAvailable(['username' => $user->getUsername()], 'Oacc\Entity\User')) {
            $this->error->setError('username', 'Username is not available');
        }
        if (preg_match('/[^A-Za-z0-9_-]/', $user->getUsername()) && !empty($user->getUsername())) {
            $this->error->setError('username', 'Username can only contain letters, numbers, underscores and hyphens');
        }
        if (empty($user->getEmailAddress())) {
            $this->error->setError('email', 'Please enter an email address');
        }
        if (!filter_var($user->getEmailAddress(), FILTER_VALIDATE_EMAIL) && !empty($user->getEmailAddress())) {
            $this->error->setError('email', 'Please enter a valid email address');
        }
        if (!$this->fieldIsAvailable(['emailAddress' => $user->getEmailAddress()], 'Oacc\Entity\User')) {
            $this->error->setError('email', 'An account has already been registered');
        }
        if (empty($user->getPlainPassword())) {
            $this->error->setError('password', 'Please enter a password');
        }
    }

    private function fieldIsAvailable($criteria, $entityName)
    {
        $entityRepository = $this->em->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);

        return !$entity;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
        $this->checkErrors();
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}