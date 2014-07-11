<?php
include_once ("../main/combat.php");
include_once ("../main/character.php");

class attack_Tests implements testInterface
{
    public function initialize(){

    }

    public function ItExists()
    {
        assert(class_exists("combat"));
        assert(method_exists("combat","attack"));
    }

    public function ItReturnsTrueOnAttackSuccess()
    {
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;

        $combatResult = $c->attack($attacker, $defender, $roll);
        assert($combatResult === true);
    }

    public function ItReturnsFalseWhenRollNotGreaterThanDefenderAC()
    {
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;

        $combatResult = $c->attack($attacker, $defender, $roll);
        assert($combatResult === false);
    }

    public function ItDealsOneToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $attacker->strength = 12;
        $defender = new character();
        $roll = $defender->armorClass;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-(1+$attacker->strengthModifier));
    }

    public function ItDealsOneDamagePlusSTRModifierToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;
        $attacker->strength = 12;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-(1+$attacker->strengthModifier));
    }

    public function ItDealsOneDamageEvenWithMinusFiveModifierToDefenderOnSuccessfulAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $defender->armorClass = 5;
        $roll = 11;
        $hpPreAttack = $defender->hitPoints;
        $attacker->strength = 1;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-1);
    }

    public function ItDealsZeroDamageToDefenderOnFailedAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack);
    }

    public function ItDealsDoubleDamageToDefenderOnSuccessfulCriticalAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = 20;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack-2);
    }

    public function ItAddsDexterityModifierToArmorClass()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $defender->dexterity = 12;
        $roll = $defender->armorClass+1;
        $hpPreAttack = $defender->hitPoints;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $hpPostAttack = $defender->hitPoints;

        //Assert
        assert($hpPostAttack == $hpPreAttack);
    }

    public function ItRaisesAttackerExperienceBy10EachSuccessfulAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass+1;
        $xpPreAttack = $attacker->experience;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $xpPostAttack = $attacker->experience;

        //Assert
        assert($xpPostAttack == $xpPreAttack + 10);
    }

    public function ItAttackerExperienceStaysTheSameOnFailedAttack()
    {
        //Arrange
        $c = new combat();
        $attacker = new character();
        $defender = new character();
        $roll = $defender->armorClass;
        $xpPreAttack = $attacker->experience;

        //Act
        $combatResult = $c->attack($attacker, $defender, $roll);
        $xpPostAttack = $attacker->experience;

        //Assert
        assert($xpPostAttack == $xpPreAttack);
    }
}