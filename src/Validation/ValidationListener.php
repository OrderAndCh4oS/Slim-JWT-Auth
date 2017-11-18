<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;

/**
 * Class ValidationListener
 * @package Oacc\Validation
 */
abstract class ValidationListener implements EventSubscriber
{

    /**
     * @var Error
     */
    protected $error;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserValidationListener constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->error = new Error();
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidationException
     */
    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }

    protected function fieldIsAvailable($criteria, $entityName)
    {
        $entityRepository = $this->entityManager->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);

        return !$entity;
    }
}
