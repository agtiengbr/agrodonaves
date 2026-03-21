<?php

namespace AGTI\Rodonaves\Interfaces;

use AgRodonavesCache;
use AGTI\Rodonaves\Entity\AccessToken;
use AGTI\Rodonaves\Entity\Pack;
use AGTI\Rodonaves\Entity\ServiceArgs\SimulateShipping as ServiceArgsSimulateShipping;
use AGTI\Rodonaves\Entity\ServiceResponse\SimulateShipping as ServiceResponseSimulateShipping;
use AGTI\Rodonaves\Exception\CityNotFoundException;
use AGTI\Rodonaves\Service\SimulateShipping as ServiceSimulateShipping;

class SimulateShipping
{
    public static function getShippingCost(AccessToken $token, $postcode_from, $postcode_to, $products, $invoice_value, $customer_tax_registration, $cache = true)
    {
        $args = new ServiceArgsSimulateShipping;
        foreach ($products as $product) {
            $pack = new Pack;
            $pack->setWeight($product['weight'])
                ->setHeight($product['height'])
                ->setLength($product['length'])
                ->setWidth($product['width'])
                ->setAmountPackages($product['quantity']);

            $args->addPack($pack);
        }

        if ($cache) {
            $dbData = AgRodonavesCache::get($postcode_from, $postcode_to, $args->getTotalWeight(), $invoice_value, $products);
            if (\Validate::isLoadedObject($dbData)) {
                $response = new ServiceResponseSimulateShipping;
                $response->setDeliveryTime($dbData->delivery_time)
                    ->setValue($dbData->shipping_cost);

                return $response;
            }
        }

        $city_from = 0;
        $city_to = 0;

        $args->setOriginZipCode($postcode_from)
            ->setOriginCityId($city_from)
            ->setDestinationZipCode($postcode_to)
            ->setDestinationCityId($city_to)
            ->setElectronicInvoiceValue($invoice_value)
            ->setCustomerTaxIdRegistration($customer_tax_registration)
            ->setToken($token);

        $service = new ServiceSimulateShipping;
        $r = $service->exec($args);

        AgRodonavesCache::saveCache($postcode_from, $postcode_to, $args->getTotalWeight(), $invoice_value, $products, $r->getValue(), $r->getDeliveryTime());

        return $r;
    }
}