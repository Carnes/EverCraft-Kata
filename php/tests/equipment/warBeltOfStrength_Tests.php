<?php
class warBeltOfStrength_Tests implements testInterface
{
    private $belt;
    public function  initialize() {
        $this->belt  = equipmentFactory::startForge()
            ->withName("war belt of strength")
            ->withType(\Equipment\itemType::$Belt)
            ->withSubType(\Equipment\beltSubType::$Magic)
            ->withAbilityModifier("strength",4)
            ->getEquipment();
    }

    public function ItIsABelt(){
        assert($this->belt->type == \Equipment\itemType::$Belt);
    }

    public function ItIsAMagicBelt(){
        assert($this->belt->subType == \Equipment\beltSubType::$Magic);
    }

    public function ItHasAName()
    {
        assert($this->belt->name == "war belt of strength");
    }

    public function ItGivesPlus4Strength()
    {
        //Arrange
        $c = new character();
        $strNoBelt = $c->strength;

        //Act
        $c->equip($this->belt);
        $strWithBelt = $c->strength;

        //Assert
        assert($strWithBelt == $strNoBelt + 4);
    }
}