<?php

namespace Oacc\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Utility\Error;
use Oacc\Validation\Entity\EntityValidation;

/**
 * Class ValidationListener
 * @package Oacc\Validation
 */
class ValidationListener implements EventSubscriber
{

    /**
     * @var Error
     */
    protected $error;
    /**
     * @var
     */
    private $validation;

    /**
     * UserValidationListener constructor.
     * @param EntityValidation $validation
     */
    public function __construct(EntityValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Oacc\Exceptions\ValidationException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->validation->validate($args->getEntity());
        $this->validation->checkErrors();
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Oacc\Exceptions\ValidationException
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->validation->validate($args->getEntity());
        $this->validation->checkErrors();
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
