<?php
namespace AGTI\Rodonaves\Interfaces;

use AgRodonavesAccessToken;
use AGTI\Rodonaves\Entity\AccessToken as EntityAccessToken;
use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\ServiceArgs\Auth as ServiceArgsAuth;
use AGTI\Rodonaves\Exception\RodonavesException;
use AGTI\Rodonaves\Service\Auth;
use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class AccessToken
{
    /**
     * Obtém o access token para ser utilizado na API.
     * Primeiramente o access token é consultado no banco de dados local
     * evitando que façamos consultas desnecessárias ao servidor da API.
     * 
     * @var ApiUser $user Credenciais de acesso à API
     * @return EntityAccessToken Token de acesso à API
     * 
     * @throws RodonavesException ocorreu um erro na comunicação com a API.
     */
    public static function getAccessToken(ApiUser $user, $cache = true)
    {
        //consulta local
        if ($cache) {
            $dbToken = \AgRodonavesAccessToken::get();
            if (\Validate::isLoadedObject($dbToken)) {
                $return = new EntityAccessToken;
                $return->setToken($dbToken->token);
                return $return;
            }
        }

        //solicitação de token via API
        $args = new ServiceArgsAuth;
        $args->setUser($user);

        $service = new Auth;
        $response = $service->exec($args);

        $token = $response->getToken();

        try {
            AgRodonavesAccessToken::saveToken($token->getToken(), $token->getExpires());
        } catch (DatabaseException $e) {
            \Logger::addLog("agrodonaves - Erro gravando token de acesso no banco de dados - {$e->getMessage()}", 3, 0x1, 'AgRodonavesAccessToken', null, true);
        } catch (\Exception $e) {
            \Logger::addLog("agrodonaves - Erro validando dados do token de acesso para gravação no banco de dados - {$e->getMessage()}", 3, 0x2, 'AgRodonavesAccessToken', null, true);
        }

        return $token;
    }
}