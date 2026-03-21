<?php
namespace AGTI\Rodonaves\Test;

require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';

use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\Pack;
use AGTI\Rodonaves\Entity\ServiceArgs\SimulateShipping;
use AGTI\Rodonaves\Interfaces\Address;
use AGTI\Rodonaves\Service\SimulateShipping as ServiceSimulateShipping;

class SimulateShippingTest extends \Codeception\Test\Unit
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
    public function test()
    {
        $args = new SimulateShipping;

        $packs = [
            (new Pack)->setWeight(1)->setLength(5)->setWidth(10)->setHeight(3)->setAmountPackages(2),
            (new Pack)->setWeight(0.13)->setLength(4)->setWidth(7)->setHeight(2)->setAmountPackages(1),
        ];
        
        $args->addPack($packs[0])->addPack($packs[1]);
        $this->assertEquals(2.13, $args->getTotalWeight());


        $postcodeFrom = "01001001";
        $postcodeTo = "16430176";

        $cityFrom = Address::getCityIdFromPostcode($postcodeFrom, $this->token);
        $cityTo = Address::getCityIdFromPostcode($postcodeTo, $this->token);

        $args->setOriginCityId($cityFrom)
            ->setOriginZipCode($postcodeFrom)
            ->setDestinationCityId($cityTo)
            ->setDestinationZipCode($postcodeTo)
            ->setElectronicInvoiceValue(1)
            ->setCustomerTaxIdRegistration("38354626873")
            ->setToken($this->token);

        $service = new ServiceSimulateShipping;
        $r = $service->exec($args);
        
        $this->assertNotEmpty($r->getValue());
        $this->assertNotEmpty($r->getDeliveryTime());
    }
}