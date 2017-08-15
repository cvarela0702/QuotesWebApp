<?php

namespace Author\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

/**
 * Description of Author
 *
 * @author gabriel
 */
class Author implements InputFilterAwareInterface
{
    public $entity_id;
    public $first_name;
    public $last_name;
    public $dob;
    public $created_at;
    
    private $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->entity_id=!empty($data['entity_id']) ? $data['entity_id'] : null;
        $this->first_name=!empty($data['first_name']) ? $data['first_name'] : null;
        $this->last_name=!empty($data['last_name']) ? $data['last_name'] : null;
        $this->dob=!empty($data['dob']) ? $data['dob'] : null;
        $this->created_at=!empty($data['created_at']) ? $data['created_at'] : null;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new DomainException(sprintf(
                '%s does not allow injection of an alternate input filter',
                __CLASS__
                ));
    }
    
    public function getInputFilter() {
        if($this->inputFilter)
        {
            return $this->inputFilter;
        }
        
        $inputFilter=new InputFilter();
        
        $inputFilter->add([
            'name'=>'entity_id',
            'required'=>true,
            'filters'=>[
                ['name'=> ToInt::class],
            ],
        ]);
        
        $inputFilter->add([
            'name'=>'first_name',
            'required'=>true,
            'filters'=>[
                ['name'=> StripTags::class],
                ['name'=> StringTrim::class],
            ],
            'validators'=>[
                [
                    'name' => StringLength::class,
                    'options'=>[
                        'encoding'=>'UTF-8',
                        'min'=>1,
                        'max'=>30,
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name'=>'last_name',
            'required'=>true,
            'filters'=>[
                ['name'=> StripTags::class],
                ['name'=> StringTrim::class],
            ],
            'validators'=>[
                [
                    'name' => StringLength::class,
                    'options'=>[
                        'encoding'=>'UTF-8',
                        'min'=>1,
                        'max'=>30,
                    ],
                ],
            ],
        ]);
        
        $this->inputFilter=$inputFilter;
        return $this->inputFilter;
    }
    
    public function getData()
    {
        $data=[];
        $data['entity_id']=!empty($this->entity_id) ? $this->entity_id : null;
        $data['first_name']=!empty($this->first_name) ? $this->first_name : null;
        $data['last_name']=!empty($this->last_name) ? $this->last_name : null;
        $data['dob']=!empty($this->dob) ? $this->dob : null;
        $data['created_at']=!empty($this->created_at) ? $this->created_at : null;
        return $data;
    }
}
