<?php
class main
{
    private $greeting;

    function __construct($greeting){
        $this->greeting = $greeting;
    }

    function sayGreeting(){
        echo $this->greeting;
    }
}