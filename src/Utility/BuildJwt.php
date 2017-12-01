<?php

namespace Oacc\Utility;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class BuildJwt
{
    /**
     * @var Builder $builder
     */
    private $builder;

    public function __construct()
    {
        $this->builder = (new Builder())->setIssuer(getenv('JWT_ISSUER'))
            ->setAudience(getenv('JWT_AUDIENCE'))
            ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + 3600);
    }

    public function addClaims(array $data)
    {
        foreach ($data as $key => $value) {
            $this->builder->set($key, $value);
        }

        return $this;
    }

    public function sign()
    {
        $signer = new Sha256();

        return (string)$this->builder->sign($signer, getenv('JWT_KEY'))->getToken();
    }
}
