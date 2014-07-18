<?php
include_once ("character/character.php");
include_once ("character/classes/availableClasses.php");
include_once ("character/classes/fighterClass.php");


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

    public function DoesTwoDamageAtLevel2ForSuccessfulAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $attacker->experience=1000; //lvl 2
        $attacker->addClass(availableClasses::Fighter);
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-(2+$attacker->strengthModifier));
    }

    public function Has10HitPointsAtLevel1()
    {
        $c = new character();
        $c->addClass(availableClasses::Fighter);
        assert($c->hitPoints == 10);
    }
    public function Has10MaxHitPointsAtLevel1()
    {
        $c = new character();
        $c->addClass(availableClasses::Fighter);
        assert($c->maxHitPoints == 10);
    }
}