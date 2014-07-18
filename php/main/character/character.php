<?php
include_once("formula/availableFormulaCategories.php");
include_once("formula/formula.php");
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
                return $this->abilityModifier($this->wisdom);
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

    public function getAttackDamage($defender, $attackRole)
    {
        $damage = $this->getAttackDamagePerLevel() + $this->getAttackDamageBonus($defender);
        if($attackRole==20)
            $damage *= $this->getCriticalHitMultiplier($defender);
        if($damage<1)
            $damage=1;
        return $damage;
    }

    public function getAttackRoleBonus($defender)
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$AttackRoleBonus, $defender);
    }

    public function addClass($classType)
    {
        $interfaces = class_implements($classType);
        if(!in_array("ICharacterClass",$interfaces))
            return;

        $preMaxHP = $this->getMaxHitPoints();

        $this->class[] = new $classType();

        $postMaxHP = $this->getMaxHitPoints();
        $this->hitPoints+=($postMaxHP - $preMaxHP);
    }

    private function getNoClassModifiers()
    {
        $mod = array();

        $mod[] = new formula(
            availableFormulaCategories::$AttackDamagePerLevel,
            function($character){return ceil($character->level/2);},
            "Base damage per level"
        );
        $mod[] = new formula(
            availableFormulaCategories::$MaxHitPointsPerLevel,
            function($character){return (5 * $character->level) + $character->constitutionModifier;},
            "Base 5 hit points per level plus constitution modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$CriticalHitMultiplier,
            function(){return 2;},
            "Base critical hit multiplier of 2"
        );
        $mod[] = new formula(
            availableFormulaCategories::$AttackRoleBonus,
            function($self, $target){return $self->strengthModifier;},
            "Base attack role bonus of strength modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$AttackDamageForAbilityModifier,
            function($character){return $character->strengthModifier;},
            "Base attack damage bonus for ability modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$ArmorClassBonusForAbilityModifier,
            function($character){return $character->dexterityModifier;},
            "Base armor class bonus for ability modifier"
        );
        return $mod;
    }

    private function getArmorClass()
    {
        return $this->armorClass + $this->solveFormulaCategory(availableFormulaCategories::$ArmorClassBonusForAbilityModifier, null);
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

    private function getAllModifiersForTarget($target)
    {
        $mods = array();
        foreach ($this->getModifiers() as $modifier)
            if($modifier->category== $target)
                $mods[] = $modifier;
        return $mods;
    }

    private function solveFormulaCategory($category, $target)
    {
        $formulas = $this->getAllModifiersForTarget($category);
        if($category->type == formulaType::Additive)
        {
            $result = 0;
            foreach($formulas as $formula)
            $result += $formula->execute([$this, $target]);
            return $result;
        }
        if($category->type == formulaType::BestOfCategory)
        {
            $bestResult = null;
            foreach ($formulas as $formula)
            {
                $newResult = $formula->execute([$this, $target]);
                if($bestResult == null || $newResult > $bestResult)
                    $bestResult = $newResult;
            }
            if ($bestResult == null)
                return 0;
            return $bestResult;
        }
    }


    private function getCriticalHitMultiplier($defender)
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$CriticalHitMultiplier, $defender);
    }

    private function getAttackDamagePerLevel()
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$AttackDamagePerLevel, null);
    }

    private function getMaxHitPoints()
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$MaxHitPointsPerLevel, null);
    }

    private function getAttackDamageBonus($target)
    {
        $bonus = 0;
        $bonus += $this->solveFormulaCategory(availableFormulaCategories::$AttackDamageForAbilityModifier,null);
        $bonus += $this->solveFormulaCategory(availableFormulaCategories::$AttackDamageBonus, $target);

        return $bonus;
    }
}