<?php
class character
{
    private $alignment;
    private $hitPoints;
    private static $abilityModifierLookup = array(null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5);

    public $name;
    public $armorClass;
    public $strength;
    public $dexterity;
    public $constitution;
    public $wisdom;
    public $intelligence;
    public $charisma;
    public $experience;
    public $class;

    public function __construct(){
        $this->class = array();
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

    public function __get($property){
        if($property=="isAlive")
            return $this->isAlive();
        if($property=="strengthModifier")
            return $this->abilityModifier($this->strength);
        if($property=="dexterityModifier")
            return $this->abilityModifier($this->dexterity);
        if($property=="constitutionModifier")
            return $this->abilityModifier($this->constitution);
        if($property=="wisdomModifier")
            return $this->abilityModifier($this->widsom);
        if($property=="intelligenceModifier")
            return $this->abilityModifier($this->intelligence);
        if($property=="charismaModifier")
            return $this->abilityModifier($this->charisma);
        if($property=="hitPoints")
            return $this->hitPoints;
        if($property=="level")
            return $this->getLevel();
        if($property=="maxHitPoints")
            return $this->getMaxHitPoints();
        return $this->$property;
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

    public function getAttackDamage($attackRole)
    {
        $damage = $this->getAttackDamagePerLevel() + $this->strengthModifier;
        if($attackRole==20)
            $damage *= 2;
        if($damage<1)
            $damage=1;
        return $damage;
    }

    public function addClass($classType)
    {
        $interfaces = class_implements($classType);
        if(!in_array("characterClass",$interfaces))
            return;

        $preMaxHP = $this->getMaxHitPoints();
        
        $this->class[] = new $classType();

        $postMaxHP = $this->getMaxHitPoints();
        $this->hitPoints+=($postMaxHP - $preMaxHP);
    }

    private function isAlive(){
        if($this->hitPoints>0)
            return true;
        return false;
    }

    private function abilityModifier($stat)
    {
        return self::$abilityModifierLookup[$stat];
    }

    private function getLevel()
    {
        $xpLevel = floor($this->experience / 1000);
        return $xpLevel + 1;
    }


    private function getPeasantModifiers()
    {
        $modifiers = array();
        $modifiers[] = array("target"=>"attack damage per level","method"=>function($character){return ceil($character->level/2);});
        $modifiers[] = array("target"=>"maxHitPoints per level","method"=>function($character){return (5 * $character->level) + $character->constitutionModifier;});
        return $modifiers;
    }

    private function getModifiers()
    {
        $modifiers = $this->getPeasantModifiers();

        foreach($this->class as $class)
            $modifiers = array_merge($modifiers,$class->getModifiers());

        return $modifiers;
    }

    private function getBestModifierResultForTarget($target)
    {
        $bestResult = null;
        foreach ($this->getModifiers() as $modifier)
            if($modifier["target"]== $target)
            {
                $newResult = $modifier["method"]($this);
                if($bestResult == null || $newResult > $bestResult)
                    $bestResult = $newResult;
            }
        return $bestResult;
    }

    private function getAttackDamagePerLevel()
    {
        return $this->getBestModifierResultForTarget("attack damage per level");
    }

    private function getMaxHitPoints()
    {
        return $this->getBestModifierResultForTarget("maxHitPoints per level");
    }
}