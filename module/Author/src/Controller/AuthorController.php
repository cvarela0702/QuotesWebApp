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
    
    public function editAction()
    {
        $entity_id=(int) $this->params()->fromRoute('id', 0);
        if($entity_id===0)
        {
            return $this->redirect()->toRoute('author',['action'=>'add']);
        }
        
        try {
            $author=$this->restcollection->getAuthor($entity_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('author', ['action'=>'index']);
        }
        
        $form=new AuthorForm();
        $form->bind($author);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request=$this->getRequest();
        $viewData=['entity_id'=>$entity_id,'form'=>$form];
        
        if(!$request->isPost())
        {
            return $viewData;
        }
        
        $form->setInputFilter($author->getInputFilter());
        $form->setData($request->getPost());
        
        if(!$form->isValid())
        {
            return $viewData;
        }
        
        $this->restcollection->saveAuthor($author);
        
        return $this->redirect()->toRoute('author', ['action'=>'index']);
    }
}
