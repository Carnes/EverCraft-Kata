<?php
include_once("equipment.php");
include_once("formula.php");
class weaponFactory
{
    private $equipment;

    private function __construct($weapon = null){
        if($weapon == null)
            $this->equipment = new Equipment\equipment();
        else
            $this->equipment = $weapon;
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
        $this->equipment->name = $name;
        return $this;
    }

    public function withSubType($subType)
    {
        $this->equipment->subType = $subType;
        return $this;
    }

    public function withType($type)
    {
        $this->equipment->type = $type;
        return $this;
    }

    public function withDamage($dmgAmount)
    {
        $formula = new Equipment\formula(
            function()use($dmgAmount){ return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage",
            \Equipment\formulaCategories::$Damage
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withDamageForRace($dmgAmount, $raceName)
    {
        $formula = new Equipment\formula(
            function($wielder)use($dmgAmount, $raceName){ if($wielder != null && $wielder->race->getName() == $raceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when used by ".$raceName,
            \Equipment\formulaCategories::$Damage
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withDamageAgainstRace($dmgAmount, $raceName)
    {
        $formula = new Equipment\formula(
            function($wielder, $target)use($dmgAmount, $raceName){ if($target != null && $target->race->getName() == $raceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when used against ".$raceName,
            \Equipment\formulaCategories::$Damage
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withDamageForRaceAndAgainstRace($dmgAmount, $forRaceName, $againstRaceName)
    {
        $formula = new Equipment\formula(
            function($wielder, $target)use($dmgAmount, $forRaceName, $againstRaceName){ if($target == null || $wielder == null) return; if($target->race->getName() == $againstRaceName && $wielder->race->getName()== $forRaceName) return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage when used by ".$forRaceName." and against ".$againstRaceName,
            \Equipment\formulaCategories::$Damage
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withAttack($atkAmount)
    {
        $formula = new Equipment\formula(
            function()use($atkAmount){ return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack",
            \Equipment\formulaCategories::$Attack
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withAttackForRace($atkAmount, $raceName)
    {
        $formula = new Equipment\formula(
            function($wielder)use($atkAmount, $raceName){ if($wielder != null && $wielder->race->getName() == $raceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when used by ".$raceName,
            \Equipment\formulaCategories::$Attack
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withAttackAgainstRace($atkAmount, $raceName)
    {
        $formula = new Equipment\formula(
            function($wielder, $target)use($atkAmount, $raceName){ if($target != null && $target->race->getName() == $raceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when used against ".$raceName,
            \Equipment\formulaCategories::$Attack
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withAttackForRaceAgainstRace($atkAmount, $forRaceName, $againstRaceName)
    {
        $formula = new Equipment\formula(
            function($wielder, $target)use($atkAmount, $forRaceName, $againstRaceName){ if($target == null || $wielder == null) return; if($target->race->getName() == $againstRaceName && $wielder->race->getName() == $forRaceName) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when used by ".$forRaceName." against ".$againstRaceName,
            \Equipment\formulaCategories::$Attack
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withAttackForNonClass($atkAmount, $className)
    {
        $formula = new Equipment\formula(
            function($wielder)use($atkAmount, $className){ if($wielder != null && $wielder->hasClassName($className)===false) return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack when not used by ".$className,
            \Equipment\formulaCategories::$Attack
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withCriticalMultiplier($multiplier)
    {
        $formula = new Equipment\formula(
            function()use($multiplier){ return $multiplier; },
            ($multiplier >0 ? "+": "").$multiplier." critical damage multiplier",
            \Equipment\formulaCategories::$CriticalMultiplier
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function withRogueCriticalMultiplier($multiplier)
    {
        $formula = new Equipment\formula(
            function($wielder)use($multiplier){ if($wielder==null)return; foreach($wielder->class as $class) if($class instanceof rogueClass) return $multiplier; },
            ($multiplier >0 ? "+": "").$multiplier." critical damage multiplier for Rogues",
            \Equipment\formulaCategories::$CriticalMultiplier
        );

        $this->equipment->addFormula($formula);
        return $this;
    }

    public function getEquipment()
    {
        $e = $this->equipment;
        if($e->name == "Unknown")
            throw new Exception("Cannot forge equipment without a name.");
        if($e->type == \Equipment\itemType::$Unknown)
            throw new Exception("Cannot forge equipment without a type.");
        if($e->type == \Equipment\itemSubType::$Unknown)
            throw new Exception("Cannot forge equipment without a sub-type.");
        if($e->type == \Equipment\itemType::$Weapon)
        {
            //$weaponSubTypes = get_object_vars(new Equipment\weaponSubType());
            //$class = new ReflectionClass('Foo');
            $weaponSubTypes = (new ReflectionClass('Equipment\weaponSubType'))->getStaticProperties();
            if(array_search($e->subType,$weaponSubTypes)===false)
                throw new Exception("Cannot forge weapon without a weapon sub-type.");
        }
        //    throw new Exception("Cannot forge weapon without a weapon sub-type.");
        return $this->equipment;
    }
}