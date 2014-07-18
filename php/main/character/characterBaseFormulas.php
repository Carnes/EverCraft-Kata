<?php
include_once("formula/formula.php");

class characterBaseFormulas{
    private static $formulas = null;

    public static function getFormulas()
    {
        if(self::$formulas==null)
            self::buildFormulas();
        return self::$formulas;
    }

    private static function buildFormulas()
    {
        $mod = array();

        $mod[] = new formula(
            availableFormulaCategories::$AttackDamagePerLevel,
            function($character){return ceil($character->level/2);},
            "Base damage per level"
        );
        $mod[] = new formula(
            availableFormulaCategories::$MaxHitPointsPerLevel,
            function($character){return (5 * $character->level) + $character->constitutionModifier;},
            "Base 5 hit points per level plus constitution modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$CriticalHitMultiplier,
            function(){return 2;},
            "Base critical hit multiplier of 2"
        );
        $mod[] = new formula(
            availableFormulaCategories::$AttackRoleBonus,
            function($self, $target){return $self->strengthModifier;},
            "Base attack role bonus of strength modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$AttackDamageForAbilityModifier,
            function($character){return $character->strengthModifier;},
            "Base attack damage bonus for ability modifier"
        );
        $mod[] = new formula(
            availableFormulaCategories::$ArmorClassBonusForAbilityModifier,
            function($character){return $character->dexterityModifier;},
            "Base armor class bonus for ability modifier"
        );
        self::$formulas = $mod;
    }
}