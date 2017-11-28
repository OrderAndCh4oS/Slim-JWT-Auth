<?php

namespace Oacc\Validation\Entity;

use Doctrine\ORM\EntityManager;
use Oacc\Exceptions\ValidationException;
use Oacc\Utility\Error;

abstract class EntityValidation
{

    protected $error;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct()
    {
        $this->error = new Error();
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
}
