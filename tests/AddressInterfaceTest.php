<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Exception\CityNotFoundException;

require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';

class AddressInterfaceTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

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
        $this->assertDoesNotThrow(CityNotFoundException::class, function(){
            \AGTI\Rodonaves\Interfaces\Address::getCityIdFromPostcode("16430-176", $this->token);
            \AGTI\Rodonaves\Interfaces\Address::getCityIdFromPostcode("01001001", $this->token);
            \AGTI\Rodonaves\Interfaces\Address::getCityIdFromPostcode("90040320", $this->token);
        });
    }
}