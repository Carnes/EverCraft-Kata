<?php
include_once("character/character.php");
include_once("character/races/halflingRace.php");

class halfling_Tests implements testInterface
{
    public function initialize(){}


    public function ItExists(){
        assert(class_exists("halflingRace"));
    }

    public function ItHasAName()
    {
        $halfling = new halflingRace();
        assert($halfling->getName() == "Halfling");
    }

    public function ItHasCorrectInterface()
    {
        $halfling = new halflingRace();
        $interfaces = class_implements($halfling);
        assert(in_array("ICharacterRace",$interfaces));
    }

    public function ItCanBeSetAsCharacterRace()
    {
        //Arrange
        $c = new character();

        //Act
        $c->race = new halflingRace();

        //Assert
        assert($c->race instanceof halflingRace);
    }

    public function ItGivesPlus1DexterityModifier()
    {
        //Arrange
        $c = new character();
        $defaultDexMod = $c->dexterityModifier;

        //Act
        $c->race = new halflingRace();
        $halflingDexMod = $c->dexterityModifier;

        //Assert
        assert($halflingDexMod == $defaultDexMod + 1);
    }

    public function ItGivesMinus1StrengthModifier()
    {
        //Arrange
        $c = new character();
        $defaultStrMod = $c->strengthModifier;

        //Act
        $c->race = new halflingRace();
        $halflingStrMod = $c->strengthModifier;

        //Assert
        assert($halflingStrMod == $defaultStrMod - 1);
    }

    public function ItGetsPlus2ACWhenDefendingAgainstNonHalfling()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $defender->race = new halflingRace();
        $roll = $defender->armorClass +2;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === false);
    }
}