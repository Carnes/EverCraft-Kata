<?php
include_once("weapons/longSword.php");
include_once("weapons/IWeapon.php");

class longSword_Tests implements testInterface
{
    public function initialize() {}

    public function ItImplementsIWeapon()
    {
        $interfaces = class_implements("longSword");

        assert(in_array("IWeapon",$interfaces));
    }

    public function ItDoes5Damage()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $defender->experience+=10000;
        $attacker->wieldedWeapon = new longsword();
        $preHP = $defender->hitPoints;
        $attackBaseDmg = 1;

        //Act
        $attacker->attack($defender,19);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == $attackBaseDmg + 5);
    }
}