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
}