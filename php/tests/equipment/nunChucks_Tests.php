<?php
class nunChucks_Tests implements testInterface
{
    private $nunChucks;
    public function initialize(){
        $this->nunChucks = equipmentFactory::startForge()
            ->withName("nun chucks")
            ->withSubType(\Equipment\weaponSubType::$Nunchucks)
            ->withType(\Equipment\itemType::$Weapon)
            ->withDamage(6)
            ->withAttackForNonClass(-4, "War Monk")
            ->getEquipment();
    }

    public function ItIsAPieceOfEquipment()
    {
        //Arrange / Act
        $interfaces = class_implements($this->nunChucks);

        //Assert
        assert(in_array("Equipment\IEquipment",$interfaces));
    }

    public function ItIsAWeapon()
    {
        assert($this->nunChucks->type == \Equipment\itemType::$Weapon);
    }

    public function ItIsNunChucks()
    {
        assert($this->nunChucks->subType == \Equipment\weaponSubType::$Nunchucks);
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
        $attacker->equipedWeapon = $this->nunChucks;
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
        $attacker->equipedWeapon = $this->nunChucks;
        $attacker->addClass(availableClasses::WarMonk);
        $defender = new character();
        $roll = $defender->armorClass + 1;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }
}