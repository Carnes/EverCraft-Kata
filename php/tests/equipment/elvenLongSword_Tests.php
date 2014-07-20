<?php
class elvenLongSword_Tests implements testInterface
{
    private $els;
    public function initialize(){
        $this->els = weaponFactory::startForge()
            ->withName("Glamdring")
            ->withSubType(\Equipment\weaponSubType::$Longsword)
            ->withDamage(6)
            ->withDamageForRace(2, "Elf")
            ->withDamageAgainstRace(2, "Orc")
            ->withDamageForRaceAndAgainstRace(1, "Elf", "Orc")
            ->withAttackForRace(2, "Elf")
            ->withAttackAgainstRace(2, "Orc")
            ->withAttackForRaceAgainstRace(1, "Elf", "Orc")
            ->withType(\Equipment\itemType::$Weapon)
            ->getEquipment();
    }

    public function ItIsAPieceOfEquipment()
    {
        //Arrange / Act
        $interfaces = class_implements($this->els);

        //Assert
        assert(in_array("Equipment\IEquipment",$interfaces));
    }

    public function ItIsAWeapon()
    {
        assert($this->els->type == \Equipment\itemType::$Weapon);
    }

    public function ItIsALongsword()
    {
        assert($this->els->subType == \Equipment\weaponSubType::$Longsword);
    }

    public function ItHasName()
    {
        assert($this->els->name == "Glamdring");
    }

    public function ItDoes6PointsOfDamage()
    {
        assert($this->els->getDamage()==6);
    }

    public function ItDoesPlus2ToDamageWhenWielderIsElf()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $attacker->setRace(new elfRace());
        $defender = new character();
        $roll = 18;
        $preHP = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 9);
    }

    public function ItDoesPlus2ToDamageWhenTargetIsOrc()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $defender = new character();
        $defender->setRace(new orcRace());
        $roll = 18;
        $preHP = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 9);
    }
    public function ItDoesPlus5ToDamageWhenTargetIsOrcAndWielderIsElf()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $attacker->setRace(new elfRace());
        $defender = new character();
        $defender->setRace(new orcRace());
        $roll = 18;
        $preHP = $defender->hitPoints;

        //Act
        $attacker->attack($defender, $roll);
        $damageDone = $preHP - $defender->hitPoints;

        //Assert
        assert($damageDone == 12);
    }

    public function ItDoesPlus2ToAttackWhenWielderIsElf()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $attacker->setRace(new elfRace());
        $defender = new character();
        $roll = $defender->armorClass - 1;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }

    public function ItDoesPlus2ToAttackWhenTargetIsOrc()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $defender = new character();
        $defender->setRace(new orcRace());
        $roll = $defender->armorClass - 1;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }

    public function ItDoesPlus5ToAttackWhenTargetIsOrcAndWielderIsElf()
    {
        //Arrange
        $attacker = new character();
        $attacker->wieldedWeapon = $this->els;
        $attacker->setRace(new elfRace());
        $defender = new character();
        $defender->setRace(new orcRace());
        $roll = $defender->armorClass - 4;

        //Act
        $isAttackSuccessful = $attacker->attack($defender, $roll);

        //Assert
        assert($isAttackSuccessful === true);
    }
}