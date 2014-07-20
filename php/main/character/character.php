<?php
include_once("formula/availableFormulaCategories.php");
include_once("characterBaseFormulas.php");
include_once("races/humanRace.php");
include_once("classes/availableClasses.php");
class character
{
    private $alignment;
    private $hitPoints;
    private static $abilityModifierLookup = array(null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5);

    public $name;
    private $_armorClass;
    private $_race;
    public $strength;
    public $dexterity;
    public $constitution;
    public $wisdom;
    public $intelligence;
    public $charisma;
    public $experience;
    public $class;
    public $wieldedWeapon;

    public function __construct(){
        $this->class = array();
        $this->_armorClass = 10;
        $this->strength = 10;
        $this->dexterity = 10;
        $this->constitution = 10;
        $this->wisdom = 10;
        $this->intelligence = 10;
        $this->charisma = 10;
        $this->experience = 0;
        $this->_race = new humanRace();
        $this->hitPoints = $this->getMaxHitPoints();
    }

    public function __get($property){
        switch($property) {
            case "armorClass":
                return $this->getArmorClass();
            case "isAlive":
                return $this->isAlive();
            case "strengthModifier":
                return $this->getStrengthAbilityModifier();
            case "dexterityModifier":
                return $this->getDexterityAbilityModifier();
            case "constitutionModifier":
                return $this->getConstitutionAbilityModifier();
            case "wisdomModifier":
                return $this->getWisdomAbilityModifier();
            case "intelligenceModifier":
                return $this->getIntelligenceAbilityModifier();
            case "charismaModifier":
                return $this->getCharismaAbilityModifier();
            case "hitPoints":
                return $this->hitPoints;
            case "level":
                return $this->getLevel();
            case "maxHitPoints":
                return $this->getMaxHitPoints();
            case "alignment":
                return $this->alignment;
            case "race":
                return $this->_race;
        }
    }

    public function __set($property, $value){
        switch($property) {
            case "alignment":
                if($value == alignment::Good || $value == alignment::Neutral || $value == alignment::Evil)
                    $this->alignment = $value;
                return;
            case "armorClass":
                $this->_armorClass = $value;
                return;
            case "race":
                $this->setRace($value);
                return;
        }
    }

    public function attack($defender, $roll)
    {
        $defender->getDefendingArmorClass($this);
        if($roll + $this->getAttackRoleBonus($defender) > $defender->getDefendingArmorClass($this)) {
            $damage = $this->getAttackDamage($defender, $roll);

            $defender->takeDamage($damage);
            $this->experience += 10;
            return true;
        }
        return false;
    }

    public function takeDamage($amount)
    {
        $this->hitPoints-=$amount;
    }

    public function getAttackDamage($defender, $attackRole)
    {
        $damage = $this->getAttackDamagePerLevel();
        $damage += $this->getAttackDamageBonus($defender);
        $weaponDamage = 0;

        if($this->wieldedWeapon instanceof Weapon\IWeapon)
            $weaponDamage += $this->wieldedWeapon->getDamage();

        if($attackRole >= 20 - $this->getCriticalHitRoleBonus($defender))
        {
            $damage *= $this->getCriticalHitMultiplier($defender);
            if($this->wieldedWeapon instanceof Weapon\IWeapon)
                $weaponDamage *= $this->wieldedWeapon->getCriticalMultiplier($this);
        }

        $damage += $weaponDamage;

        if($damage<1)
            $damage=1;
        return $damage;
    }

    public function getAttackRoleBonus($defender)
    {
        $bonus = 0;
        $bonus += $this->solveFormulaCategory(availableFormulaCategories::$AttackRoleBonus, $defender);
        if($this->wieldedWeapon instanceof Weapon\IWeapon)
            $bonus += $this->wieldedWeapon->getAttack();
        return $bonus;
    }

    public function getDefendingArmorClass($attacker)
    {
        return $this->getArmorClass($attacker);
    }

    public function setRace($race)
    {
        $interfaces = class_implements($race);
        if(!in_array("ICharacterRace",$interfaces))
            return;
        $this->_race = $race;
    }

    public function addClass($classType)
    {
        $interfaces = class_implements($classType);
        if(!in_array("ICharacterClass",$interfaces))
            return;

        $preMaxHP = $this->getMaxHitPoints();

        $this->class[] = new $classType();

        $postMaxHP = $this->getMaxHitPoints();
        $this->hitPoints+=($postMaxHP - $preMaxHP);
    }

    private function getArmorClass($target = null)
    {
        $ac = $this->_armorClass;
        $ac += $this->solveFormulaCategory(availableFormulaCategories::$ArmorClassBonusForAbilityModifier);
        $ac += $this->solveFormulaCategory(availableFormulaCategories::$ArmorClassBonus, $target);
        return $ac;
    }

    private function isAlive(){
        if($this->hitPoints>0)
            return true;
        return false;
    }

    private function abilityModifier($stat)
    {
        return self::$abilityModifierLookup[$stat];
    }

    private function getLevel()
    {
        $xpLevel = floor($this->experience / 1000);
        return $xpLevel + 1;
    }

    private function getModifiers()
    {
        $modifiers = characterBaseFormulas::getFormulas();

        $raceFormulas = $this->_race->getModifiers();
        if(is_array($raceFormulas))
            $modifiers = array_merge($modifiers,$raceFormulas);

        foreach($this->class as $class)
        {
            $classModifiers = $class->getModifiers();
            if(is_array($classModifiers))
                $modifiers = array_merge($modifiers,$classModifiers);
        }

        return $modifiers;
    }

    private function getAllModifiersForTarget($target)
    {
        $mods = array();
        foreach ($this->getModifiers() as $modifier)
            if($modifier->category== $target)
                $mods[] = $modifier;
        return $mods;
    }

    private function solveFormulaCategory($category, $target = null)
    {
        $formulas = $this->getAllModifiersForTarget($category);
        if($category->type == formulaType::Additive)
            return $this->solveAdditiveFormulas($formulas, $target);
        if($category->type == formulaType::BestOfCategory)
            return $this->solveBestOfFormulas($formulas, $target);
    }

    private function solveAdditiveFormulas($formulas, $target)
    {
        $result = 0;
        foreach($formulas as $formula)
            $result += $formula->execute([$this, $target]);
        return $result;
    }

    private function solveBestOfFormulas($formulas, $target)
    {
        $bestResult = null;
        foreach ($formulas as $formula)
        {
            $newResult = $formula->execute([$this, $target]);
            if($bestResult == null || $newResult > $bestResult)
                $bestResult = $newResult;
        }
        if ($bestResult == null)
            return 0;
        return $bestResult;
    }

    private function getCriticalHitMultiplier($defender)
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$CriticalHitMultiplier, $defender);
    }

    private function getCriticalHitRoleBonus($defender)
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$CriticalHitRollBonus, $defender);
    }

    private function getAttackDamagePerLevel()
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$AttackDamagePerLevel, null);
    }

    private function getMaxHitPoints()
    {
        return $this->solveFormulaCategory(availableFormulaCategories::$MaxHitPointsPerLevel, null);
    }

    private function getAttackDamageBonus($target)
    {
        $bonus = 0;
        $bonus += $this->solveFormulaCategory(availableFormulaCategories::$AttackDamageForAbilityModifier,null);
        $bonus += $this->solveFormulaCategory(availableFormulaCategories::$AttackDamageBonus, $target);

        return $bonus;
    }

    private function getStrengthAbilityModifier()
    {
        $strMod = $this->abilityModifier($this->strength);
        $strMod += $this->solveFormulaCategory(availableFormulaCategories::$StrengthModifierBonus);
        return $strMod;
    }

    private function getIntelligenceAbilityModifier()
    {
        $intMod = $this->abilityModifier($this->intelligence);
        $intMod += $this->solveFormulaCategory(availableFormulaCategories::$IntelligenceModifierBonus);
        return $intMod;
    }

    private function getWisdomAbilityModifier()
    {
        $wisMod = $this->abilityModifier($this->wisdom);
        $wisMod += $this->solveFormulaCategory(availableFormulaCategories::$WisdomModifierBonus);
        return $wisMod;
    }

    private function getCharismaAbilityModifier()
    {
        $chaMod = $this->abilityModifier($this->charisma);
        $chaMod += $this->solveFormulaCategory(availableFormulaCategories::$CharismaModifierBonus);
        return $chaMod;
    }

    private function getConstitutionAbilityModifier()
    {
        $conMod = $this->abilityModifier($this->constitution);
        $conMod += $this->solveFormulaCategory(availableFormulaCategories::$ConstitutionModifierBonus);
        return $conMod;
    }

    private function getDexterityAbilityModifier()
    {
        $dexMod = $this->abilityModifier($this->dexterity);
        $dexMod += $this->solveFormulaCategory(availableFormulaCategories::$DexterityModifierBonus);
        return $dexMod;
    }
}