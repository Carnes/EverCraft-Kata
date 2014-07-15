<?php
include_once ("../main/character.php");
include_once ("../main/availableClasses.php");
include_once ("../main/warMonkClass.php");

class warMonk_Tests implements testInterface
{
    public function Initialize()
    {

    }

    public function ItExists()
    {
        assert(class_exists("warMonkClass"));
    }

    public function ItIsInAvailableClasses()
    {
        //Arrange
        $class = new ReflectionClass("availableClasses");

        //Act / Assert
        assert($class->hasConstant("WarMonk"));
        assert(availableClasses::WarMonk == "warMonkClass");
    }

    public function ItHasANamePropertyOfWarMonk()
    {
        $fc = new warMonkClass();
        assert($fc->getName() == "War Monk");
    }

    public function CharCanSetWarMonkClass()
    {
        $c = new character();
        $c->addClass(availableClasses::WarMonk);
        assert($c->class[0]->getName() == "War Monk");
    }
}