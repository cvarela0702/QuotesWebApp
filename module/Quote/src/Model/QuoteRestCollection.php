<?php

namespace Quote\Model;

use Zend\Http\Client;
use Zend\Json\Json;
use RuntimeException;
use Zend\Paginator\Adapter\ArrayAdapter;
use Paginator;

/**
 * Description of QuoteRestCollection
 *
 * @author gabriel
 */
class QuoteRestCollection {
    private $httpClient;
    private $json;
    private $httpClientUri;
    private $httpClientHeaders;
    private $httpClientMethod;
    private $httpClientAuth;
    
    public function __construct(Client $httpClient, array $httpClientSettings)
    {
        $this->httpClient=$httpClient;
        $this->setHttpClientSettings($httpClientSettings);
    }
    
    public function setHttpClientSettings(array $httpClientSettings)
    {
        $this->httpClientUri=$httpClientSettings['base_uri'].
                $httpClientSettings['quotes']['route'];
        $this->httpClientHeaders=$httpClientSettings['headers'];
        $this->httpClientAuth=$httpClientSettings['basic_auth'];
        $this->httpClientMethod=$httpClientSettings['method'];
        
        $this->httpClient->setUri($this->httpClientUri);
        $this->httpClient->setHeaders($this->httpClientHeaders);
        $this->httpClient->setAuth(
                $this->httpClientAuth['user'],
                $this->httpClientAuth['password']);
        $this->httpClient->setMethod($this->httpClientMethod);
    }
    
    public function fetchAll($paginated=false,$conditions=null)
    {
        if(!is_null($conditions))
        {
            $uri=$this->httpClient->getUri();
            $uri->setQuery($conditions);
        }
        if($paginated===true)
        {
            return $this->fetchPaginatedResults($conditions);
        }
        $this->httpClient->setMethod('GET');
        $res=$this->httpClient->send();
        $json=$this->getJson();
        $all=$json->decode($res->getContent());
        return $all;
    }
    
    public function fetchPaginatedResults()
    {
        $this->httpClient->setMethod('GET');
        $res=$this->httpClient->send();
        $json=$this->getJson();
        $all=$json->decode($res->getContent());
        
        $paginatorAdapter=new ArrayAdapter($all->_embedded->quotes);
        
        $paginator=new Paginator($paginatorAdapter);
        return $paginator;
    }
    
    public function saveQuote(Quote $quote)
    {
        $data=$quote->getArrayCopy();
        $entity_id=(int)$data['entity_id'];
        
        if(empty($entity_id))
        {
            $this->httpClient->setMethod('POST');
        }
        else
        {
            $this->httpClient->setMethod('PUT');
        }
        $json=$this->getJson();
        $body=$json->encode($data);
        
        $this->httpClient->setRawBody($body);
        $res=$this->httpClient->send();
        if(empty($entity_id) and $res->getStatusCode()!=201)
        {
            throw new RuntimeException("The quote could not be saved.");
        }
        if(!empty($entity_id) and $res->getStatusCode()!=200)
        {
            throw new RuntimeException("The quote could not be saved.");
        }
    }
    
    public function getQuote($id)
    {
        $id=(int)$id;
        $this->httpClient->setMethod('GET');
        $this->httpClient->setUri($this->httpClientUri."/$id");
        $res=$this->httpClient->send();
        if($res->getStatusCode()!=200)
        {
            throw new RuntimeException(sprintf("There is no quote with ID %d", $id));
        }
        $json=$this->getJson();
        $all=$json->decode($res->getContent());
        $quote=new Quote();
        $quote->exchangeArray(get_object_vars($all));
        return $quote;
    }
    
    public function deleteQuote($id)
    {
        $id=(int)$id;
        $this->httpClient->setMethod('DELETE');
        $this->httpClient->setUri($this->httpClientUri."/$id");
        $res=$this->httpClient->send();
        if($res->getStatusCode()!=204)
        {
            throw new RuntimeException(sprintf("The quote with ID %d was not deleted", $id));
        }
    }
    
    public function setJson($json)
    {
        $this->json=$json;
    }
    
    public function getJson()
    {
        if(!isset($this->json))
        {
            $json=new Json();
            $this->json=$json;
        }
        return $this->json;
    }
}
