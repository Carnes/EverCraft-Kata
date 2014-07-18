<?php
include_once("ICharacterClass.php");
class rogueClass implements ICharacterClass
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
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$CriticalHitMultiplier,
                function(){return 3;},
                "Rogue does triple damage on critical hit"
            );
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$AttackRoleBonus,
                function($self, $target){ $dMod = $target->dexterityModifier; if($dMod > 0) return $dMod; return 0; },
                "Rogue negates defender's dexterity modifier"
            );
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$AttackDamageForAbilityModifier,
                function($character){return $character->dexterityModifier;},
                "Rogue gets damage bonus for dexterity modifier"
            );

        }
    }
    public function getModifiers()
    {
        return $this::$modifiers;
    }
}