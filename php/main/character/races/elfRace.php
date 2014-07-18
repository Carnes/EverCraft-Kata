<?php
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
//            self::$formulas[] = new formula(
//                availableFormulaCategories::$ConstitutionModifierBonus,
//                function(){return 1;},
//                "Dwarf gets +1 to Constitution modifier"
//            );

        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}