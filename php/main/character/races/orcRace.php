<?php
include_once("ICharacterRace.php");
class orcRace implements ICharacterRace
{
    private static $name = "Orc";

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
                availableFormulaCategories::$StrengthModifierBonus,
                function(){return 2;},
                "Orc gets +2 to Strength modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$IntelligenceModifierBonus,
                function(){return -1;},
                "Orc gets -1 to Intelligence modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$WisdomModifierBonus,
                function(){return -1;},
                "Orc gets -1 to Wisdom modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$CharismaModifierBonus,
                function(){return -1;},
                "Orc gets -1 to Charisma modifier"
            );
            self::$formulas[] = new formula(
                availableFormulaCategories::$ArmorClassBonus,
                function(){ return 2; },
                "Orc gets +2 to Armor class for thick skin"
            );
        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}