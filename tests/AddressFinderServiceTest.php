<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\ServiceArgs\AddressFinder as ServiceArgsAddressFinder;
use AGTI\Rodonaves\Entity\ServiceResponse\AddressFinder as ServiceResponseAddressFinder;
use AGTI\Rodonaves\Exception\AddressNotFoundException;
use AGTI\Rodonaves\Service\External\AddressFinder;

class AddressFinderServiceTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    /** @var AddressFinder */
    protected $service;

    protected function _before()
    {
        $this->service = new AddressFinder;
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $postcode = new ServiceArgsAddressFinder;
        $postcode->setPostcode('16430176');

        //esse não é um bom teste unitário porque a API pode, por exemplo, cair... mas é o que temos por agora :(
        $r = $this->service->exec($postcode);
        $this->assertInstanceOf(ServiceResponseAddressFinder::class, $r);
        $this->assertEquals($r->getAddress()->getCity(), 'Guaiçara');

        //CEP inexistente
        $this->assertThrows(AddressNotFoundException::class, function(){
            $postcode = new ServiceArgsAddressFinder;
            $postcode->setPostcode('00000000');
            $this->service->exec($postcode);
        });
    }
}