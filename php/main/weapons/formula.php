<?php
namespace Weapon;
class formula
{
    public $method;
    public $reason;

    public function execute($args = null)
    {
        $callable = $this->method;
        if($args == null) return $callable();
        return call_user_func_array($callable, $args);
    }

    public function __construct($method, $reason){
        if(!is_callable($method))
            throw new Exception("Formula must have an executable method");
        if(!is_string($reason))
            $reason = "";

        $this->method = $method;
        $this->reason = $reason;
    }
}