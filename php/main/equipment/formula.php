<?php
namespace Equipment;

class formulaType
{
    const Additive = 1;
    const BestOf = 2;
}

class formulaCategory
{
    public $description;
    public $type;
    public function __construct($type, $description){
        if($type != formulaType::BestOf && $type != formulaType::Additive)
            throw new Exception("Formula category must have a type");
        if(!is_string($description))
            throw new Exception("Formula category must set a description");
        $this->type = $type;
        $this->description = $description;
    }
}

class formulaCategories
{
    public static $Damage;
    public static $Attack;
    public static $CriticalMultiplier;

    public static function init()
    {
        self::$Damage = new formulaCategory(formulaType::Additive, "Damage");
        self::$Attack = new formulaCategory(formulaType::Additive, "Attack");
        self::$CriticalMultiplier = new formulaCategory(formulaType::BestOf, "Critical damage multiplier");
    }
}
formulaCategories::init();

class formula
{
    public $method;
    public $reason;
    public $category;

    public function execute($args = null)
    {
        $callable = $this->method;
        if($args == null) return $callable();
        return call_user_func_array($callable, $args);
    }

    public function __construct($method, $reason, $category){
        if(!is_callable($method))
            throw new Exception("Formula must have an executable method");
        if(!is_string($reason))
            $reason = "";

        $this->method = $method;
        $this->reason = $reason;
        $this->category = $category;
    }
}