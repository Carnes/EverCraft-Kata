<?php
include_once("character/races/elfRace.php");

class elf_Tests implements testInterface
{
    public function initialize(){}


    public function ItExists(){
        assert(class_exists("elfRace"));
    }

    public function ItHasAName()
    {
        $elf = new elfRace();
        assert($elf->getName() == "Elf");
    }

    public function ItHasCorrectInterface()
    {
        $elf = new elfRace();
        $interfaces = class_implements($elf);
        assert(in_array("ICharacterRace",$interfaces));
    }

    public function ItCanBeSetAsCharacterRace()
    {
        //Arrange
        $c = new character();

        //Act
        $c->race = new elfRace();

        //Assert
        assert($c->race instanceof elfRace);
    }

    public function ItGivesPlus1DexterityModifier()
    {
        //Arrange
        $c = new character();
        $defaultDexMod = $c->dexterityModifier;

        //Act
        $c->race = new elfRace();
        $elfDexMod = $c->dexterityModifier;

        //Assert
        assert($elfDexMod == $defaultDexMod + 1);
    }

    public function ItGivesMinus1ConstitutionModifier()
    {
        //Arrange
        $c = new character();
        $defaultConMod = $c->constitutionModifier;

        //Act
        $c->race = new elfRace();
        $elfConMod = $c->constitutionModifier;

        //Assert
        assert($elfConMod == $defaultConMod - 1);
    }

    public function ItGivesPlus1ToCriticalHitRollBonus()
    {
        //Arrange
        $attacker = new character();
        $attacker->race = new elfRace();
        $defender = new character();
        $preHP = $defender->hitPoints;

        //Act
        $attacker->attack($defender,19);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 2);
    }

    public function ItGetsPlus2ACWhenDefendingAgainstOrcs()
    {
        //Arrange
        $attacker = new character();
        $attacker->race = new orcRace();
        $defender = new character();
        $defender->race = new elfRace();
        $roll = $defender->armorClass;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === false);
    }
}