<?php
namespace Weapon;
include_once ("IWeapon.php");
class weapon implements IWeapon
{
    public $name;

    public $damage;

    public function getDamage()
    {
        return $this->damage;
    }
}