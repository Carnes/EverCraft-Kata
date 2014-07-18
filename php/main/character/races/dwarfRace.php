<?php
include_once("ICharacterRace.php");
include_once("orcRace.php");

class dwarfRace implements ICharacterRace
{
    private static $name = "Dwarf";

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
                availableFormulaCategories::$ConstitutionModifierBonus,
                function(){return 1;},
                "Dwarf gets +1 to Constitution modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$MaxHitPointsPerLevel,
                function($dwarf){ return ($dwarf->constitutionModifier * 2) + (5 * $dwarf->level); },
                "Dwarf max hit points is (Constitution modifier x 2) + (5 x level)"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$AttackRoleBonus,
                function($dwarf, $target){ if($target->race instanceof orcRace) return 2;  },
                "Dwarf gets +2 to attack roll against Orcs"
            );
        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}