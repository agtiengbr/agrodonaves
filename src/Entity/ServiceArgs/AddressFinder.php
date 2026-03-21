<?php

namespace AGTI\Rodonaves\Entity\ServiceArgs;

use AGTI\Rodonaves\Exception\DataValidationException;

class AddressFinder
{
    protected $postcode;

    /**
     * Get the value of postcode
     */ 
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set the value of postcode
     *
     * @return  self
     */ 
    public function setPostcode($postcode)
    {
        $postcode = str_replace(['.', '-'], "", $postcode);
        if (strlen($postcode) != 8) {
            throw new DataValidationException("{$postcode} não é um CEP válido.");
        }

        $this->postcode = $postcode;

        return $this;
    }
}