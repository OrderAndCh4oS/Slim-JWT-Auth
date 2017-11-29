<?php

namespace Oacc\Validation\Field;

use Doctrine\ORM\EntityManager;
use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class Unique extends FieldValidation
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    private $criteria;
    private $entityName;
    private $entityId;

    /**
     * UniqueValidation constructor.
     * @param $criteria
     * @param $entityName
     * @param $entityId
     * @param EntityManager $entityManager
     */
    public function __construct($entityName, $criteria, $entityId, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->criteria = $criteria;
        $this->entityName = $entityName;
        $this->entityId = $entityId;
    }

    public function validate(Error $error)
    {
        if (!$this->isUnique()) {
            $error->addError(ucfirst($error->getName()).' is not available');
        }
    }

    /**
     * @return bool
     */
    private function isUnique()
    {
        $entityRepository = $this->entityManager->getRepository($this->entityName);
        $entity = $entityRepository->findOneBy($this->criteria);
        switch ($entity) {
            case null:
                return true;
            case $entity->getId() === $this->entityId:
                return true;
            default:
                return !$entity;
        }
    }
}
