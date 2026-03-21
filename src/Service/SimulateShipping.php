<?php

namespace AGTI\Rodonaves\Service;

use AGTI\Rodonaves\Entity\ServiceArgs\Auth as ServiceArgsAuth;
use AGTI\Rodonaves\Entity\ServiceArgs\SimulateShipping as ServiceArgsSimulateShipping;
use AGTI\Rodonaves\Entity\ServiceResponse\Auth as ServiceResponseAuth;
use AGTI\Rodonaves\Entity\ServiceResponse\SimulateShipping as ServiceResponseSimulateShipping;
use AGTI\Rodonaves\Exception\RodonavesException;

class SimulateShipping extends Service
{
    protected $endpoint = 'api/v1/simula-cotacao';

    public function getEndpoint()
    {
        return $this->endpoint;
    }
    

    /**
     * @return ServiceResponseSimulateShipping
     * 
     * @throws RodonavesException A API Retornou um erro.
     */
    public function exec(ServiceArgsSimulateShipping $args)
    {
        $url = $this->buildUrl();

        $request_data = [
            'OriginZipCode' => $args->getOriginZipCode(),
            'OriginCityId' => $args->getOriginCityId(),
            'DestinationZipCode' => $args->getDestinationZipCode(),
            'DestinationCityId' => $args->getDestinationCityId(),
            'TotalWeight' => $args->getTotalWeight(),
            'EletronicInvoiceValue' => $args->getElectronicInvoiceValue(),
            'CustomerTaxIdRegistration' => $args->getCustomerTaxIdRegistration(),
            'Packs' => []
        ];

        foreach ($args->getPacks() as $pack) {
            $request_data['Packs'][] = [
                'AmountPackages' => $pack->getAmountPackages(),
                'Weight' => $pack->getWeight(),
                'Length' => $pack->getLength(),
                'Height' => $pack->getHeight(),
                'Width' => $pack->getWidth()
            ];
        }

        $r = $this->doRequest('POST', $url, $request_data, $args->getToken()->getToken(), true);
        $decoded = json_decode($r);

        $response = new ServiceResponseSimulateShipping;
        $response->setValue($decoded->Value)
            ->setDeliveryTime($decoded->DeliveryTime)
            ->setProtocolNumber($decoded->ProtocolNumber)
            ->setCustomerEmail($decoded->CustomerEmail)
            ->setCubed($decoded->Cubed)
            ->setMessage($decoded->Message)
            ->setExpirationDay(\DateTime::createFromFormat("Y-m-dTH:i:s", $decoded->ExpirationDay));

        return $response;
    }
}