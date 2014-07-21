<?php
namespace Equipment;
include_once ("IEquipment.php");

class itemType
{
    public static $Unknown = "unknown";
    public static $Weapon = "weapon";
    public static $Armor = "armor";
    public static $Shield = "shield";
    public static $Ring = "ring";
    public static $Belt = "belt";
}

class ringSubType
{
    public static $Magic = "magic";
}

class beltSubType
{
    public static $Magic = "magic";
}

class itemSubType
{
    public static $Unknown = "unknown";
}

class shieldSubType
{
    public static $Wood = "wood";
}

class weaponSubType
{
    public static $WarAxe = "war axe";
    public static $Nunchucks = "nunchaku";
    public static $Longsword = "longsword";
}

class armorSubType
{
    public static $Plate = "plate";
    public static $Leather = "leather";
    public static $ChainMail = "chainmail";
}

class equipment implements IEquipment
{
    public $name;
    public $type;
    public $subType;
    private $formulas;

    public function __construct()
    {
        $this->formulas=array();
        $this->name = "Unknown";
        $this->subType = itemSubType::$Unknown;
        $this->type = itemType::$Unknown;
    }

    public function addFormula($formula)
    {
        if($formula instanceof formula)
            $this->formulas[] = $formula;
    }

    public function isEquipable($wearer)
    {
        $isWearable = $this->processFormulas(formulaCategories::$EquipRestriction, [$wearer]);
        if($isWearable === false)
            return false;
        return true;
    }

    public function getAbilityModifier($abilityName)
    {
        return $this->processFormulas(formulaCategories::$AbilityModifier, [$abilityName]);
    }

    public function getDamageReduction($wearer, $attacker)
    {
        return $this->processFormulas(formulaCategories::$DamageReduction, [$wearer, $attacker]);
    }

    public function getDamage($wielder = null, $target = null)
    {
        return $this->processFormulas(formulaCategories::$Damage, [$wielder, $target]);
    }

    public function getAttack($wielder = null, $target = null)
    {
        return $this->processFormulas(formulaCategories::$Attack, [$wielder, $target]);
    }

    public function getCriticalMultiplier($wielder = null, $target = null)
    {
        $multiplier = $this->processFormulas(formulaCategories::$CriticalMultiplier, [$wielder, $target]);
        return ($multiplier == 0) ? 1 : $multiplier;
    }

    public function getArmorClass($wearer = null, $attacker = null)
    {
        return $this->processFormulas(formulaCategories::$ArmorClass, [$wearer, $attacker]);
    }

    private function getFormulasForCategory($category)
    {
        $formulas = array();
        foreach($this->formulas as $formula)
            if($formula->category == $category)
                $formulas[] = $formula;
        return $formulas;
    }

    private function processAdditiveFormulas($formulas, $args)
    {
        $val = 0;
        foreach($formulas as $formula)
        {
            $result = $formula->execute($args);
            if(is_numeric($result))
                $val += $result;
        }
        return $val;
    }

    private function processBestOfFormulas($formulas, $args)
    {
        $best = null;
        foreach($formulas as $formula)
        {
            $result = $formula->execute($args);
            if(is_numeric($result) && ($best == null || $result > $best ))
                $best = $result;
        }
        if($best == null)
            $best = 0;
        return $best;
    }

    private function processHasTrueFormulas($formulas, $args)
    {
        $isTrue = null;
        foreach($formulas as $formula)
        {
            $result = $formula->execute($args);
            if($result===true)
                return true;
            if($result===false)
                $isTrue = false;
        }
        return $isTrue;
    }

    private function processFormulas($category, $args)
    {
        $formulas = $this->getFormulasForCategory($category);
        if($category->type == formulaType::Additive)
            return $this->processAdditiveFormulas($formulas, $args);
        if($category->type == formulaType::BestOf)
            return $this->processBestOfFormulas($formulas, $args);
        if($category->type == formulaType::HasTrue)
            return $this->processHasTrueFormulas($formulas, $args);
    }
}