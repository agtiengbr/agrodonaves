<?php

namespace AGTI\Rodonaves\Interfaces;

use AgRodonavesCity;
use AGTI\Rodonaves\Entity\AccessToken;
use AGTI\Rodonaves\Entity\ServiceArgs\AddressFinder as ServiceArgsAddressFinder;
use AGTI\Rodonaves\Entity\ServiceArgs\SearchCity as ServiceArgsSearchCity;
use AGTI\Rodonaves\Exception\CityNotFoundException;
use AGTI\Rodonaves\Service\External\AddressFinder;
use AGTI\Rodonaves\Service\SearchCity;

class Address
{
    public static function getCityIdFromPostcode($postcode, AccessToken $token)
    {
        //consulta o endereço na AGTI
        $args = new ServiceArgsAddressFinder;
        $args->setPostcode($postcode);

        $service = new AddressFinder;
        $response = $service->exec($args);
        $address = $response->getAddress();

        $address->setCity(self::clean($address->getCity()));

        //verifica se o código dessa cidade já está no BD local
        $dbCity = AgRodonavesCity::get($address->getCity());
        if (\Validate::isLoadedObject($dbCity)) {
            return $dbCity->city_id;
        }

        //em caso negativo, consulta o código junto a rodonaves        
        $args = new ServiceArgsSearchCity;
        $args->setCityName($address->getCity())
            ->setUf($address->getState())
            ->setToken($token);

        $service = new SearchCity;
        $response = $service->exec($args);

        foreach ($response->getCities() as $city) {
            if (strtoupper($city->getCity()) == strtoupper($address->getCity()) && strtoupper($city->getState()) == strtoupper($address->getState())) {
                AgRodonavesCity::saveCity($city->getCity(), $city->getCityId());
                return $city->getCityId();
            }
        }

        throw new CityNotFoundException("Cidade não localizada para o CEP {$postcode}.");
    }

    public static function clean($str)
    {
        $chars_from = ['ç', 'Ç', 'ã', 'Ã', 'â', 'á', 'í'];
        $chars_to = ['c', 'C', 'a', 'a', 'a', 'a', 'i'];

        return str_replace($chars_from, $chars_to, $str);
    }
}