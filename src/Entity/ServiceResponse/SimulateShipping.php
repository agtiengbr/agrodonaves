<?php

namespace AGTI\Rodonaves\Entity\ServiceResponse;

class SimulateShipping
{
    protected $value;
    protected $deliveryTime;
    protected $protocolNumber;
    protected $customerEmail;
    protected $cubed;
    protected $message;
    protected $expirationDay;

    /**
     * Get the value of expirationDay
     */ 
    public function getExpirationDay()
    {
        return $this->expirationDay;
    }

    /**
     * Set the value of expirationDay
     *
     * @return  self
     */ 
    public function setExpirationDay($expirationDay)
    {
        $this->expirationDay = $expirationDay;

        return $this;
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of cubed
     */ 
    public function getCubed()
    {
        return $this->cubed;
    }

    /**
     * Set the value of cubed
     *
     * @return  self
     */ 
    public function setCubed($cubed)
    {
        $this->cubed = $cubed;

        return $this;
    }

    /**
     * Get the value of customerEmail
     */ 
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set the value of customerEmail
     *
     * @return  self
     */ 
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get the value of protocolNumber
     */ 
    public function getProtocolNumber()
    {
        return $this->protocolNumber;
    }

    /**
     * Set the value of protocolNumber
     *
     * @return  self
     */ 
    public function setProtocolNumber($protocolNumber)
    {
        $this->protocolNumber = $protocolNumber;

        return $this;
    }

    /**
     * Get the value of deliveryTime
     */ 
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * Set the value of deliveryTime
     *
     * @return  self
     */ 
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */ 
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}