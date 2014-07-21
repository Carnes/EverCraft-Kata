<?php
class woodShield_Tests implements testInterface
{
    private $shield;
    public function  initialize() {
        $this->shield  = equipmentFactory::startForge()
            ->withName("wood shield")
            ->withType(\Equipment\itemType::$Shield)
            ->withSubType(\Equipment\shieldSubType::$Wood)
            ->withArmorClass(4)
            ->getEquipment();
    }

    public function ItIsArmor(){
        assert($this->shield->type == \Equipment\itemType::$Shield);
    }

    public function ItIsPlateArmor(){
        assert($this->shield->subType == \Equipment\shieldSubType::$Wood);
    }

    public function ItHasAName()
    {
        assert($this->shield->name == "wood shield");
    }

    public function ItGivesPlus4AC()
    {
        //Arrange
        $c = new character();
        $noArmorAC = $c->armorClass;

        //Act
        $c->equipedShield = $this->shield;
        $withArmorAC = $c->armorClass;

        //Assert
        assert($withArmorAC == $noArmorAC + 4);
    }
}