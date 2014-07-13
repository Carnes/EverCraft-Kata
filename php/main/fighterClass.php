<?php
include_once("characterClass.php");
class fighterClass implements characterClass
{
    private static $name = "Fighter";

    public function getName(){
        return $this::$name;
    }
}