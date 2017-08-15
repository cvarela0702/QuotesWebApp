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
    
    public function __construct(Client $httpClient)
    {
        $this->httpClient=$httpClient;
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
        $data=$author->getData();
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
        if($res->getStatusCode()!=201)
        {
            throw new RuntimeException("The author could not be saved.");
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
