<?php
class magicLeatherArmor_Tests implements testInterface
{
    private $armor;
    public function  initialize() {
        $this->armor  = equipmentFactory::startForge()
            ->withName("magic leather armor")
            ->withType(\Equipment\itemType::$Armor)
            ->withSubType(\Equipment\armorSubType::$Leather)
            ->withArmorClass(2)
            ->withDamageReduction(2)
            ->getEquipment();
    }

    public function ItIsArmor(){
        assert($this->armor->type == \Equipment\itemType::$Armor);
    }

    public function ItIsLeatherArmor(){
        assert($this->armor->subType == \Equipment\armorSubType::$Leather);
    }

    public function ItHasAName()
    {
        assert($this->armor->name == "magic leather armor");
    }

    public function ItGivesPlus2AC()
    {
        //Arrange
        $c = new character();
        $noArmorAC = $c->armorClass;

        //Act
        $c->equipedArmor = $this->armor;
        $withArmorAC = $c->armorClass;

        //Assert
        assert($withArmorAC == $noArmorAC + 2);
    }

    public function ItReducesAllDamageBy2()
    {
        //Arrange
        $attacker = new character();
        $defenderWithoutArmor = new character();
        $defenderWithArmor = new character();

        //Act
        $defenderWithArmor->equip($this->armor);
        $attacker->attack($defenderWithoutArmor, 20);
        $attacker->attack($defenderWithArmor, 20);

        //Assert
        assert($defenderWithArmor->hitPoints == $defenderWithoutArmor->hitPoints + 2);
    }
}