<?php
namespace Weapon;
include_once ("IWeapon.php");
class weapon implements IWeapon
{
    public $name;
    private $formulas;

    public function __construct()
    {
        $this->formulas=array();
        $this->name = "Unknown";
    }

    public function addFormula($formula)
    {
        if($formula instanceof formula)
            $this->formulas[] = $formula;
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

    private function processFormulas($category, $wielder, $target)
    {
        $formulas = $this->getFormulasForCategory($category);
        if($category->type == formulaType::Additive)
            return $this->processAdditiveFormulas($formulas, $wielder, $target);
        if($category->type == formulaType::BestOf)
            return $this->processBestOfFormulas($formulas, $wielder, $target);

    }
}