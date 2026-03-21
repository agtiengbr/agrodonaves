<?php

namespace AGTI\Rodonaves\Service;

use AGTI\Rodonaves\Entity\Address;
use AGTI\Rodonaves\Entity\ServiceArgs\SearchCity as ServiceArgsSearchCity;
use AGTI\Rodonaves\Entity\ServiceResponse\SearchCity as ServiceResponseSearchCity;
use AGTI\Rodonaves\Exception\CityNotFoundException;
use AGTI\Rodonaves\Exception\RodonavesException;

class SearchCity extends Service
{
    protected $endpoint = 'api/v1/busca-cidade';

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /** @return ServiceResponseSearchCity */
    public function exec(ServiceArgsSearchCity $args)
    {
        $url = $this->buildUrl() . '?name=' . urlencode($args->getCityName() . ', ' . $args->getUf());
        $r = $this->doRequest('GET', $url, [], $args->getToken()->getToken());
        $decoded = json_decode($r);

        if (isset($decoded->message)) {
            throw new RodonavesException($decoded->message);
        }
        if (count($decoded) == 0) {
            throw new CityNotFoundException("Cidade {$args->getCityName()} do estado {$args->getUf()} não localizada no servidor da Rodonaves.");
        }
        
        $response = new ServiceResponseSearchCity;

        foreach ($decoded as $city) {
            $address = new Address;

            $exploded = explode(',', $city->CityDescription);

            $address->setCity(trim($exploded[0]))
                ->setCityId($city->CityId)
                ->setState(trim($exploded[1]));

            $response->addCity($address);
        }

        return $response;
    }
}