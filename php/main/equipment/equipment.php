<?php
namespace Equipment;
include_once ("IEquipment.php");

class itemType
{
    public static $Unknown = "unknown";
    public static $Weapon = "weapon";
    public static $Armor = "armor";
}

class itemSubType
{
    public static $Unknown = "unknown";
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
        $isWearable = $this->processFormulas(formulaCategories::$EquipRestriction, $wearer, null);
        if($isWearable === false)
            return false;
        return true;
    }

    public function getDamageReduction($wearer, $attacker)
    {
        return $this->processFormulas(formulaCategories::$DamageReduction, $wearer, $attacker);
    }

    public function getDamage($wielder = null, $target = null)
    {
        return $this->processFormulas(formulaCategories::$Damage, $wielder, $target);
    }

    public function getAttack($wielder = null, $target = null)
    {
        return $this->processFormulas(formulaCategories::$Attack, $wielder, $target);
    }

    public function getCriticalMultiplier($wielder = null, $target = null)
    {
        $multiplier = $this->processFormulas(formulaCategories::$CriticalMultiplier, $wielder, $target);
        return ($multiplier == 0) ? 1 : $multiplier;
    }

    public function getArmorClass($wearer = null, $attacker = null)
    {
        return $this->processFormulas(formulaCategories::$ArmorClass, $wearer, $attacker);
    }

    private function getFormulasForCategory($category)
    {
        $formulas = array();
        foreach($this->formulas as $formula)
            if($formula->category == $category)
                $formulas[] = $formula;
        return $formulas;
    }

    private function processAdditiveFormulas($formulas, $wielder, $target)
    {
        $val = 0;
        foreach($formulas as $formula)
        {
            $result = $formula->execute([$wielder, $target]);
            if(is_numeric($result))
                $val += $result;
        }
        return $val;
    }

    private function processBestOfFormulas($formulas, $wielder, $target)
    {
        $best = null;
        foreach($formulas as $formula)
        {
            $result = $formula->execute([$wielder, $target]);
            if(is_numeric($result) && ($best == null || $result > $best ))
                $best = $result;
        }
        if($best == null)
            $best = 0;
        return $best;
    }

    private function processHasTrueFormulas($formulas, $wielder, $target)
    {
        $isTrue = null;
        foreach($formulas as $formula)
        {
            $result = $formula->execute([$wielder, $target]);
            if($result===true)
                return true;
            if($result===false)
                $isTrue = false;
        }
        return $isTrue;
    }

    private function processFormulas($category, $wielder, $target)
    {
        $formulas = $this->getFormulasForCategory($category);
        if($category->type == formulaType::Additive)
            return $this->processAdditiveFormulas($formulas, $wielder, $target);
        if($category->type == formulaType::BestOf)
            return $this->processBestOfFormulas($formulas, $wielder, $target);
        if($category->type == formulaType::HasTrue)
            return $this->processHasTrueFormulas($formulas, $wielder, $target);
    }
}