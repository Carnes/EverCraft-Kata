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
}