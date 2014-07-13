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
        $modifiers[] = array("target"=>"maxHitPoints per level","method"=>function($character){return (10 * $character->level) + $character->constitutionModifier;});

        return $modifiers;
    }
}