<?php
include_once ("character/character.php");
include_once ("character/alignment.php");
include_once ("character/races/humanRace.php");

class character_Tests implements testInterface
{
    public function initialize() {

    }

    public function itExists(){
        assert(class_exists("character"));
    }

    public function itHasNameProperty(){
        assert(property_exists("character","name"));
    }

    public function itHasAlignmentProperty(){
        assert(property_exists("character","alignment"));
    }

    public function itHasExperiencePoints(){
        assert(property_exists("character","experience"));
    }

    public function alignmentPropertySetIgnoresBadValue(){
        //Arrange
        $c = new character();

        //Act
        $c->alignment = "junk";

        //Assert
        assert($c->alignment != "junk");
    }

    public function alignmentPropertySetUsesGoodValue(){
        //Arrange
        $c = new character();

        //Act
        $c->alignment = "Good";

        //Assert
        assert($c->alignment == "Good");
    }

    public function itHasArmorClassOf10(){
        $c = new character();

        assert($c->armorClass == 10);
    }

    public function ItHasArmorClassOf10PlusDexModifier(){
        $c = new character();
        $c->dexterity+=2;
        assert($c->armorClass == 11);
    }

    public function ItDefaultsToLevel1AtZeroXP()
    {
        $c = new character();
        assert($c->level == 1);
    }

    public function ItHasLevelForEachThousandXP()
    {
        $c = new character();
        $c->experience=12345;
        assert($c->level == 13);
    }

    public function CharWillNotSetNonCharacterClassClass()
    {
        $c = new character();
        $c->addClass("character");
        assert(count($c->class) == 0);
    }

    public function ItCanWieldAWeapon()
    {
        //Arrange
        $c = new character();
        $noWeapon = $c->wieldedWeapon;
        $longsword = equipmentFactory::startForge()
            ->withDamage(5)
            ->withName("longsword")
            ->withType(\Equipment\itemType::$Weapon)
            ->withSubType(\Equipment\weaponSubType::$Longsword)
            ->getEquipment();

        //Act
        $c->wieldedWeapon = $longsword;
        $withWeapon = $c->wieldedWeapon;

        //Assert
        assert($noWeapon == null);
        assert($withWeapon instanceof Equipment\IEquipment);
    }

    public function ItCanWearArmor()
    {
        //Arrange
        $c = new character();
        $armor = equipmentFactory::startForge()
            ->withType(\Equipment\itemType::$Armor)
            ->withSubType(\Equipment\armorSubType::$Plate)
            ->withName("tarnished copper plate armor")
            ->getEquipment();
        $noArmor = $c->equipedArmor;

        //Act
        $c->equipedArmor = $armor;
        $hasArmor = $c->equipedArmor;

        //Assert
        assert($hasArmor == $armor);
    }
}