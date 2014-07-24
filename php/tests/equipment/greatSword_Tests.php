<?php
include_once("equipment/equipmentFactory.php");
include_once("character/character.php");

class greatSword_Tests implements testInterface
{
    private $greatsword;
    public function initialize() {
        $this->greatsword = equipmentFactory::startForge()
            ->withName("Greatsword")
            ->withSubType(\Equipment\weaponSubType::$Greatsword)
            ->withType(\Equipment\itemType::$Weapon)
            ->withDamage(10)
            ->withRestrictionForOnlyTheseClasses(["Fighter"])
            ->getEquipment();
    }

    public function ItIsAPieceOfEquipment()
    {
        //Arrange / Act
        $interfaces = class_implements($this->greatsword);

        //Assert
        assert(in_array("Equipment\IEquipment",$interfaces));
    }

    public function ItIsAWeapon()
    {
        assert($this->greatsword->type == \Equipment\itemType::$Weapon);
    }

    public function ItIsAGreatSword()
    {
        assert($this->greatsword->subType == \Equipment\weaponSubType::$Greatsword);
    }

    public function ItHasName()
    {
        assert($this->greatsword->name == "Greatsword");
    }

    public function ItDoes10Dmg()
    {
        assert($this->greatsword->getDamage() == 10);
    }

    public function ItCanOnlyBeWieldedByAFighterClass()
    {
        //Arrange
        $c = new character();
        $noWeapon = $c->equipedWeapon;

        //Act
        $c->equip($this->greatsword);
        $equipedWeapon = $c->equipedWeapon;

        //Assert
        assert($equipedWeapon == $noWeapon);
    }

    private function getRandomShield()
    {
        return equipmentFactory::startForge()
            ->withName("wood shield")
            ->withType(\Equipment\itemType::$Shield)
            ->withSubType(\Equipment\shieldSubType::$Wood)
            ->withArmorClass(4)
            ->getEquipment();
    }

    public function ItRequiresTwoHandsToWield()
    {
        //Arrange
        $wielder = new character();
        $shield = $this->getRandomShield();

        //Act
        $wielder->equip($shield);
        //$wielder->unequip($shield);
        $wielder->equip($this->greatsword);

        //Assert
        assert(count($wielder->inventory)==1);
        //assert($wielder->inventory[0] == $shield);
    }

}