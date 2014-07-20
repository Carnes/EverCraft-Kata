<?php
include_once("weapons/weaponFactory.php");
include_once("weapons/IWeapon.php");

class longSword_Tests implements testInterface
{
    private $longsword;

    public function initialize() {
        $this->longsword = weaponFactory::startForge()
            ->withDamage(5)
            ->withName("longsword")
            ->withSubType(\Weapon\itemSubType::Longsword)
            ->getWeapon();
    }

    public function ItImplementsIWeapon()
    {
        $interfaces = class_implements($this->longsword);

        assert(in_array("Weapon\IWeapon",$interfaces));
    }

    public function ItHasAName()
    {
        assert($this->longsword->name == "longsword");
    }

    public function ItIsALongsword()
    {
        assert($this->longsword->subType == \Weapon\itemSubType::Longsword);
    }

    public function ItDoes5Damage()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $defender->experience+=10000;
        $attacker->wieldedWeapon = $this->longsword;
        $preHP = $defender->hitPoints;
        $attackBaseDmg = 1;

        //Act
        $attacker->attack($defender,19);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == $attackBaseDmg + 5);
    }
}