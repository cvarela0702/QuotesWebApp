<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

use Author\Controller\AuthorController;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => AuthorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation'=>[
        'default'=>[
            [
                'label'=>'Home',
                'route'=>'home',
            ],
            [
                'label'=>'Authors',
                'route'=>'author',
                'pages'=>[
                    [
                        'label'=>'List',
                        'route'=>'author',
                        'action'=>'index',
                    ],
                    [
                        'label'=>'Add',
                        'route'=>'author',
                        'action'=>'add',
                    ],
                    [
                        'label'=>'Edit',
                        'route'=>'author',
                        'action'=>'edit',
                    ],
                    [
                        'label'=>'View',
                        'route'=>'author',
                        'action'=>'view',
                    ],
                    [
                        'label'=>'Delete',
                        'route'=>'author',
                        'action'=>'delete',
                    ],
                ],
            ],
            [
                'label'=>'Quotes',
                'route'=>'quote',
                'pages'=>[
                    [
                        'label'=>'List',
                        'route'=>'quote',
                        'action'=>'index',
                    ],
                    [
                        'label'=>'Add',
                        'route'=>'quote',
                        'action'=>'add',
                    ],
                    [
                        'label'=>'Edit',
                        'route'=>'quote',
                        'action'=>'edit',
                    ],
                    [
                        'label'=>'View',
                        'route'=>'quote',
                        'action'=>'view',
                    ],
                    [
                        'label'=>'Delete',
                        'route'=>'quote',
                        'action'=>'delete',
                    ],
                ],
            ],
        ],
    ],
];
