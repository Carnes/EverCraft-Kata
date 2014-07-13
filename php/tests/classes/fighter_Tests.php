<?php
include_once ("../main/combat.php");
include_once ("../main/character.php");
include_once ("../main/availableClasses.php");
include_once ("../main/fighterClass.php");


class fighter_Tests implements testInterface
{
    public function Initialize()
    {

    }

    public function ItExists()
    {
        assert(class_exists("fighterClass"));
    }

    public function ItIsInAvailableClasses()
    {
        //Arrange
        $class = new ReflectionClass("availableClasses");

        //Act / Assert
        assert($class->hasConstant("Fighter"));
        assert(availableClasses::Fighter == "fighterClass");
    }

    public function ItHasANamePropertyOfFighter()
    {
        $fc = new fighterClass();
        assert($fc->getName() == "Fighter");
    }

    public function CharCanSetFighterClass()
    {
        $c = new character();
        $c->addClass(availableClasses::Fighter);
        assert($c->class[0]->getName() == "Fighter");
    }

}