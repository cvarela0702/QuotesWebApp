<?php

namespace Author\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Author\Model\AuthorRestCollection;

use Author\Model\Author;
use Author\Form\AuthorForm;

use Quote\Model\QuoteRestCollection;

/**
 * Description of Author
 *
 * @author gabriel
 */
class AuthorController extends AbstractActionController
{
    private $restcollection;
    private $quoterestcollection;
    
    public function __construct(AuthorRestCollection $restcollection, QuoteRestCollection $quoterestcollection) {
        $this->restcollection=$restcollection;
        $this->quoterestcollection=$quoterestcollection;
    }
    
    public function indexAction() {
        $paginator=$this->restcollection->fetchAll(true);
        
        $page=(int) $this->params()->fromQuery('page',1);
        $page= ($page<1)?1:$page;
        $paginator->setCurrentPageNumber($page);
        
        $paginator->setItemCountPerPage(10);
        return new ViewModel([
            'paginator'=>$paginator,
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
    
    public function deleteAction()
    {
        $entity_id=(int) $this->params()->fromRoute('id', 0);
        if($entity_id===0)
        {
            return $this->redirect()->toRoute('author');
        }
        
        $request=$this->getRequest();
        if($request->isPost())
        {
            $del=$request->getPost('del','No');
            
            if($del=='Yes')
            {
                $id=(int) $request->getPost('entity_id');
                $this->restcollection->deleteAuthor($id);
            }
            return $this->redirect()->toRoute('author');
        }
        return [
            'entity_id'=>$entity_id,
            'author'=>$this->restcollection->getAuthor($entity_id),
        ];
    }
    
    public function viewAction()
    {
        $entity_id=(int) $this->params()->fromRoute('id', 0);
        if($entity_id===0)
        {
            return $this->redirect()->toRoute('author');
        }
        
        try {
            $author=$this->restcollection->getAuthor($entity_id);
            
            $quotes_by_author=$this->quoterestcollection->fetchAll(null, ['author_id'=>$entity_id]);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('author', ['action'=>'index']);
        }
        return [
            'author'=>$author,
            'quotes_by_author'=>$quotes_by_author,
        ];
    }
}
