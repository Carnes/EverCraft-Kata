<?php
include_once("weapons/weaponFactory.php");
include_once("weapons/IWeapon.php");

class longSword_Tests implements testInterface
{
    public function initialize() {}

    public function ItImplementsIWeapon()
    {
        $longsword = weaponFactory::startForge()->getWeapon();

        $interfaces = class_implements($longsword);

        assert(in_array("Weapon\IWeapon",$interfaces));
    }

    public function ItHasAName()
    {
        $longsword = weaponFactory::startForge()->withDamage(5)->withName("longsword")->getWeapon();

        assert($longsword->name == "longsword");
    }

    public function ItDoes5Damage()
    {
        //Arrange
        $longsword = weaponFactory::startForge()->withDamage(5)->getWeapon();

        $attacker = new character();
        $defender = new character();
        $defender->experience+=10000;
        $attacker->wieldedWeapon = $longsword;
        $preHP = $defender->hitPoints;
        $attackBaseDmg = 1;

        //Act
        $attacker->attack($defender,19);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == $attackBaseDmg + 5);
    }
}