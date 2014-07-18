<?php
include_once("character/races/orcRace.php");
class orc_Tests implements testInterface
{
    public function initialize(){}

    public function ItExists(){
        assert(class_exists("orcRace"));
    }

    public function ItHasAName()
    {
        $orc = new orcRace();
        assert($orc->getName() == "Orc");
    }

    public function ItHasCorrectInterface()
    {
        $orc = new orcRace();
        $interfaces = class_implements($orc);
        assert(in_array("ICharacterRace",$interfaces));
    }

    public function ItCanBeSetAsCharacterRace()
    {
        //Arrange
        $c = new character();

        //Act
        $c->race = new orcRace();

        //Assert
        assert($c->race instanceof orcRace);
    }

    public function ItGivesPlus2StrengthModifier()
    {
        //Arrange
        $c = new character();
        $defaultStrMod = $c->strengthModifier;

        //Act
        $c->race = new orcRace();
        $orcStrMod = $c->strengthModifier;

        //Assert
        assert($orcStrMod == $defaultStrMod + 2);
    }
}