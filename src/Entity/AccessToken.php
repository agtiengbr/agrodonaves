<?php

namespace AGTI\Rodonaves\Entity;

class AccessToken
{
    protected $token;
    protected $tokenType;
    protected $expiresIn;
    protected $userId;
    protected $name;
    protected $companyId;
    protected $userCompanyId;
    protected $isMaster;
    protected $expires;

    /**
     * Get the value of expires
     */ 
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set the value of expires
     *
     * @return  self
     */ 
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get the value of isMaster
     */ 
    public function getIsMaster()
    {
        return $this->isMaster;
    }

    /**
     * Set the value of isMaster
     *
     * @return  self
     */ 
    public function setIsMaster($isMaster)
    {
        $this->isMaster = $isMaster;

        return $this;
    }

    /**
     * Get the value of userCompanyId
     */ 
    public function getUserCompanyId()
    {
        return $this->userCompanyId;
    }

    /**
     * Set the value of userCompanyId
     *
     * @return  self
     */ 
    public function setUserCompanyId($userCompanyId)
    {
        $this->userCompanyId = $userCompanyId;

        return $this;
    }

    /**
     * Get the value of companyId
     */ 
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set the value of companyId
     *
     * @return  self
     */ 
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of expiresIn
     */ 
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * Set the value of expiresIn
     *
     * @return  self
     */ 
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * Get the value of tokenType
     */ 
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * Set the value of tokenType
     *
     * @return  self
     */ 
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;

        return $this;
    }

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