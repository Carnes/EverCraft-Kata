<?php
namespace Weapon;
include_once ("IWeapon.php");
class weapon implements IWeapon
{
    public $name;
    public $damageFormulas;
    public $attackFormulas;

    public function __construct()
    {
        $this->damageFormulas=array();
        $this->attackFormulas=array();
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

    private function processFormulas($formulas)
    {
        $val = 0;
        foreach($formulas as $formula)
        {
            $result = $formula->execute();
            if(is_numeric($result))
                $val += $result;
        }
        return $val;
    }
}