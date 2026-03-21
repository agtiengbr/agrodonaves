<?php
namespace AGTI\Rodonaves\Test;

require_once '../../config/config.inc.php';
require_once 'agrodonaves.php';

use AgRodonavesAccessToken;
use DateTime;

class AcessTokenObjectModelTest extends \Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before()
    {
        //carrega dependências
        new \agrodonaves;
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $this->assertDoesNotThrow(Exception::class, function(){
            //data inválida
            AgRodonavesAccessToken::saveToken("Test Token", new DateTime);
        });
        
        sleep(1);

        //verifica se obtém o mesmo token que foi salvo
        $r = AgRodonavesAccessToken::get();
        $this->assertEquals('Test Token', $r->token);
    }
}