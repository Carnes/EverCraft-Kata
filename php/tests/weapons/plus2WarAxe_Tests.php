<?php
include_once("weapons/weaponFactory.php");
include_once("character/character.php");

class plus2WarAxe_Tests implements testInterface
{
    private $axe;
    public function initialize() {
        $this->axe = weaponFactory::startForge()->withDamage(8)->withAttack(2)->withName("+2 war axe")->getWeapon();
    }

    public function ItIsAWeapon()
    {
        //Arrange / Act
        $interfaces = class_implements($this->axe);

        //Assert
        assert(in_array("Weapon\IWeapon",$interfaces));
    }

    public function ItHasName()
    {
        assert($this->axe->name == "+2 war axe");
    }

    public function ItDoes8PointsOfDamage()
    {
        assert($this->axe->getDamage()==8);
    }

    public function ItDoesPlus2ToAttack()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->axe;
        $defender = new character();
        $roll = $defender->armorClass -1;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }
}
