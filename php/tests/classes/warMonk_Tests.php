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

    public function ItGains6HPPerLevel()
    {
        $c = new character();
        $c->addClass(availableClasses::WarMonk);
        assert($c->hitPoints == 6);
    }

    public function ItDoes3PointsOfDamageAtLevel1()
    {
        $attacker = new character();
        $attacker->addClass(availableClasses::WarMonk);

        $defender = new character();
        $roll = $defender->armorClass +1;

        $preHP = $defender->hitPoints;
        (new combat())->attack($attacker, $defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        assert($damageDone == 3);
    }

    private function doCombatForLevel($level)
    {
        $attacker = new character();
        $attacker->addClass(availableClasses::WarMonk);
        $attacker->experience += ($level * 1000) - 1000;

        $defender = new character();
        $roll = $defender->armorClass +1;

        $preHP = $defender->hitPoints;
        (new combat())->attack($attacker, $defender, $roll);
        return $preHP - $defender->hitPoints;
    }

    public function AttackRollIsIncreasedBy1Every2ndAnd3rdLevel()
    {
        $dmgAtLevel2 = $this->doCombatForLevel(2);
        $dmgAtLevel3 = $this->doCombatForLevel(3);
        $dmgAtLevel4 = $this->doCombatForLevel(4);
        $dmgAtLevel5 = $this->doCombatForLevel(5);
        $dmgAtLevel6 = $this->doCombatForLevel(6);
        $dmgAtLevel7 = $this->doCombatForLevel(7);
        $dmgAtLevel8 = $this->doCombatForLevel(8);
        $dmgAtLevel9 = $this->doCombatForLevel(9);

        assert($dmgAtLevel2 == 4);
        assert($dmgAtLevel3 == 5);
        assert($dmgAtLevel4 == 6);
        assert($dmgAtLevel5 == 6);
        assert($dmgAtLevel6 == 8);
        assert($dmgAtLevel7 == 8);
        assert($dmgAtLevel8 == 9);
        assert($dmgAtLevel9 == 10);
    }

    public function ItAddsWisdomAndDexModifiersToArmorClass()
    {
        $c = new character();
        $c->addClass(availableClasses::WarMonk);
        $c->dexterity+=2;
        $acWithDex = $c->armorClass;
        $c->wisdom+=2;
        $acWithDexAndWis = $c->armorClass;



        assert($acWithDex==11);
        assert($acWithDexAndWis==12);
    }
}