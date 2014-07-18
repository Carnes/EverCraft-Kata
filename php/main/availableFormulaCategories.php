<?php
include_once("formulaType.php");
include_once("formulaCategory.php");
class availableFormulaCategories{
    public static $MaxHitPointsPerLevel;
    public static $AttackRoleBonus;
    public static $AttackDamageBonus;
    public static $AttackDamagePerLevel;
    public static $CriticalHitMultiplier;
    public static $AttackDamageForAbilityModifier;
    public static $ArmorClassBonusForAbilityModifier;

    public static function init()
    {
        self::$MaxHitPointsPerLevel = new formulaCategory(formulaType::BestOfCategory, "Max HitPoints per level");
        self::$AttackRoleBonus = new formulaCategory(formulaType::Additive, "Attack role bonus");
        self::$AttackDamageBonus = new formulaCategory(formulaType::Additive, "Attack damage bonus");
        self::$CriticalHitMultiplier = new formulaCategory(formulaType::BestOfCategory, "Critical hit multiplier");
        self::$AttackDamagePerLevel = new formulaCategory(formulaType::BestOfCategory, "Attack damage per level");
        self::$AttackDamageForAbilityModifier = new formulaCategory(formulaType::BestOfCategory, "Attack damage for ability modifier");
        self::$ArmorClassBonusForAbilityModifier = new formulaCategory(formulaType::BestOfCategory,"Armor class bonus for ability modifiers");
    }
}
availableFormulaCategories::init(); //PHP is awesome and weird : )