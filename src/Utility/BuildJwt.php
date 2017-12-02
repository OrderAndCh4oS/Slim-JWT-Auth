<?php

namespace Oacc\Utility;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Class BuildJwt
 * @package Oacc\Utility
 */
class BuildJwt
{
    /**
     * @var Builder $builder
     */
    private $builder;

    /**
     * BuildJwt constructor.
     */
    public function __construct()
    {
        $this->builder = (new Builder())->setIssuer(getenv('JWT_ISSUER'))
            ->setAudience(getenv('JWT_AUDIENCE'))
            ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + 3600);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function addClaims(array $data): BuildJwt
    {
        foreach ($data as $key => $value) {
            $this->builder->set($key, $value);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function sign(): string
    {
        $signer = new Sha256();

        return (string)$this->builder->sign($signer, getenv('JWT_KEY'))->getToken();
    }
}
