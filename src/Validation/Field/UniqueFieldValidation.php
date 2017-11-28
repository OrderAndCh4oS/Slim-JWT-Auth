<?php

namespace Oacc\Validation\Field;

use Doctrine\ORM\EntityManager;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class UniqueFieldValidation
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $criteria
     * @param $entityName
     * @param $entityId
     * @return bool
     */
    public function isUnique($criteria, $entityName, $entityId)
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
