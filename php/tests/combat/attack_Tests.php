<?php
include_once ("character/character.php");

class attack_Tests implements testInterface
{
    public function initialize(){

    }

    public function ItExists()
    {
        assert(method_exists("character","attack"));
    }

    public function ItReturnsTrueOnAttackSuccess()
    {
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;

        $combatResult = $attacker->attack($defender, $roll);
        assert($combatResult === true);
    }

    public function ItReturnsFalseWhenRollNotGreaterThanDefenderAC()
    {
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;

        $combatResult = $attacker->attack($defender, $roll);
        assert($combatResult === false);
    }

    public function ItDealsOneToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $attacker = new character();
        $attacker->strength = 12;
        $defender = new character();
        $roll = $defender->armorClass;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-(1+$attacker->strengthModifier));
    }

    public function ItDealsTwoToDefenderOnSuccessfulAttackAtlevel3()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;
        $attacker->experience=2000;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-2);
    }

    public function ItDealsOneToDefenderOnSuccessfulAttackAtlevel2()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;
        $attacker->experience=1000;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-1);
    }

    public function ItDealsTwoToDefenderOnSuccessfulAttackAtlevel4()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;
        $attacker->experience=3000;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-2);
    }

    public function ItDealsOneDamagePlusSTRModifierToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;
        $attacker->strength = 12;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-(1+$attacker->strengthModifier));
    }

    public function ItDealsOneDamageEvenWithMinusFiveModifierToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $defender->armorClass = 5;
        $roll = 11;
        $hpPreAttack = $defender->hitPoints;
        $attacker->strength = 1;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-1);
    }

    public function ItDealsZeroDamageToDefenderOnFailedAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack);
    }

    public function ItDealsDoubleDamageToDefenderOnSuccessfulCriticalAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = 20;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-2);
    }

    public function ItAddsDexterityModifierToArmorClass()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $defender->dexterity = 12;
        $roll = $defender->armorClass - $defender->dexterityModifier;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful == false);
    }

    public function ItRaisesAttackerExperienceBy10EachSuccessfulAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $xpPreAttack = $attacker->experience;

        //Act
        $attacker->attack($defender, $roll);
        $xpPostAttack = $attacker->experience;

        //Assert
        assert($xpPostAttack == $xpPreAttack + 10);
    }

    public function ItAttackerExperienceStaysTheSameOnFailedAttack()
    {
        //Arrange
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;
        $xpPreAttack = $attacker->experience;

        //Act
        $attacker->attack($defender, $roll);
        $xpPostAttack = $attacker->experience;

        //Assert
        assert($xpPostAttack == $xpPreAttack);
    }
}