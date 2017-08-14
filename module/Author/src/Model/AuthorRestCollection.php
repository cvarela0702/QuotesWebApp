<?php

namespace Author\Model;

use Zend\Http\Client;
use Zend\Json\Json;

/**
 * Description of AuthorRestCollection
 *
 * @author gabriel
 */
class AuthorRestCollection
{
    private $httpClient;
    
    public function __construct(Client $httpClient)
    {
        $this->httpClient=$httpClient;
    }
    
    public function fetchAll()
    {
        $res=$this->httpClient->send();
        $json=new Json();
        $all=$json->decode($res->getContent());
        return $all;
    }
}
