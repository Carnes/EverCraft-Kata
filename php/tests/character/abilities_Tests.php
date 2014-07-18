<?php
include_once("character/character.php");

class abilities_Tests implements testInterface
{
    private $character;
    public function Initialize()
    {
        $this->character = new character();
    }

    public function ItHasAbilities()
    {
        assert(property_exists($this->character, "strength"));
        assert(property_exists($this->character, "dexterity"));
        assert(property_exists($this->character, "constitution"));
        assert(property_exists($this->character, "wisdom"));
        assert(property_exists($this->character, "intelligence"));
        assert(property_exists($this->character, "charisma"));
    }

    public function ItDefaultsAbilitiesTo10()
    {
        assert($this->character->strength == 10);
        assert($this->character->dexterity == 10);
        assert($this->character->constitution == 10);
        assert($this->character->wisdom == 10);
        assert($this->character->intelligence == 10);
        assert($this->character->charisma == 10);
    }

    private function lookupCheck($value,$expectedValue)
    {
        $this->character->strength=$value;
        assert($this->character->strengthModifier == $expectedValue);
    }

    public function ItHasAbilityModifiersBasedOnLookupTable()
    {
        $this->lookupCheck(1,-5);
        $this->lookupCheck(2,-4);
        $this->lookupCheck(3,-4);
        $this->lookupCheck(4,-3);
        $this->lookupCheck(5,-3);
        $this->lookupCheck(6,-2);
        $this->lookupCheck(7,-2);
        $this->lookupCheck(8,-1);
        $this->lookupCheck(9,-1);
        $this->lookupCheck(10,0);
        $this->lookupCheck(11,0);
        $this->lookupCheck(12,1);
        $this->lookupCheck(13,1);
        $this->lookupCheck(14,2);
        $this->lookupCheck(15,2);
        $this->lookupCheck(16,3);
        $this->lookupCheck(17,3);
        $this->lookupCheck(18,4);
        $this->lookupCheck(19,4);
        $this->lookupCheck(20,5);
    }
}