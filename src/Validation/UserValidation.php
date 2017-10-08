<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Oacc\Entity\User;
use Oacc\Validation\Exceptions\ValidationException;

class UserValidation implements EventSubscriber
{

    public function validate(User $user) {
        if(empty($user->getUsername())) {
            throw new ValidationException("FUCK");
        }
    }

    public function prePersist(User $user) {
        $this->validate($user);
    }

    public function preUpdate(User $user) {
        $this->validate($user);
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