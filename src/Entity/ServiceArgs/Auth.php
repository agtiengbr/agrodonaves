<?php

namespace AGTI\Rodonaves\Entity\ServiceArgs;

use AGTI\Rodonaves\Entity\ApiUser;

class Auth
{
    /** @var ApiUser */
    protected $user;

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}