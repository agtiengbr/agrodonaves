<?php

namespace AGTI\Rodonaves\Entity\ServiceResponse;

use AGTI\Rodonaves\Entity\Address;

class SearchCity
{
    /** @var Address[] */
    protected $cities = [];

    /**
     * Get the value of cities
     */ 
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Set the value of cities
     *
     * @return  self
     */ 
    public function addCity(Address $city)
    {
        $this->cities[] = $city;

        return $this;
    }
}