<?php

namespace Author;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Http\Client;

/**
 * Description of Module
 *
 * @author gabriel
 */
class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return [
            'factories'=>[
                Model\AuthorRestCollection::class=>function($container) {
                    $httpClient=$container->get(Model\AuthorHttpClient::class);
                    return new Model\AuthorRestCollection($httpClient);
                },
                Model\AuthorHttpClient::class=>function($container) {
                    $config=$container->get('config');
                    $client=new Client($config['httpclient']['base_uri'].
                            $config['httpclient']['authors']['route']);
                    $client->setHeaders($config['httpclient']['headers']);
                    $client->setAuth(
                            $config['httpclient']['basic_auth']['user'],
                            $config['httpclient']['basic_auth']['password']
                            );
                    return $client;
                },
            ],
        ];
    }
    
    public function getControllerConfig()
    {
        return [
            'factories'=>[
                Controller\AuthorController::class=>function($container) {
                    return new Controller\AuthorController(
                            $container->get(Model\AuthorRestCollection::class));
                }
            ],
        ];
    }
}
