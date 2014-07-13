<?php
include_once ("../main/character.php");
include_once ("../main/alignment.php");

class hitPoints_Tests implements testInterface
{
    public function initialize() {

    }

    public function itHasHitPointsOf5(){
        $c = new character();

        assert(property_exists($c,"hitPoints"));
        assert($c->hitPoints == 5);
    }

    public function itIsAliveIfHitPointsOverZero(){
        $c = new character();
        $c->hitPoints=1;
        assert($c->isAlive === true);
    }

    public function itIsNotAliveIfHitPointsZeroOrLess(){
        $c = new character();
        $c->takeDamage($c->hitPoints);
        assert($c->isAlive === false);
    }

    public function ItHasMaxHitPointsOf5ForLevel1()
    {
        $c = new character();
        assert($c->maxHitPoints == 5);
    }

    public function ItHasMaxHitPointsOf10ForLevel2()
    {
        $c = new character();
        $c->experience+=1000;
        assert($c->maxHitPoints == 10);
    }

    public function ItHasMaxHitPointsOf5PlusConstitutionModifier()
    {
        $c = new character();
        $c->constitution=12;
        assert($c->maxHitPoints == 6);
    }

    public function ItHasMaxHitPointsOf11AtLevel2PlusConstitutionModifier()
    {
        $c = new character();
        $c->constitution=12;
        $c->experience=1000;
        assert($c->maxHitPoints == 11);
    }
}