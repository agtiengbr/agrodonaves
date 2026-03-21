<?php

namespace AGTI\Rodonaves\Entity\ServiceArgs;

use AGTI\Rodonaves\Entity\AccessToken;
use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\Pack;

class SimulateShipping
{
    protected $originZipCode;
    protected $originCityId;
    protected $destinationZipCode;
    protected $destinationCityId;
    protected $electronicInvoiceValue;
    protected $customerTaxIdRegistration;
    protected $token;

    /** @var Pack[] */
    protected $packs = [];

    /**
     * Get the value of originZipCode
     */ 
    public function getOriginZipCode()
    {
        return $this->originZipCode;
    }

    /**
     * Set the value of originZipCode
     *
     * @return  self
     */ 
    public function setOriginZipCode($originZipCode)
    {
        $this->originZipCode = $originZipCode;

        return $this;
    }

    /**
     * Get the value of originCityId
     */ 
    public function getOriginCityId()
    {
        return $this->originCityId;
    }

    /**
     * Set the value of originCityId
     *
     * @return  self
     */ 
    public function setOriginCityId($originCityId)
    {
        $this->originCityId = $originCityId;

        return $this;
    }

    /**
     * Get the value of destinationZipCode
     */ 
    public function getDestinationZipCode()
    {
        return $this->destinationZipCode;
    }

    /**
     * Set the value of destinationZipCode
     *
     * @return  self
     */ 
    public function setDestinationZipCode($destinationZipCode)
    {
        $this->destinationZipCode = $destinationZipCode;

        return $this;
    }

    /**
     * Get the value of destinationCityId
     */ 
    public function getDestinationCityId()
    {
        return $this->destinationCityId;
    }

    /**
     * Set the value of destinationCityId
     *
     * @return  self
     */ 
    public function setDestinationCityId($destinationCityId)
    {
        $this->destinationCityId = $destinationCityId;

        return $this;
    }

    /**
     * Get the value of totalWeight
     */ 
    public function getTotalWeight()
    {
        $return = 0;

        foreach ($this->getPacks() as $pack) {
            $return += $pack->getAmountPackages() * $pack->getWeight();
        }

        return $return;
    }

    /**
     * Get the value of electronicInvoiceValue
     */ 
    public function getElectronicInvoiceValue()
    {
        return $this->electronicInvoiceValue;
    }

    /**
     * Set the value of electronicInvoiceValue
     *
     * @return  self
     */ 
    public function setElectronicInvoiceValue($electronicInvoiceValue)
    {
        $this->electronicInvoiceValue = $electronicInvoiceValue;

        return $this;
    }

    /**
     * Get the value of customerTaxIdRegistration
     */ 
    public function getCustomerTaxIdRegistration()
    {
        return $this->customerTaxIdRegistration;
    }

    /**
     * Set the value of customerTaxIdRegistration
     *
     * @return  self
     */ 
    public function setCustomerTaxIdRegistration($customerTaxIdRegistration)
    {
        $this->customerTaxIdRegistration = $customerTaxIdRegistration;

        return $this;
    }

    /**
     * Get the value of packs
     */ 
    public function getPacks()
    {
        return $this->packs;
    }

    /**
     * Set the value of packs
     *
     * @return  self
     */ 
    public function addPack(Pack $pack)
    {
        $this->packs[] = $pack;

        return $this;
    }

    /**
     * Get the value of token
     * 
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