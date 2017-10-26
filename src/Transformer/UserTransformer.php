<?php

namespace Oacc\Transformer;

use Oacc\Entity\User;

class UserTransformer
{
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmailAddress(),
        ];
    }
}
