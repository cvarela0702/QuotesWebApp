<?php

namespace Author\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Author\Model\AuthorRestCollection;


/**
 * Description of Author
 *
 * @author gabriel
 */
class AuthorController extends AbstractActionController
{
    private $restcollection;
    
    public function __construct(AuthorRestCollection $restcollection) {
        $this->restcollection=$restcollection;
    }
    
    public function indexAction() {
        
        return new ViewModel([
            'authors'=>$this->restcollection->fetchAll(),
        ]);
    }
}
