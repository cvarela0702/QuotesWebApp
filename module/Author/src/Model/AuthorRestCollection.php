<?php

namespace Author\Model;

use Zend\Http\Client;
use Zend\Json\Json;

use RuntimeException;


/**
 * Description of AuthorRestCollection
 *
 * @author gabriel
 */
class AuthorRestCollection
{
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
    
    private function setHttpClientSettings($httpClientSettings)
    {
        $this->httpClientUri=$httpClientSettings['base_uri'].
                $httpClientSettings['authors']['route'];
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
    
    public function fetchAll()
    {
        $this->httpClient->setMethod('GET');
        $res=$this->httpClient->send();
        $json=$this->getJson();
        $all=$json->decode($res->getContent());
        return $all;
    }
    
    public function saveAuthor(Author $author)
    {
        $data=$author->getArrayCopy();
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
            throw new RuntimeException("The author could not be saved.");
        }
        if(!empty($entity_id) and $res->getStatusCode()!=200)
        {
            throw new RuntimeException("The author could not be saved.");
        }
    }
    
    public function getAuthor($id)
    {
        $id=(int)$id;
        $this->httpClient->setMethod('GET');
        $this->httpClient->setUri($this->httpClientUri."/$id");
        $res=$this->httpClient->send();
        if($res->getStatusCode()!=200)
        {
            throw new RuntimeException(sprintf("There is no author with ID %d", $id));
        }
        $json=$this->getJson();
        $all=$json->decode($res->getContent());
        $author=new Author();
        $author->exchangeArray(get_object_vars($all));
        return $author;
    }
    
    public function deleteAuthor($id)
    {
        $id=(int)$id;
        $this->httpClient->setMethod('DELETE');
        $this->httpClient->setUri($this->httpClientUri."/$id");
        $res=$this->httpClient->send();
        if($res->getStatusCode()!=204)
        {
            throw new RuntimeException(sprintf("The author with ID %d was not deleted", $id));
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
