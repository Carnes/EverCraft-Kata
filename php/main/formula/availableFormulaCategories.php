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
    public static $ArmorClassBonus;
    public static $ArmorClassBonusForAbilityModifier;
    public static $StrengthModifierBonus;
    public static $IntelligenceModifierBonus;
    public static $WisdomModifierBonus;
    public static $CharismaModifierBonus;

    public static function init()
    {
        self::$MaxHitPointsPerLevel = new formulaCategory(formulaType::BestOfCategory, "Max HitPoints per level");
        self::$CriticalHitMultiplier = new formulaCategory(formulaType::BestOfCategory, "Critical hit multiplier");
        self::$AttackDamagePerLevel = new formulaCategory(formulaType::BestOfCategory, "Attack damage per level");
        self::$AttackDamageForAbilityModifier = new formulaCategory(formulaType::BestOfCategory, "Attack damage for ability modifier");
        self::$ArmorClassBonusForAbilityModifier = new formulaCategory(formulaType::BestOfCategory,"Armor class bonus for ability modifiers");

        self::$StrengthModifierBonus = new formulaCategory(formulaType::Additive, "Strength modifier bonus");
        self::$IntelligenceModifierBonus = new formulaCategory(formulaType::Additive, "Intelligence modifier bonus");
        self::$WisdomModifierBonus = new formulaCategory(formulaType::Additive, "Wisdom modifier bonus");
        self::$CharismaModifierBonus = new formulaCategory(formulaType::Additive, "Charisma modifier bonus");
        self::$ArmorClassBonus = new formulaCategory(formulaType::Additive, "Armor class bonus");
        self::$AttackRoleBonus = new formulaCategory(formulaType::Additive, "Attack role bonus");
        self::$AttackDamageBonus = new formulaCategory(formulaType::Additive, "Attack damage bonus");
    }
}
availableFormulaCategories::init(); //PHP is awesome and weird : )