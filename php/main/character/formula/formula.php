<?php
class formula
{
    public $category;
    public $execute;
    public $reason;

    public function execute($args)
    {
        $callable = $this->execute;
        return call_user_func_array($callable, $args);
    }

    public function __construct($category, $execute, $reason){
        if(!is_callable($execute))
            throw new Exception("Formula must have an executable method");
        if(!is_string($reason))
            $reason = "";

        $this->category = $category;
        $this->execute = $execute;
        $this->reason = $reason;
    }
}

