<?php

namespace Quote;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Description of Module
 *
 * @author gabriel
 */
class Module implements ConfigProviderInterface
{
    public function getConfig() {
        return include __DIR__.'/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return [
            'factories'=>[
                Model\QuoteRestCollection::class=>function($container) {
                    $httpClient=$container->get(Model\QuoteHttpClient::class);
                    $config=$container->get('config');
                    return new Model\QuoteRestCollection($httpClient,$config['httpclient']);
                },
                Model\QuoteHttpClient::class=>function() {
                    return new Client();
                },
            ],
        ];
    }
}
