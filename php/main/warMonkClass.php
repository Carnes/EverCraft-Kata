<?php
include_once("ICharacterClass.php");
class warMonkClass implements ICharacterClass
{
    private static $name = "War Monk";

    private static $modifiers = null;

    public function getName(){
        return $this::$name;
    }

    public function __construct()
    {
        if($this::$modifiers==null)
        {
            $this::$modifiers = array();
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$MaxHitPointsPerLevel,
                function($character){return (6 * $character->level) + $character->constitutionModifier;},
                "WarMonk gets 6 hit points per level plus constitution modifier"
            );
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$AttackDamagePerLevel,
                function($character){return 3+ floor($character->level/2) + floor($character->level/3);},
                "War Monk does 3 damage a level 1 and +1 at every 2nd and 3rd level"
            );
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$ArmorClassBonusForAbilityModifier,
                function($character){return $character->wisdomModifier + $character->dexterityModifier;},
                "War Monk has wisdom and dexterity modifiers bonus to armor class"
            );
        }
    }

    public function getModifiers()
    {
        return $this::$modifiers;
    }
}