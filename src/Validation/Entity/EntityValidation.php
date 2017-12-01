<?php

namespace Oacc\Validation\Entity;

use Doctrine\ORM\EntityManager;
use Oacc\Exceptions\ValidationException;
use Oacc\Utility\Error;

/**
 * Class EntityValidation
 * @package Oacc\Validation\Entity
 */
abstract class EntityValidation
{

    /**
     * @var Error
     */
    protected $error;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * EntityValidation constructor.
     */
    public function __construct()
    {
        $this->error = new Error();
    }

    /**
     * @param $entity
     * @return mixed
     */
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
}
