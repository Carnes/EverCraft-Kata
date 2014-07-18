<?php
class formulaCategory{
    public $description;
    public $type;
    public function __construct($type, $description){
        if($type != formulaType::BestOfCategory && $type != formulaType::Additive)
            throw new Exception("Formula category must have a type");
        if(!is_string($description))
            throw new Exception("Formula category must set a description");
        $this->type = $type;
        $this->description = $description;
    }
}