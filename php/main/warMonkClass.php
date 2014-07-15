<?php
include_once("ICharacterClass.php");
class warMonkClass implements ICharacterClass
{
    private static $name = "War Monk";

    public function getName(){
        return $this::$name;
    }

    public function __construct()
    {
    }
    public function getModifiers()
    {
    }
}