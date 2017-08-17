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
                    $config=$container->get('config');
                    return new Model\AuthorRestCollection($httpClient,$config['httpclient']);
                },
                Model\AuthorHttpClient::class=>function() {
                    return new Client();
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
