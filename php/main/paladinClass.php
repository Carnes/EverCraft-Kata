<?php
include_once("ICharacterClass.php");
class paladinClass implements ICharacterClass
{
    private static $name = "Paladin";

    private static $modifiers = null;

    public function getName(){
        return $this::$name;
    }

    public function __construct()
    {
        if($this::$modifiers==null)
        {
            $mod = array();

            $mod[] = new formula(
                availableFormulaCategories::$MaxHitPointsPerLevel,
                function($character){return (8 * $character->level) + $character->constitutionModifier;},
                "Paladin gets 8 hit points per level plus constitution modifier"
            );

            $mod[] = new formula(
                availableFormulaCategories::$AttackRoleBonus,
                function($self, $target){ if($target->alignment==alignment::Evil) return 2; return 0; },
                "Paladin does +2 attack to Evil"
            );

            $mod[] = new formula(
                availableFormulaCategories::$AttackDamageBonus,
                function($self, $target){ if($target->alignment==alignment::Evil) return 2; return 0; },
                "Paladin does +2 damage to Evil"
            );

            $mod[] = new formula(
                availableFormulaCategories::$CriticalHitMultiplier,
                function($self, $target){ if($target->alignment==alignment::Evil) return 3; return 2; },
                "Paladin does triple damage on critical hit to Evil"
            );
            $mod[] = new formula(
                availableFormulaCategories::$AttackDamagePerLevel,
                function($character){return $character->level;},
                "Paladin gets +1 attack damage for each level"
            );
            $this::$modifiers = $mod;
        }
    }

    public function getModifiers()
    {
        return $this::$modifiers;
    }
}