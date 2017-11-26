<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Oacc\Exceptions\ValidationException;
use Oacc\Utility\Error;

abstract class Validation
{

    protected $error;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->error = new Error();
        $this->entityManager = $entityManager;
    }

    abstract public function validate($entity);

    /**
     * @throws ValidationException
     */
    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }

    /**
     * @param $criteria
     * @param $entityName
     * @param null $entityId
     * @return bool
     */
    protected function fieldIsAvailable($criteria, $entityName, $entityId = null)
    {
        $entityRepository = $this->entityManager->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);
        switch ($entity) {
            case null:
                return true;
            case $entity->getId() === $entityId:
                return true;
            default:
                return !$entity;
        }
    }
}
