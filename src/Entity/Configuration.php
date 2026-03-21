<?php

namespace AGTI\Rodonaves\Entity;

use AGTI\Rodonaves\Exception\DataValidationException;

class Configuration
{
    protected $username;
    protected $password;
    protected $postcodeFrom;
    protected $taxRegistrationId;
    protected $endpoint;

    /**
     * Get the value of postcodeFrom
     */ 
    public function getPostcodeFrom()
    {
        return $this->postcodeFrom;
    }

    /**
     * Set the value of postcodeFrom
     *
     * @return  self
     */ 
    public function setPostcodeFrom($postcodeFrom)
    {
        $postcode = str_replace(['.', '-'], "", $postcodeFrom);
        if (strlen($postcode) != 8 && strlen($postcode) != 0) {
            throw new DataValidationException("{$postcodeFrom} não é um CEP válido.");
        }

        $this->postcodeFrom = $postcodeFrom;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    
    /**
     * Get the value of taxRegistrationId
     */ 
    public function getTaxRegistrationId()
    {
        return $this->taxRegistrationId;
    }

    /**
     * Set the value of taxRegistrationId
     *
     * @return  self
     */ 
    public function setTaxRegistrationId($taxRegistrationId)
    {
        $this->taxRegistrationId = $taxRegistrationId;

        return $this;
    }

    public function persist()
    {
        \Configuration::updateValue('AGRODONAVES_CONFIG_USERNAME', $this->getUsername());
        \Configuration::updateValue('AGRODONAVES_CONFIG_PASSWORD', $this->getPassword());
        \Configuration::updateValue('AGRODONAVES_CONFIG_POSTCODE_FROM', $this->getPostcodeFrom());
        \Configuration::updateValue('AGRODONAVES_API_CUSTOMER_TAX_ID', $this->getTaxRegistrationId());
        \Configuration::updateValue('AGRODONAVES_ENDPOINT', $this->getEndpoint());
    }

    public function loadConfig()
    {
        $config = \Configuration::getMultiple([
            'AGRODONAVES_CONFIG_USERNAME',
            'AGRODONAVES_CONFIG_PASSWORD',
            'AGRODONAVES_CONFIG_POSTCODE_FROM',
            'AGRODONAVES_API_CUSTOMER_TAX_ID',
            'AGRODONAVES_ENDPOINT'
        ]);

        $this->setUsername($config['AGRODONAVES_CONFIG_USERNAME']);
        $this->setPassword($config['AGRODONAVES_CONFIG_PASSWORD']);
        $this->setPostcodeFrom($config['AGRODONAVES_CONFIG_POSTCODE_FROM']);
        $this->setTaxRegistrationId($config['AGRODONAVES_API_CUSTOMER_TAX_ID']);
        $this->setEndpoint($config['AGRODONAVES_ENDPOINT']);
    }

    /**
     * Get the value of Endpoint
     */ 
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the value of Endpoint
     *
     * @return  self
     */ 
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}