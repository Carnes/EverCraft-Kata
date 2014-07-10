<?php
include ("../main/character.php");
include ("../main/alignment.php");

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

    public function itHasHitPointsOf5(){
        $c = new character();

        assert(property_exists($c,"hitPoints"));
        assert($c->hitPoints == 5);
    }
}