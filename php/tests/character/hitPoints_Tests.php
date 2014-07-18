<?php
include_once ("character/character.php");
include_once ("character/alignment.php");

class hitPoints_Tests implements testInterface
{
    private $char;
    public function initialize() {
        $this->char = new character();
    }

    public function itHasHitPointsOf5(){
        assert(property_exists($this->char,"hitPoints"));
        assert($this->char->hitPoints == 5);
    }

    public function itIsAliveIfHitPointsOverZero(){
        $this->char->hitPoints=1;
        assert($this->char->isAlive === true);
    }

    public function itIsNotAliveIfHitPointsZeroOrLess(){
        $this->char->takeDamage($this->char->hitPoints);
        assert($this->char->isAlive === false);
    }

    public function ItHasMaxHitPointsOf5ForLevel1()
    {
        assert($this->char->maxHitPoints == 5);
    }

    public function ItHasMaxHitPointsOf10ForLevel2()
    {
        $this->char->experience+=1000;
        assert($this->char->maxHitPoints == 10);
    }

    public function ItHasMaxHitPointsOf5PlusConstitutionModifier()
    {
        $this->char->constitution=12;
        assert($this->char->maxHitPoints == 6);
    }

    public function ItHasMaxHitPointsOf11AtLevel2PlusConstitutionModifier()
    {
        $this->char->constitution=12;
        $this->char->experience=1000;
        assert($this->char->maxHitPoints == 11);
    }
}