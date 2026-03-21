<?php

namespace AGTI\Rodonaves\Service;

use AGTI\Rodonaves\Entity\AccessToken;
use AGTI\Rodonaves\Entity\ServiceArgs\Auth as ServiceArgsAuth;
use AGTI\Rodonaves\Entity\ServiceResponse\Auth as ServiceResponseAuth;
use AGTI\Rodonaves\Exception\RodonavesException;

class Auth extends Service
{
    protected $endpoint = 'token';

    public function getEndpoint()
    {
        return $this->endpoint;
    }
    

    /**
     * @return ServiceResposeAuth
     * 
     * @throws RodonavesException A API Retornou um erro.
     */
    public function exec(ServiceArgsAuth $auth)
    {
        $url = $this->buildUrl();
        $request_data = [
            'auth_type' => 'dev',
            'grant_type' => 'password',
            'username' => $auth->getUser()->getUsername(),
            'password' => $auth->getUser()->getPassword()
        ];

        $r = $this->doRequest('POST', $url, $request_data);
        $decoded = json_decode($r);

        $token = new AccessToken;
        $token->setToken($decoded->access_token)
            ->setTokenType($decoded->token_type)
            ->setExpiresIn($decoded->expires_in)
            ->setUserId($decoded->userId)
            ->setName($decoded->name)
            ->setCompanyId($decoded->companyId)
            ->setUserCompanyId($decoded->userCompanyId)
            ->setIsMaster($decoded->isMaster)
            ->setExpires((new \DateTime)->add(new \DateInterval('PT12H')));
        
        $response = new ServiceResponseAuth;
        $response->setToken($token);
        
        return $response;
    }
}