<?php
include_once("ICharacterRace.php");
class humanRace implements ICharacterRace
{
    private static $name = "Human";

    public function getName(){
        return $this::$name;
    }

    public function getModifiers()
    {
    }
}