<?php
include_once("characterClass.php");
class fighterClass implements characterClass
{
    private static $name = "Fighter";

    public function getName(){
        return $this::$name;
    }

    public function getModifiers() {

        $modifiers = array();
        $modifiers[] = array("target"=>"attack damage per level","method"=>function($character){return $character->level;});

        return $modifiers;
    }
}