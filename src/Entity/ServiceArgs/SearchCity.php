<?php
namespace AGTI\Rodonaves\Entity\ServiceArgs;

use AGTI\Rodonaves\Entity\AccessToken;

;

class SearchCity
{
    protected $cityName;
    protected $uf;
    protected $token;

    /**
     * Get the value of cityName
     */ 
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set the value of cityName
     *
     * @return  self
     */ 
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get the value of uf
     */ 
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Set the value of uf
     *
     * @return  self
     */ 
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get the value of token
     * @return AccessToken
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