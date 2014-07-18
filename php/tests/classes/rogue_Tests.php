<?php
include_once ("character/character.php");
include_once ("character/classes/availableClasses.php");
include_once ("character/classes/rogueClass.php");

class rogue_Tests implements testInterface
{
    public function Initialize()
    {

    }

    public function ItExists()
    {
        assert(class_exists("rogueClass"));
    }

    public function ItIsInAvailableClasses()
    {
        //Arrange
        $class = new ReflectionClass("availableClasses");

        //Act / Assert
        assert($class->hasConstant("Rogue"));
        assert(availableClasses::Rogue == "rogueClass");
    }

    public function ItHasANamePropertyOfRogue()
    {
        $fc = new rogueClass();
        assert($fc->getName() == "Rogue");
    }

    public function CharCanSetRogueClass()
    {
        $c = new character();
        $c->addClass(availableClasses::Rogue);
        assert($c->class[0]->getName() == "Rogue");
    }

    public function ItDoesTripleDamageOnCritialHit()
    {
        //Arrange
        $attacker = new character();
        $attacker->addClass(availableClasses::Rogue);
        $defender = new character();
        $roll = 20;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-3);
    }

    public function ItIgnoresDefendersDexterityModToArmorClassOnAttack()
    {
        //Arrange
        $attacker = new character();
        $attacker->addClass(availableClasses::Rogue);
        $defender = new character();
        $defender->dexterity+=2;
        $roll = $defender->armorClass;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }

    public function ItIgnoresDefendersDexterityModToArmorClassOnAttackUnlessDexModIsNegative()
    {
        //Arrange
        $attacker = new character();
        $attacker->addClass(availableClasses::Rogue);
        $defender = new character();
        $defender->dexterity-=2;
        $roll = $defender->armorClass - $defender->dexterityModifier;

        assert($roll == 10);
        assert($defender->armorClass == 9);

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }

    public function ItAddsDexterityModifierToAttacksInsteadOfStrength()
    {
        //Arrange
        $attacker = new character();
        $attacker->addClass(availableClasses::Rogue);
        $defender = new character();
        $attacker->dexterity+=2;
        $roll = $defender->armorClass+1;
        $preHP = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $postHP = $defender->hitPoints;

        //Assert
        assert($postHP == $preHP-2);
    }

}