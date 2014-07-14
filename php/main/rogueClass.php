<?php
class rogueClass implements characterClass
{
    private static $name = "Rogue";

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
                "target"=>"critical hit multiplier",
                "method"=>function(){return 3;},
                //"reason"=>"Rogue does triple damage on critical hit",
            );
            $this::$modifiers[] = array(
                "target"=>"attack role bonus",
                "method"=>function($self, $target){ $dMod = $target->dexterityModifier; if($dMod > 0) return $dMod; return 0; },
                //"reason"=>"Rogue negates defender's dexterity modifier"
            );
            $this::$modifiers[] = array(
                "target"=>"attack damage bonus for ability modifier",
                "method"=>function($character){return $character->dexterityModifier;},
                //"reason"=>"Rogue gets damage bonus for dexterity modifier",
            );

        }
    }
    public function getModifiers()
    {
        return $this::$modifiers;
    }
}