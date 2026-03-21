<?php
namespace AGTI\Rodonaves\Test;

require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';

use AGTI\Rodonaves\Entity\AccessToken as EntityAccessToken;
use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Interfaces\AccessToken;

class AcessTokenInterfaceTest extends \Codeception\Test\Unit
{

    protected function _before()
    {
        new \agrodonaves;
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        \Db::getInstance()->delete('agrodonaves_access_token');
        $user = new ApiUser;
        $user->setUsername('MFCONFECCOES')
            ->setPassword('Z9UY376R');

        $token = AccessToken::getAccessToken($user);
        $this->assertInstanceOf(EntityAccessToken::class, $token);
    }
}   