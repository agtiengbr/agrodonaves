<?php
namespace AGTI\Rodonaves\Test;
require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';

use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\ServiceArgs\SearchCity as ServiceArgsSearchCity;
use AGTI\Rodonaves\Service\SearchCity;

class SearchCityServiceTest extends \Codeception\Test\Unit
{

    protected function _before()
    {
        new \agrodonaves;
        
        $user = new ApiUser;
        $user->setUsername('MFCONFECCOES')
            ->setPassword('Z9UY376R');
            
        $this->token = \AGTI\Rodonaves\Interfaces\AccessToken::getAccessToken($user, false);
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $args = new ServiceArgsSearchCity;
        $args->setCityName('Guaicara')
            ->setUf('SP')
            ->setToken($this->token);

        $service = new SearchCity;
        $r = $service->exec($args);

        $this->assertEquals('GUAICARA', strtoupper($r->getCities()[0]->getCity()));


        $args->setCityName('Lins')
            ->setUf('SP')
            ->setToken($this->token);

        $service = new SearchCity;
        $r = $service->exec($args);

        $this->assertEquals('LINS', strtoupper($r->getCities()[0]->getCity()));


        $args->setCityName('Gramado')
            ->setUf('RS')
            ->setToken($this->token);

        $service = new SearchCity;
        $r = $service->exec($args);

        $this->assertEquals('GRAMADO', strtoupper($r->getCities()[0]->getCity()));
    }
}