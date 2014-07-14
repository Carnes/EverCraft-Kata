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
            $this::$modifiers[] = array("target"=>"critical hit multiplier","method"=>function(){return 3;});
        }
    }
    public function getModifiers()
    {
        return $this::$modifiers;
    }
}