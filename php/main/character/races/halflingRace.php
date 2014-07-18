<?php
include_once("ICharacterRace.php");

class halflingRace implements ICharacterRace
{
    private static $name = "Halfling";

    public function getName(){
        return self::$name;
    }

    private static $formulas = null;

    public function __construct()
    {
        if(self::$formulas==null)
        {
            self::$formulas = array();
            self::$formulas[] = new formula(
                availableFormulaCategories::$DexterityModifierBonus,
                function(){return 1;},
                "Halfling gets +1 to Dexterity modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$StrengthModifierBonus,
                function(){return -1;},
                "Halfling gets -1 to Strength modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$ArmorClassBonus,
                function($elf, $target){ if(isset($target) && !$target->race instanceof halflingRace) return 2; },
                "Elf gets +2 to Armor class when defending against non-Halflings"
            );
        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}