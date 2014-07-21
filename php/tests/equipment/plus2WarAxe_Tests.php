<?php
include_once("equipment/equipmentFactory.php");
include_once("character/character.php");

class plus2WarAxe_Tests implements testInterface
{
    private $axe;
    public function initialize() {
        $this->axe = equipmentFactory::startForge()
            ->withDamage(8)
            ->withAttack(2)
            ->withCriticalMultiplier(3)
            ->withRogueCriticalMultiplier(4)
            ->withName("+2 war axe")
            ->withSubType(\Equipment\weaponSubType::$WarAxe)
            ->withType(\Equipment\itemType::$Weapon)
            ->getEquipment();
    }

    public function ItIsAPieceOfEquipment()
    {
        //Arrange / Act
        $interfaces = class_implements($this->axe);

        //Assert
        assert(in_array("Equipment\IEquipment",$interfaces));
    }

    public function ItIsAWeapon()
    {
        assert($this->axe->type == \Equipment\itemType::$Weapon);
    }

    public function ItIsAWarAxe()
    {
        assert($this->axe->subType == \Equipment\weaponSubType::$WarAxe);
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

    public function ItDoesCriticalDamageOfDamageTimes3()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->axe;
        $defender = new character();
        $defender->experience += 5000;
        $roll = 20;
        $preHP  = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 26);
    }

    public function ItDoesCriticalDamageOfDamageTimes4ForRogue()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->axe;
        $attacker->addClass(availableClasses::Rogue);
        $defender = new character();
        $defender->experience += 5000;
        $roll = 20;
        $preHP  = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 3+32);
    }
}
