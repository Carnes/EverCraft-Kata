<?php
class elvenChainMail_Tests implements testInterface
{
    private $armor;
    public function  initialize() {
        $this->armor  = equipmentFactory::startForge()
            ->withName("elven chainmail")
            ->withType(\Equipment\itemType::$Armor)
            ->withSubType(\Equipment\armorSubType::$ChainMail)
            ->withArmorClass(5)
            ->withArmorClassForRace(3,"Elf")
            ->withAttackForRace(1,"Elf")
            ->getEquipment();
    }

    public function ItIsArmor(){
        assert($this->armor->type == \Equipment\itemType::$Armor);
    }

    public function ItIsChainmail(){
        assert($this->armor->subType == \Equipment\armorSubType::$ChainMail);
    }

    public function ItHasAName()
    {
        assert($this->armor->name == "elven chainmail");
    }

    public function ItGivesPlus5AC()
    {
        //Arrange
        $c = new character();
        $noArmorAC = $c->armorClass;

        //Act
        $c->equipedArmor = $this->armor;
        $withArmorAC = $c->armorClass;

        //Assert
        assert($withArmorAC == $noArmorAC + 5);
    }

    public function ItGivesPlus8ACForAnElf()
    {
        //Arrange
        $c = new character();
        $c->setRace(new elfRace());
        $noArmorAC = $c->armorClass;

        //Act
        $c->equipedArmor = $this->armor;
        $withArmorAC = $c->armorClass;

        //Assert
        assert($withArmorAC == $noArmorAC + 8);
    }

    public function ItGivesPlus1AttackToElf()
    {
        //Arrange
        $human = new character();
        $human->equip($this->armor);
        $elf = new character();
        $elf->setRace(new elfRace());
        $elf->equip($this->armor);
        $defender = new character();
        $roll = $defender->armorClass;

        //Act
        $isHumanAttackSuccessful = $human->attack($defender, $roll);
        $isElfAttackSuccessful = $elf->attack($defender, $roll);

        //Assert
        assert($isHumanAttackSuccessful === false);
        assert($isElfAttackSuccessful === true);
    }
}