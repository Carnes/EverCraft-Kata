<?php
include_once("ICharacterClass.php");
class paladinClass implements ICharacterClass
{
    private static $name = "Paladin";

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
                "method"=>function($character){return (8 * $character->level) + $character->constitutionModifier;},
                //"reason"=>"Paladin gets 8 hit points per level plus constitution modifier",
            );
            $this::$modifiers[] = array(
                "target"=>"attack role bonus",
                "method"=>function($self, $target){ if($target->alignment==alignment::Evil) return 2; return 0; }
                //"reason"=>"Paladin does +2 attack to Evil",
            );
            $this::$modifiers[] = array(
                "target"=>"attack damage bonus",
                "method"=>function($self, $target){ if($target->alignment==alignment::Evil) return 2; return 0; }
                //"reason"=>"Paladin does +2 damage to Evil",
            );
            $this::$modifiers[] = array(
                "target"=>"critical hit multiplier",
                "method"=>function($self, $target){ if($target->alignment==alignment::Evil) return 3; return 2; },
                //"reason"=>"Paladin does triple damage on critical hit to Evil",
            );
            $this::$modifiers[] = array(
                "target"=>"attack damage per level",
                "method"=>function($character){return $character->level;},
                //"reason"=>"Paladin gets +1 attack damage for each level",
            );
        }
    }

    public function getModifiers()
    {
        return $this::$modifiers;
    }
}