<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\ServiceArgs\AddressFinder;
use AGTI\Rodonaves\Exception\DataValidationException;

class AddressFinderEntityTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testValidation()
    {
        //7 dígitos
        $this->assertThrows(DataValidationException::class, function(){
            $entity = new AddressFinder;
            $entity->setPostcode("0100100");
        });

        //nulo
        $this->assertThrows(DataValidationException::class, function(){
            $entity = new AddressFinder;
            $entity->setPostcode('');
        });
        
        //texto no meio
        $this->assertThrows(DataValidationException::class, function(){
            $entity = new AddressFinder;
            $entity->setPostcode('a16430176');
        });

        //válido
        $this->assertDoesNotThrow(DataValidationException::class, function(){
            $entity = new AddressFinder;
            $entity->setPostcode('16430176');
        });

        //CEP correto retornado
        $entity = new AddressFinder;
        $entity->setPostcode('16430176');
        $this->assertEquals($entity->getPostcode(), '16430176');
    }
}