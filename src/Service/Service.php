<?php
namespace AGTI\Rodonaves\Service;

use AgRodonavesRequest;
use AGTI\Rodonaves\Exception\CurlNotFoundException;
use AGTI\Rodonaves\Exception\RodonavesException;
use AGTI\Rodonaves\Utils;
use AGTI\Rodonaves\Entity\Configuration;

abstract class Service
{
    protected $baseUri = 'https://01wapi.rte.com.br/';
    protected $config;
    
    public function __construct()
    {
        $this->config = new Configuration();
        $this->config->loadConfig();

        if($this->config->getEndpoint() != '' || $this->config->getEndpoint() != NULL ){
            $barr=substr($this->config->getEndpoint(), -1) == '/';

            if($barr){
                $this->baseUri = $this->config->getEndpoint();
            }else{
                $this->baseUri = $this->config->getEndpoint().'/';
            }
        }
    }

    /**
     * Retorna a URL completa do endpoint referente ao 
     */
    public function buildUrl()
    {
        return $this->baseUri . $this->getEndpoint();
    }

    /**
     * Retorna o endpoint da API de cada serviço.
     * 
     * @return string
     * */
    abstract public function getEndpoint();

    /**
     * Executa uma requisição CURL
     * 
     * @param string $method GET|PUT
     * @param string $url URL a ser acessada
     * @param string $data
     * @param string $token Access Token da requisição
     * @param bool $json Encoda ou não os dados como  JSON
     * 
     * @throws RodonavesException A API retornou um erro.
     */
    protected function doRequest($method, $url, $data = array(), $token = '', $json = false)
    {
        $headers = [
            'Accept: application/json'
        ];

        // converte de objeto para array
        if (is_object($data)) {
            $data = Utils::objectToArray($data);
        }

        // percorre todos os arrays atrás de variaveis de strings e limpa os espaços laterais
        array_walk_recursive($data, function (&$v) {
            if (is_string($v)) {
                $v = trim($v);
            }
        });
        
        if (strtoupper($method) === 'POST') {
            if ($json) {
                $methodOptions = array(
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($data),
                );

                $headers[] = 'Content-Type: application/json';
            } else {
                $postFields = http_build_query($data);
                $methodOptions = array(
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postFields,
                );
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            }
        } else {
            $methodOptions = array(
                CURLOPT_HTTPGET => true
            );
        }

        if ($token) {
            $headers[] = "Authorization: Bearer {$token}";
        }

        $options = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 45
        );

        $options = ($options + $methodOptions);
        
        $curl = curl_init();

        curl_setopt_array($curl, $options);
        $body = curl_exec($curl);
        $decoded = json_decode($body);

        $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $request = new AgRodonavesRequest;
        $request->endpoint = $url;
        $request->headers = json_encode($headers);
        $request->method = $method;
        $request->body = json_encode($data);
        $request->http_code = $http;
        $request->response = $body;
        $request->time_spent = curl_getinfo($curl, CURLINFO_TOTAL_TIME );
        $request->add();

        if ($http == '404') {
            throw new CurlNotFoundException("O endereço {$url} retornou erro 404.");
        }

        if ($http == '400') {
            throw new RodonavesException("O endereço {$url} retornou erro 400 - " . $body);
        }

        if ($http == '401') {
            throw new RodonavesException("Autorização negada.");
        }

        if ((int)$http > 401) {
            throw new RodonavesException("Erro na requisição");
        }

        curl_close($curl);

        if (isset($decoded->error)) {
            throw new RodonavesException($decoded->error);
        }

        return $body;
    }
}