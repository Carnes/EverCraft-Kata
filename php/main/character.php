<?php
class character
{
    public $name;
    private $alignment;
    public $armorClass;
    public $hitPoints;

    public function __construct(){
        $this->armorClass = 10;
        $this->hitPoints = 5;
    }

    public function __get($property){
        return $this->$property;
    }

    public function __set($property, $value){
        if($property=="alignment")
            if($value == alignment::Good || $value == alignment::Neutral || $value == alignment::Evil)
                $this->alignment = $value;
    }
}