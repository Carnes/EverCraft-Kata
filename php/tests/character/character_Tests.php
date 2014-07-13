<?php
include_once ("../main/character.php");
include_once ("../main/alignment.php");

class character_Tests implements testInterface
{
    public function initialize() {

    }

    public function itExists(){
        assert(class_exists("character"));
    }

    public function itHasNameProperty(){
        assert(property_exists("character","name"));
    }

    public function itHasAlignmentProperty(){
        assert(property_exists("character","alignment"));
    }

    public function itHasExperiencePoints(){
        assert(property_exists("character","experience"));
    }

    public function alignmentPropertySetIgnoresBadValue(){
        //Arrange
        $c = new character();

        //Act
        $c->alignment = "junk";

        //Assert
        assert($c->alignment != "junk");
    }

    public function alignmentPropertySetUsesGoodValue(){
        //Arrange
        $c = new character();

        //Act
        $c->alignment = "Good";

        //Assert
        assert($c->alignment == "Good");
    }

    public function itHasArmorClassOf10(){
        $c = new character();

        assert(property_exists($c,"armorClass"));
        assert($c->armorClass == 10);
    }

    public function ItDefaultsToLevel1AtZeroXP()
    {
        $c = new character();
        assert($c->level == 1);
    }

    public function ItHasLevelForEachThousandXP()
    {
        $c = new character();
        $c->experience=12345;
        assert($c->level == 13);
    }

    public function CharWillNotSetNonCharacterClassClass()
    {
        $c = new character();
        $c->addClass("character");
        assert(count($c->class) == 0);
    }
}