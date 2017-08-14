<?php

namespace Author\Model;

/**
 * Description of Author
 *
 * @author gabriel
 */
class Author {
    public $entity_id;
    public $first_name;
    public $last_name;
    public $dob;
    public $created_at;
    
    public function exchangeArray($data)
    {
        $this->entity_id=!empty($data['entity_id']) ? $data['entity_id'] : null;
        $this->first_name=!empty($data['first_name']) ? $data['first_name'] : null;
        $this->last_name=!empty($data['last_name']) ? $data['last_name'] : null;
        $this->dob=!empty($data['dob']) ? $data['dob'] : null;
        $this->created_at=!empty($data['created_at']) ? $data['created_at'] : null;
    }
}
