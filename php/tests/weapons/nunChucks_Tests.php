<?php
class nunChucks_Tests implements testInterface
{
    private $nunChucks;
    public function initialize(){
        $this->nunChucks = weaponFactory::startForge()
            ->withName("nun chucks")
            ->withDamage(6)
            ->withAttackForNonClass(-4, "War Monk")
            ->getWeapon();
    }

    public function ItIsAWeapon()
    {
        //Arrange / Act
        $interfaces = class_implements($this->nunChucks);

        //Assert
        assert(in_array("Weapon\IWeapon",$interfaces));
    }

    public function ItHasName()
    {
        assert($this->nunChucks->name == "nun chucks");
    }

    public function ItDoes6PointsOfDamage()
    {
        assert($this->nunChucks->getDamage()==6);
    }

    public function ItDoesMinus4AttackForNonMonk()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->nunChucks;
        $defender = new character();
        $roll = $defender->armorClass + 4;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === false);
    }

    public function ItDoesMinusZeroAttackForMonk()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->nunChucks;
        $attacker->addClass(availableClasses::WarMonk);
        $defender = new character();
        $roll = $defender->armorClass + 1;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }
}