<?php

namespace Oacc\Transformer;

use League\Fractal;
use Oacc\Entity\User;

/**
 * Class UserTransformer
 * @package Oacc\Transformer
 */
class UserTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmailAddress(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ];
    }
}
