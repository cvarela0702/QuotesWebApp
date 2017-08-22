<?php
namespace Quote;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router'=>[
        'routes'=>[
            'quote'=>[
                'type'=> Segment::class,
                'options'=>[
                    'route'=>'/quote[/:action[/:id]]',
                    'constraints'=> [
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'=>'[0-9]+',
                    ],
                    'defaults'=>[
                        'controller'=>Controller\QuoteController::class,
                        'action'=>'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers'=>[
        'factories'=>[
            Controller\QuoteController::class=> InvokableFactory::class,
        ]
    ],
    'view_manager'=>[
        'template_path_stack'=>[
            'quote'=>__DIR__.'/../view',
        ],
    ],
];

