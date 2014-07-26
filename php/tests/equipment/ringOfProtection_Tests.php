<?php
class ringOfProtection_Tests implements testInterface
{
    private $ring;
    public function  initialize() {
        $this->ring  = equipmentFactory::startForge()
            ->withName("tarnished silver ring of protection")
            ->withType(\Equipment\itemType::$Ring)
            ->withSubType(\Equipment\ringSubType::$Magic)
            ->withArmorClass(2)
            ->withRequiredBodySlot(\Equipment\slotType::$Finger)
            ->getEquipment();
    }

    public function ItIsARing(){
        assert($this->ring->type == \Equipment\itemType::$Ring);
    }

    public function ItIsAMagicRing(){
        assert($this->ring->subType == \Equipment\ringSubType::$Magic);
    }

    public function ItHasAName()
    {
        assert($this->ring->name == "tarnished silver ring of protection");
    }

    public function ItGivesPlus2AC()
    {
        //Arrange
        $c = new character();
        $noRingAC = $c->armorClass;

        //Act
        $c->equip($this->ring);
        $withRingAC = $c->armorClass;

        //Assert
        assert($withRingAC == $noRingAC + 2);
    }
}