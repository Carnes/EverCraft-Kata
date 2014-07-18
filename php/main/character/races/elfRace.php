<?php
include_once("ICharacterRace.php");
include_once("orcRace.php");

class elfRace implements ICharacterRace
{
    private static $name = "Elf";

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
                "Elf gets +1 to Dexterity modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$ConstitutionModifierBonus,
                function(){return -1;},
                "Elf gets -1 to Constitution modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$CriticalHitRollBonus,
                function(){ return 1; },
                "Elf gets +1 to Critical roll bonus"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$ArmorClassBonus,
                function($elf, $target){ if(isset($target) && $target->race instanceof orcRace) return 2; },
                "Elf gets +2 to Armor class when defending against Orcs"
            );
        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}