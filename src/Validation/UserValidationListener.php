<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;

class UserValidationListener implements EventSubscriber
{
    /**
     * @var EntityRepository
     */
    private $em;
    /**
     * @var Error
     */
    private $error;

    /**
     * UserValidationListener constructor.
     * @param EntityManager $em
     * @param Error $error
     */
    public function __construct(EntityManager $em, Error $error)
    {
        $this->em = $em;
        $this->error = $error;
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function validate(User $user)
    {
        if (empty($user->getUsername())) {
            $this->error->setError('username', 'Please enter a username');
        }
        if (strlen($user->getUsername()) > 80) {
            $this->error->setError('username', 'Username is too long');
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
        if (empty($user->getPlainPassword())) {
            $this->error->setError('password', 'Please enter a password');
        }
        if ($this->error->hasErrors()) {
            throw new ValidationException();
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validate($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validate($entity);
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