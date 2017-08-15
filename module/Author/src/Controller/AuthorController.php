<?php

namespace Author\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Author\Model\AuthorRestCollection;

use Author\Model\Author;
use Author\Form\AuthorForm;


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
    
    public function addAction()
    {
        $form=new AuthorForm();
        $form->get('submit')
                ->setValue('Add');
        
        $request=$this->getRequest();
        
        if(!$request->isPost())
        {
            return ['form'=>$form];
        }
        
        $author=new Author();
        $form->setInputFilter($author->getInputFilter());
        $form->setData($request->getPost());
        
        if(!$form->isValid())
        {
            return ['form'=>$form];
        }
        
        $author->exchangeArray($form->getData());
        
        $this->restcollection->saveAuthor($author);
        return $this->redirect()->toRoute('author');
    }
}
