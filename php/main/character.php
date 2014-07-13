<?php
class character
{
    private $alignment;
    private $hitPoints;

    public $name;
    public $armorClass;
    public $strength;
    public $dexterity;
    public $constitution;
    public $wisdom;
    public $intelligence;
    public $charisma;
    public $experience;

    public function __construct(){
        $this->armorClass = 10;
        $this->strength = 10;
        $this->dexterity = 10;
        $this->constitution = 10;
        $this->wisdom = 10;
        $this->intelligence = 10;
        $this->charisma = 10;
        $this->experience = 0;
        $this->hitPoints = $this->getMaxHitPoints();
    }

    private function isAlive(){
        if($this->hitPoints>0)
            return true;
        return false;
    }

    private static $abilityModifierLookup = array(null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5);
    private function abilityModifier($stat)
    {
        return self::$abilityModifierLookup[$stat];
    }

    private function hitPointsPlusConstitution()
    {
        $bonus = $this->abilityModifier($this->constitution);
        if($bonus < 0)
            $bonus = 0;
        return $bonus + $this->hitPoints;
    }

    private function getLevel()
    {
        $xpLevel = floor($this->experience / 1000);
        return $xpLevel + 1;
    }

    public function __get($property){
        if($property=="isAlive")
            return $this->isAlive();
        if($property=="strengthModifier")
            return $this->abilityModifier($this->strength);
        if($property=="dexterityModifier")
            return $this->abilityModifier($this->dexterity);
        if($property=="hitPoints")
            return $this->hitPointsPlusConstitution();
        if($property=="level")
            return $this->getLevel();
        if($property=="maxHitPoints")
            return $this->getMaxHitPoints();
        return $this->$property;
    }

    private function getMaxHitPoints()
    {
        return (5 * $this->getLevel()) + $this->abilityModifier($this->constitution);
    }

    public function __set($property, $value){
        if($property=="alignment")
            if($value == alignment::Good || $value == alignment::Neutral || $value == alignment::Evil)
                $this->alignment = $value;
    }

    public function takeDamage($amount)
    {
        $this->hitPoints-=$amount;
    }
}