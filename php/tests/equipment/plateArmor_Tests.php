<?php
class plateArmor_Tests implements testInterface
{
    private $armor;
    public function  initialize() {
        $this->armor  = equipmentFactory::startForge()
            ->withName("plate armor")
            ->withType(\Equipment\itemType::$Armor)
            ->withSubType(\Equipment\armorSubType::$Plate)
            ->withArmorClass(8)
            ->withRestrictionForOnlyTheseClasses(["Fighter"])
            ->withRestrictionForOnlyTheseRaces(["Dwarf"])
            ->getEquipment();
    }

    public function ItIsArmor(){
        assert($this->armor->type == \Equipment\itemType::$Armor);
    }

    public function ItIsPlateArmor(){
        assert($this->armor->subType == \Equipment\armorSubType::$Plate);
    }

    public function ItHasAName()
    {
        assert($this->armor->name == "plate armor");
    }

    public function ItGivesPlus8AC()
    {
        //Arrange
        $c = new character();
        $c->addClass(availableClasses::Fighter);
        $noArmorAC = $c->armorClass;

        //Act
        $c->equipedArmor = $this->armor;
        $withArmorAC = $c->armorClass;

        //Assert
        assert($withArmorAC == $noArmorAC + 8);
    }

    public function ItCanOnlyBeWornByFighters()
    {
        //Arrange
        $c = new character();
        $noArmor = $c->equipedArmor;

        //Act
        $c->equipedArmor = $this->armor;
        $equipedArmor = $c->equipedArmor;

        //Assert
        assert($equipedArmor == $noArmor);
    }

    public function ItCanAlsoBeWornByDwarves()
    {
        //Arrange
        $c = new character();
        $c->setRace(new dwarfRace());
        $noArmor = $c->equipedArmor;

        //Act
        $c->equipedArmor = $this->armor;
        $equipedArmor = $c->equipedArmor;

        //Assert
        assert($equipedArmor == $this->armor);
    }

}