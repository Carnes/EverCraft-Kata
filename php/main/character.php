<?php
class character
{
    private $alignment;
    private $hitPoints;
    private static $abilityModifierLookup = array(null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5);

    public $name;
    private $armorClass;
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
        switch($property) {
            case "armorClass":
                return $this->getArmorClass();
            case "isAlive":
                return $this->isAlive();
            case "strengthModifier":
                return $this->abilityModifier($this->strength);
            case "dexterityModifier":
                return $this->abilityModifier($this->dexterity);
            case "constitutionModifier":
                return $this->abilityModifier($this->constitution);
            case "wisdomModifier":
                return $this->abilityModifier($this->widsom);
            case "intelligenceModifier":
                return $this->abilityModifier($this->intelligence);
            case "charismaModifier":
                return $this->abilityModifier($this->charisma);
            case "hitPoints":
                return $this->hitPoints;
            case "level":
                return $this->getLevel();
            case "maxHitPoints":
                return $this->getMaxHitPoints();
            case "alignment":
                return $this->alignment;
        }
    }

    public function __set($property, $value){
        switch($property) {
            case "alignment":
                if($value == alignment::Good || $value == alignment::Neutral || $value == alignment::Evil)
                    $this->alignment = $value;
                return;
            case "armorClass":
                $this->armorClass = $value;
                return;
        }
    }

    public function takeDamage($amount)
    {
        $this->hitPoints-=$amount;
    }

    public function getAttackDamage($attackRole)
    {
        $damage = $this->getAttackDamagePerLevel() + $this->getAttackDamageBonus();//$this->strengthModifier;
        if($attackRole==20)
            $damage *= $this->getCriticalHitMultiplier();//2;
        if($damage<1)
            $damage=1;
        return $damage;
    }

    public function getAttackRoleBonus($defender)
    {
        $bonus = 0;

        foreach($this->getAllModifiersForTarget("attack role bonus") as $modifier)
            $bonus += $modifier["method"]($this, $defender);

        return $bonus;
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

    private function getNoClassModifiers()
    {
        $modifiers = array();
        $modifiers[] = array(
            "target"=>"attack damage per level",
            "method"=>function($character){return ceil($character->level/2);},
            //"reason"=>"Base damage per level",
        );
        $modifiers[] = array(
            "target"=>"maxHitPoints per level",
            "method"=>function($character){return (5 * $character->level) + $character->constitutionModifier;}
            //"reason"=>"Base 5 hit points per level plus constitution modifier",
        );
        $modifiers[] = array(
            "target"=>"critical hit multiplier",
            "method"=>function(){return 2;}
            //"reason"=>"Base critical hit multiplier of 2",
        );
        $modifiers[] = array(
            "target"=>"attack role bonus",
            "method"=>function($self, $target){return $self->strengthModifier;}
            //"reason"=>"Base attack role bonus of strength modifier",
        );
        $modifiers[] = array(
            "target"=>"attack damage bonus for ability modifier",
            "method"=>function($character){return $character->strengthModifier;}
            //"reason"=>"Base attack damage bonus for ability modifier",
        );
        return $modifiers;
    }

    private function getArmorClass()
    {
        return $this->armorClass + $this->dexterityModifier;
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

    private function getModifiers()
    {
        $modifiers = $this->getNoClassModifiers();

        foreach($this->class as $class)
        {
            $classModifiers = $class->getModifiers();
            if(is_array($classModifiers))
                $modifiers = array_merge($modifiers,$class->getModifiers());
        }

        return $modifiers;
    }

    private function getBestModifierResultForTarget($target)
    {
        $bestResult = null;
        foreach ($this->getAllModifiersForTarget($target) as $modifier)
                $newResult = $modifier["method"]($this);
                if($bestResult == null || $newResult > $bestResult)
                    $bestResult = $newResult;
        return $bestResult;
    }

    private function getAllModifiersForTarget($target)
    {
        $mods = array();
        foreach ($this->getModifiers() as $modifier)
            if($modifier["target"]== $target)
                $mods[] = $modifier;
        return $mods;
    }


    private function getCriticalHitMultiplier()
    {
        return $this->getBestModifierResultForTarget("critical hit multiplier");
    }

    private function getAttackDamagePerLevel()
    {
        return $this->getBestModifierResultForTarget("attack damage per level");
    }

    private function getMaxHitPoints()
    {
        return $this->getBestModifierResultForTarget("maxHitPoints per level");
    }

    private function getAttackDamageBonus()
    {
        return $this->getBestModifierResultForTarget("attack damage bonus for ability modifier");
    }
}