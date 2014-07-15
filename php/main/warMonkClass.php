<?php
include_once("ICharacterClass.php");
class warMonkClass implements ICharacterClass
{
    private static $name = "War Monk";

    private static $modifiers = null;

    public function getName(){
        return $this::$name;
    }

    public function __construct()
    {
        if($this::$modifiers==null)
        {
            $this::$modifiers = array();
            $this::$modifiers[] = array(
                "target"=>"maxHitPoints per level",
                "method"=>function($character){return (6 * $character->level) + $character->constitutionModifier;},
                //"reason"=>"WarMonk gets 6 hit points per level plus constitution modifier",
            );
            $this::$modifiers[] = array(
                "target"=>"attack damage per level",
                "method"=>function($character){return 3+ floor($character->level/2) + floor($character->level/3);},
                //"reason"=>"War Monk does 3 damage a level 1 and +1 at every 2nd and 3rd level",
            );
            $this::$modifiers[] = array(
                "target"=>"armor class bonus for ability modifiers",
                "method"=>function($character){return $character->wisdomModifier + $character->dexterityModifier;},
                //"reason"=>"War Monk has wisdom and dexterity modifiers bonus to armor class",
            );
        }
    }

    public function getModifiers()
    {
        return $this::$modifiers;
    }
}