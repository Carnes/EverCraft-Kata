<?php
include_once("formula/availableFormulaCategories.php");
include_once("characterBaseFormulas.php");
class character
{
    private $alignment;
    private $hitPoints;
    private static $abilityModifierLookup = array(null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5);

    public $name;
    private $baseArmorClass;
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
        $this->baseArmorClass = 10;
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
                $this->baseArmorClass = $value;
                return;
        }
    }

    public function attack($defender, $roll)
    {
        if($roll + $this->getAttackRoleBonus($defender) > $defender->armorClass) {
            $damage = $this->getAttackDamage($defender, $roll);

            $defender->takeDamage($damage);
            $this->experience += 10;
            return true;
        }
        return false;
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

    private function getArmorClass()
    {
        return $this->baseArmorClass + $this->solveFormulaCategory(availableFormulaCategories::$ArmorClassBonusForAbilityModifier, null);
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
        $modifiers = characterBaseFormulas::getFormulas();

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
            return $this->solveAdditiveFormulas($formulas, $target);
        if($category->type == formulaType::BestOfCategory)
            return $this->solveBestOfFormulas($formulas, $target);
    }

    private function solveAdditiveFormulas($formulas, $target)
    {
        $result = 0;
        foreach($formulas as $formula)
            $result += $formula->execute([$this, $target]);
        return $result;
    }

    private function solveBestOfFormulas($formulas, $target)
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