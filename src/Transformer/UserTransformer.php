<?php

namespace Oacc\Transformer;

use Oacc\Entity\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
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
