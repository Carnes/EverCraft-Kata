<?php
include_once("IWeapon.php");

class longSword implements IWeapon
{
    public function getDamage()
    {
        return 5;
    }
}