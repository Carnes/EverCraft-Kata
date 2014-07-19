<?php
namespace Weapon;
include_once ("IWeapon.php");
class weapon implements IWeapon
{
    public $name;
    public $formulas;

    public function __construct()
    {
        $this->formulas=array();
        $this->name = "Unknown";
    }

    public function getDamage()
    {
        $dmg = 0;
        foreach($this->formulas as $formula)
        {
            $result = $formula->execute();
            if(is_numeric($result))
                $dmg += $result;
        }
        return $dmg;
    }
}