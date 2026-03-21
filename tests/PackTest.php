<?php
namespace AGTI\Rodonaves\Test;

use AGTI\Rodonaves\Entity\Pack;
use AGTI\Rodonaves\Exception\DataValidationException;

class PackTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testAmountPackagesNegativeValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setAmountPackages(-1);
        });
    }

    public function testAmountPackagesNullValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setAmountPackages(0);
        });
    }

    public function testAmountPackagesFloatValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setAmountPackages(0.1);
        });
    }

    public function testAmountPackagesNotNumberValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setAmountPackages('fff');
        });
    }

    public function testAmountPackagesIntValue()
    {
        $this->assertDoesNotThrow(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setAmountPackages(3);
        });
    }


    //peso
    public function testWeightNegativeValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setWeight(-1);
        });
    }

    public function testWeightNullValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setWeight(0);
        });
    }

    public function testWeightFloatValue()
    {
        $this->assertDoesNotThrow(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setWeight(0.1);
        });
    }

    public function testWeightNotNumberValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setWeight('fff');
        });
    }


    //comprimento
    public function testLengthNegativeValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setLength(-1);
        });
    }

    public function testLengthNullValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setLength(0);
        });
    }

    public function testLengthFloatValue()
    {
        $this->assertDoesNotThrow(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setLength(0.1);
        });
    }

    public function testLengthNotNumberValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setLength('fff');
        });
    }




    //altura
    public function testHeightNegativeValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setHeight(-1);
        });
    }

    public function testHeightNullValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setHeight(0);
        });
    }

    public function testHeightFloatValue()
    {
        $this->assertDoesNotThrow(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setHeight(0.1);
        });
    }

    public function testHeightNotNumberValue()
    {
        $this->assertThrows(DataValidationException::class, function(){
            $pack = new Pack;
            $pack->setHeight('fff');
        });
    }
}