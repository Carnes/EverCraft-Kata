<?php
include_once("character/races/dwarfRace.php");
include_once("character/races/orcRace.php");

class dwarf_Tests implements testInterface
{
    public function initialize(){}


    public function ItExists(){
        assert(class_exists("dwarfRace"));
    }

    public function ItHasAName()
    {
        $dwarf = new dwarfRace();
        assert($dwarf->getName() == "Dwarf");
    }

    public function ItHasCorrectInterface()
    {
        $dwarf = new dwarfRace();
        $interfaces = class_implements($dwarf);
        assert(in_array("ICharacterRace",$interfaces));
    }

    public function ItCanBeSetAsCharacterRace()
    {
        //Arrange
        $c = new character();

        //Act
        $c->race = new dwarfRace();

        //Assert
        assert($c->race instanceof dwarfRace);
    }

    public function ItGivesPlus1ConstitutionModifier()
    {
        //Arrange
        $c = new character();
        $defaultConMod = $c->constitutionModifier;

        //Act
        $c->race = new dwarfRace();
        $dwarfConMod = $c->constitutionModifier;

        //Assert
        assert($dwarfConMod == $defaultConMod + 1);
    }

    public function ItGivesDoubleConstitutionModifierBonusForHitPointsPerLevel()
    {
        //Arrange
        $c = new character();
        $c->race = new dwarfRace();

        //Act
        $level1hp = $c->maxHitPoints;
        $c->experience += 1000;
        $level2hp = $c->maxHitPoints;

        //Assert
        assert($level1hp == 7);
        assert($level2hp == 12);
    }

    public function ItDoesPlus2AttackToOrc()
    {
        //Arrange
        $dwarf = new character();
        $dwarf->race = new dwarfRace();
        $orc = new character();
        $orc->race = new orcRace();
        $roll = $orc->armorClass - 1;

        //Act
        $isAttackSuccessful = $dwarf->attack($orc, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }
}