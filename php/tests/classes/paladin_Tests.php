<?php
include_once ("combat.php");
include_once ("character/character.php");
include_once ("character/classes/availableClasses.php");
include_once ("character/classes/paladinClass.php");

class paladin_Tests implements testInterface
{
    public function Initialize()
    {

    }

    public function ItExists()
    {
        assert(class_exists("paladinClass"));
    }

    public function ItIsInAvailableClasses()
    {
        //Arrange
        $class = new ReflectionClass("availableClasses");

        //Act / Assert
        assert($class->hasConstant("Paladin"));
        assert(availableClasses::Paladin == "paladinClass");
    }

    public function ItHasANamePropertyOfPaladin()
    {
        $fc = new paladinClass();
        assert($fc->getName() == "Paladin");
    }

    public function CharCanSetPaladinClass()
    {
        //Arrange
        $c = new character();

        //Act
        $c->addClass(availableClasses::Paladin);

        //Assert
        assert($c->class[0]->getName() == "Paladin");
    }
    public function ItGains8HPPerLevelPlusConstitution()
    {
        //Arrange / Act
        $c = new character();
        $c->constitution+=2;
        $c->addClass(availableClasses::Paladin);

        //Assert
        assert($c->maxHitPoints == 9);
    }

    public function ItDoesPlus2AttackAndDamageToEvilCharacters()
    {
        $attacker = new character();
        $attacker->addClass(availableClasses::Paladin);
        $defender = new character();
        $defender->alignment = alignment::Evil;
        $roll = $defender->armorClass - 1;
        $preHP = $defender->hitPoints;

        $attackSuccess = (new combat())->attack($attacker, $defender, $roll);
        $dmgDone = $preHP - $defender->hitPoints;

        assert($attackSuccess === true);
        assert($dmgDone == 3);
    }

    public function ItDoesTripleDamageOnCriticalHitToEvil()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $attacker->addClass(availableClasses::Paladin);
        $defender = new character();
        $defender->alignment = alignment::Evil;
        $roll = 20;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $c->attack($attacker, $defender, $roll);
        $damageDone = $hpPreAttack - $defender->hitPoints;

        //Assert
        assert($damageDone == 9);
    }

    private function doAttackForLevel($level)
    {
        $c = new combat();
        $attacker = new character();
        $attacker->addClass(availableClasses::Paladin);
        $attacker->experience = ($level * 1000) - 1000;
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;

        $c->attack($attacker, $defender, $roll);
        return $hpPreAttack - $defender->hitPoints;
    }

    public function ItDoesOneExtraDamagePerLevel()
    {
        //Arrange / Act
        $dmgAtLvl1 = $this->doAttackForLevel(1);
        $dmgAtLvl2 = $this->doAttackForLevel(2);
        $dmgAtLvl3 = $this->doAttackForLevel(3);
        $dmgAtLvl4 = $this->doAttackForLevel(4);
        $dmgAtLvl5 = $this->doAttackForLevel(5);

        //Assert
        assert($dmgAtLvl1 == 1);
        assert($dmgAtLvl2 == 2);
        assert($dmgAtLvl3 == 3);
        assert($dmgAtLvl4 == 4);
        assert($dmgAtLvl5 == 5);
    }
}