<?php
namespace Weapon;
include_once ("IWeapon.php");
class weapon implements IWeapon
{
    public $name;
    public $damageFormulas;
    public $attackFormulas;
    public $criticalFormulas;

    public function __construct()
    {
        $this->damageFormulas=array();
        $this->attackFormulas=array();
        $this->criticalFormulas=array();
        $this->name = "Unknown";
    }

    public function getDamage()
    {
        return $this->processFormulas($this->damageFormulas);
    }

    public function getAttack()
    {
        return $this->processFormulas($this->attackFormulas);
    }

    public function getCriticalMultiplier($wielder)
    {
        $multiplier = $this->processFormulas($this->criticalFormulas, $wielder);
        return ($multiplier == 0) ? 1 : $multiplier;
    }

    private function processFormulas($formulas, $wielder = null)
    {
        $val = 0;
        foreach($formulas as $formula)
        {
            $result = $formula->execute([$wielder]);
            if(is_numeric($result))
                $val += $result;
        }
        return $val;
    }
}