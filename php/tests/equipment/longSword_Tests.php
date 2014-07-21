<?php
include_once("equipment/equipmentFactory.php");
include_once("equipment/IEquipment.php");

class longSword_Tests implements testInterface
{
    private $longsword;

    public function initialize() {
        $this->longsword = equipmentFactory::startForge()
            ->withDamage(5)
            ->withName("longsword")
            ->withSubType(\Equipment\weaponSubType::$Longsword)
            ->withType(\Equipment\itemType::$Weapon)
            ->getEquipment();
    }

    public function ItIsAPieceOfEquipment()
    {
        //Arrange / Act
        $interfaces = class_implements($this->longsword);

        //Assert
        assert(in_array("Equipment\IEquipment",$interfaces));
    }

    public function ItIsAWeapon()
    {
        assert($this->longsword->type == \Equipment\itemType::$Weapon);
    }

    public function ItHasAName()
    {
        assert($this->longsword->name == "longsword");
    }

    public function ItIsALongsword()
    {
        assert($this->longsword->subType == \Equipment\weaponSubType::$Longsword);
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