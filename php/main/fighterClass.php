<?php
include_once("characterClass.php");
class fighterClass implements characterClass
{
    private static $name = "Fighter";
    private static $modifiers = null;

    public function getName(){
        return $this::$name;
    }

    public function __construct(){
        if($this::$modifiers == null)
        {
            $this::$modifiers = array();
            $this::$modifiers[] = array("target"=>"attack damage per level","method"=>function($character){return $character->level;});
            $this::$modifiers[] = array("target"=>"maxHitPoints per level","method"=>function($character){return (10 * $character->level) + $character->constitutionModifier;});
        }
    }

    public function getModifiers() {

        return $this::$modifiers;
    }
}