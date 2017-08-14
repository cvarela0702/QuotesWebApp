<?php
namespace Author;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router'=> [
        'routes'=> [
            'author'=>[
                'type'=> Segment::class,
                'options'=>[
                    'route'=>'/author[/:action[/:id]]',
                    'constraints'=> [
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'=>'[0-9]+',
                    ],
                    'defaults'=>[
                        'controller'=>Controller\AuthorController::class,
                        'action'=>'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];

