<?php

namespace Author\Form;

use Zend\Form\Form;

/**
 * Description of AuthorForm
 *
 * @author gabriel
 */
class AuthorForm extends Form
{
    public function __construct($name=null)
    {
        parent::__construct('author');
        
        $this->add([
            'name'=>'entity_id',
            'type'=>'hidden',
        ]);
        
        $this->add([
            'name'=>'first_name',
            'type'=>'text',
            'options'=>[
                'label'=>'First name',
            ],
            'attributes'=>array(
                'class'=>'form-control',
                'placeholder'=>'First name',
            ),
        ]);
        
        $this->add([
            'name'=>'last_name',
            'type'=>'text',
            'options'=>[
                'label'=>'Last name',
            ],
            'attributes'=>array(
                'class'=>'form-control',
                'placeholder'=>'Last name',
            ),
        ]);
        
        $this->add([
            'name'=>'dob',
            'type'=>'date',
            'options'=>[
                'label'=>'Date of birth',
            ],
            'attributes'=>array(
                'class'=>'form-control',
            ),
        ]);
        
        $this->add([
            'name'=>'submit',
            'type'=>'submit',
            'attributes'=>[
                'value'=>'Submit',
                'id'=>'submitbutton',
                'class'=>'btn btn-primary',
            ]
        ]);
        
    }
}
