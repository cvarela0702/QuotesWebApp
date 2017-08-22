<?php

namespace Quote\Model;

/**
 * Description of Quote
 *
 * @author gabriel
 */
class Quote {
    public $entity_id;
    public $author_id;
    public $quote;
    public $location;
    public $created_at;
    
    public function exchangeArray($data)
    {
        $this->entity_id=!empty($data['entity_id']) ? $data['entity_id'] : null;
        $this->author_id=!empty($data['author_id']) ? $data['author_id'] : null;
        $this->quote=!empty($data['quote']) ? $data['quote'] : null;
        $this->location=!empty($data['location']) ? $data['location'] : null;
        $this->created_at=!empty($data['created_at']) ? $data['created_at'] : null;
    }
    
    public function getArrayCopy()
    {
        $data=[];
        $data['entity_id']=!empty($this->entity_id) ? $this->entity_id : null;
        $data['author_id']=!empty($this->author_id) ? $this->author_id : null;
        $data['quote']=!empty($this->quote) ? $this->quote : null;
        $data['location']=!empty($this->location) ? $this->location : null;
        $data['created_at']=!empty($this->created_at) ? $this->created_at : null;
        return $data;
    }
}
