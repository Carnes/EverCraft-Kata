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
            self::$formulas[] = new formula(
                availableFormulaCategories::$DexterityModifierBonus,
                function(){return 1;},
                "Elf gets +1 to Dexterity modifier"
            );

        }
    }

    public function getModifiers()
    {
        return self::$formulas;
    }
}