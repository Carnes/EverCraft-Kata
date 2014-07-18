<?php
include_once("character/character.php");
class human_Tests implements testInterface
{
    public function initialize() {}

    public function CharacterDefaultsToHumanRace()
    {
        $c = new character();
        assert($c->race instanceof humanRace);
    }

    public function ItHasCorrectInterface()
    {
        $human = new humanRace();
        $interfaces = class_implements($human);
        assert(in_array("ICharacterRace",$interfaces));
    }

    public function ItHasAName()
    {
        $human = new humanRace();
        assert($human->getName()=="Human");
    }
}