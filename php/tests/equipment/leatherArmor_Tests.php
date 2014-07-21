<?php
class leatherArmor_Tests implements testInterface
{
    private $armor;
    public function  initialize() {
        $this->armor  = equipmentFactory::startForge()
            ->withName("leather armor")
            ->withType(\Equipment\itemType::$Armor)
            ->withSubType("leather")
            ->withArmorClass(2)
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
        assert($this->armor->name == "leather armor");
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
}