<?php

namespace AGTI\Rodonaves\Entity\ServiceResponse;

use AGTI\Rodonaves\Entity\AccessToken;

class Auth
{
    /** @var AccessToken */
    protected $token;

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}