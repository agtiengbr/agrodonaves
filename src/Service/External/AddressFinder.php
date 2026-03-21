<?php

namespace AGTI\Rodonaves\Service\External;

use AGTI\Rodonaves\Entity\Address;
use AGTI\Rodonaves\Entity\ServiceArgs\AddressFinder as ServiceArgsAddressFinder;
use AGTI\Rodonaves\Entity\ServiceResponse\AddressFinder as ServiceResponseAddressFinder;
use AGTI\Rodonaves\Exception\AddressNotFoundException;
use AGTI\Rodonaves\Exception\CurlNotFoundException;
use AGTI\Rodonaves\Service\Service;

class AddressFinder extends Service
{
    public function buildUrl()
    {
        return "https://viacep.com.br/ws/";
    }

    public function getEndpoint()
    {
        return '';
    }

    /** @return ServiceResponseAddressFinder */
    public function exec(ServiceArgsAddressFinder $args)
    {
        $postcode = preg_replace('/\D+/', '', (string) $args->getPostcode());
        $url = $this->buildUrl() . $postcode . '/json/';

        try {
            $r = $this->doRequest('GET', $url);
        } catch (CurlNotFoundException $e) {
            throw new AddressNotFoundException("O CEP {$args->getPostcode()} não foi encontrado.");
        }
        
        $decoded = json_decode($r);
        if (!$decoded || (isset($decoded->erro) && $decoded->erro)) {
            throw new AddressNotFoundException("O CEP {$args->getPostcode()} não foi encontrado.");
        }

        $address = new Address();
        $address
            ->setStreet((string) ($decoded->logradouro ?? ''))
            ->setPostcode((string) ($decoded->cep ?? $postcode))
            ->setNeighborhood((string) ($decoded->bairro ?? ''))
            ->setCity((string) ($decoded->localidade ?? ''))
            ->setState((string) ($decoded->uf ?? ''));

        $response = new ServiceResponseAddressFinder;
        $response->setAddress($address);

        return $response;
    }
}