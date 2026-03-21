<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\ServiceArgs\Auth as ServiceArgsAuth;
use AGTI\Rodonaves\Entity\ServiceResponse\Auth as ServiceResponseAuth;
use AGTI\Rodonaves\Exception\RodonavesException;
use AGTI\Rodonaves\Service\Auth;

class AuthTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testBadCredentials()
    {
        $this->assertThrows(RodonavesException::class, function(){
            $username = 'erro';
            $pass = 'erro';
    
            $service = new Auth;
    
            $user = new ApiUser;
            $user->setPassword($pass)->setUsername($username);
    
            $auth = new ServiceArgsAuth;
            $auth->setUser($user);
    
            $service->exec($auth);
        });
    }

    public function testGoodCredentials()
    {
        $username = 'MFCONFECCOES';
        $pass = 'Z9UY376R';

        $service = new Auth;

        $user = new ApiUser;
        $user->setPassword($pass)->setUsername($username);

        $auth = new ServiceArgsAuth;
        $auth->setUser($user);

        $response = $service->exec($auth);

        $this->assertInstanceOf(ServiceResponseAuth::class, $response);
    }
}