<?php
include_once("weapon.php");
include_once("formula.php");
class weaponFactory
{
    private $weapon;

    private function __construct($weapon = null){
        if($weapon == null)
            $this->weapon = new Weapon\weapon();
        else
            $this->weapon = $weapon;
    }

    public static function startForge(){
        $self = new self();
        return $self;
    }

    public static function reForge($weapon){
        $self = new self($weapon);
        return $self;
    }

    public function withName($name)
    {
        $this->weapon->name = $name;
        return $this;
    }

    public function withSubType($type)
    {
        $this->weapon->subType = $type;
        return $this;
    }

    public function withDamage($dmgAmount)
    {
        $formula = new Weapon\formula(
            function()use($dmgAmount){ return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage",
            \Weapon\formulaCategories::$Damage
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withDamageForRace($dmgAmount, $raceName)
    {
        $formula = new Weapon\formula(
            function($wielder)use($dmgAmount, $raceName){ if($wielder != null && $wielder->race->getName() == $raceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when wielded by ".$raceName,
            \Weapon\formulaCategories::$Damage
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withDamageAgainstRace($dmgAmount, $raceName)
    {
        $formula = new Weapon\formula(
            function($wielder, $target)use($dmgAmount, $raceName){ if($target != null && $target->race->getName() == $raceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when wielded against ".$raceName,
            \Weapon\formulaCategories::$Damage
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withDamageForRaceAndAgainstRace($dmgAmount, $forRaceName, $againstRaceName)
    {
        $formula = new Weapon\formula(
            function($wielder, $target)use($dmgAmount, $forRaceName, $againstRaceName){ if($target == null || $wielder == null) return; if($target->race->getName() == $againstRaceName && $wielder->race->getName()== $forRaceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when wielded by ".$forRaceName." and against ".$againstRaceName,
            \Weapon\formulaCategories::$Damage
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withAttack($atkAmount)
    {
        $formula = new Weapon\formula(
            function()use($atkAmount){ return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack",
            \Weapon\formulaCategories::$Attack
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withAttackForRace($atkAmount, $raceName)
    {
        $formula = new Weapon\formula(
            function($wielder)use($atkAmount, $raceName){ if($wielder != null && $wielder->race->getName() == $raceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when wielded by ".$raceName,
            \Weapon\formulaCategories::$Attack
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withAttackAgainstRace($atkAmount, $raceName)
    {
        $formula = new Weapon\formula(
            function($wielder, $target)use($atkAmount, $raceName){ if($target != null && $target->race->getName() == $raceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when wielded against ".$raceName,
            \Weapon\formulaCategories::$Attack
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withAttackForRaceAgainstRace($atkAmount, $forRaceName, $againstRaceName)
    {
        $formula = new Weapon\formula(
            function($wielder, $target)use($atkAmount, $forRaceName, $againstRaceName){ if($target == null || $wielder == null) return; if($target->race->getName() == $againstRaceName && $wielder->race->getName() == $forRaceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when wielded by ".$forRaceName." against ".$againstRaceName,
            \Weapon\formulaCategories::$Attack
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withAttackForNonClass($atkAmount, $className)
    {
        $formula = new Weapon\formula(
            function($wielder)use($atkAmount, $className){ if($wielder != null && $wielder->hasClassName($className)===false) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when not wielded by ".$className,
            \Weapon\formulaCategories::$Attack
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withCriticalMultiplier($multiplier)
    {
        $formula = new Weapon\formula(
            function()use($multiplier){ return $multiplier; },
            ($multiplier >0 ? "+": "").$multiplier." critical damage multiplier",
            \Weapon\formulaCategories::$CriticalMultiplier
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function withRogueCriticalMultiplier($multiplier)
    {
        $formula = new Weapon\formula(
            function($wielder)use($multiplier){ if($wielder==null)return; foreach($wielder->class as $class) if($class instanceof rogueClass) return $multiplier; },
            ($multiplier >0 ? "+": "").$multiplier." critical damage multiplier for Rogues",
            \Weapon\formulaCategories::$CriticalMultiplier
        );

        $this->weapon->addFormula($formula);
        return $this;
    }

    public function getWeapon()
    {
        return $this->weapon;
    }
}