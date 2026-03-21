<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Interfaces\SimulateShipping;

require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';


class SimulateShippingInterfaceTest extends \Codeception\Test\Unit
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
        $products = [
            [
                'weight' => 1,
                'length' => 1.5,
                'height' => 0.7,
                'width' => 5,
                'quantity' => 1
            ],
            [
                'weight' => 2,
                'length' => 5,
                'height' => 7,
                'width' => 1,
                'quantity' => 3
            ]
        ];

        $r = SimulateShipping::getShippingCost($this->token, '01001001', '16430176', $products, 10, '38354626873');
        $this->assertNotEmpty($r->getValue());
        $this->assertNotEmpty($r->getDeliveryTime());
    }
}