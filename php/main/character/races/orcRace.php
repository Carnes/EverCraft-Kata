<?php
include_once("ICharacterRace.php");
class orcRace implements ICharacterRace
{
    private static $name = "Orc";

    public function getName(){
        return $this::$name;
    }

    private static $formulas = null;

    public function __construct()
    {
        if($this::$formulas==null)
        {
            self::$formulas = array();
            self::$formulas[] = new formula(
                availableFormulaCategories::$StrengthModifierBonus,
                function(){return 2;},
                "Orc gets +2 to Strength Modifier"
            );
        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}