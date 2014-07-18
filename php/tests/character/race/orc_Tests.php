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

    public function ItHasMinus1ToIntelligence()
    {
        //Arrange
        $c = new character();
        $defaultIntMod = $c->intelligenceModifier;

        //Act
        $c->race = new orcRace();
        $orcIntMod = $c->intelligenceModifier;

        //Assert
        assert($orcIntMod == $defaultIntMod -1);
    }

    public function ItHasMinus1ToWisdom()
    {
        //Arrange
        $c = new character();
        $defaultWisMod = $c->wisdomModifier;

        //Act
        $c->race = new orcRace();
        $orcWisMod = $c->wisdomModifier;

        //Assert
        assert($orcWisMod == $defaultWisMod -1);
    }

    public function ItHasMinus1ToCharisma()
    {
        //Arrange
        $c = new character();
        $defaultChaMod = $c->charismaModifier;

        //Act
        $c->race = new orcRace();
        $orcChaMod = $c->charismaModifier;

        //Assert
        assert($orcChaMod == $defaultChaMod -1);
    }

    public function ItGetsPlus2TOAC()
    {
        //Arrange
        $c = new character();
        $defaultAC = $c->armorClass;

        //Act
        $c->race = new orcRace();
        $orcAC = $c->armorClass;

        //Assert
        assert($orcAC == $defaultAC +2);
    }
}